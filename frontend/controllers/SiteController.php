<?php

namespace frontend\controllers;

use common\models\Actions;
use common\models\ActionsContent;
use common\models\Article;
use common\models\ArticleSearch;
use common\models\Category;
use common\models\Content;
use common\models\Order;
use common\models\Product;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;


/**
 * Site controller
 */
class SiteController extends Controller
{

    private $_category_arr = [];

    /**
     *      * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

//        $this->view->title = Content::findOne(1)->title;
        return $this->render('index');
    }


    public function action1cTree()
    {

        $all_cats = Category::find()->all();
        foreach ($all_cats as $cat) {
            $this->_category_arr[$cat['parent_code']][] = $cat;
        }

        $this->outTree('', 0);

        exit;

    }

    private function outTree($parent_id, $level)
    {
        if (isset($this->_category_arr[$parent_id])) {
            foreach ($this->_category_arr[$parent_id] as $value) {
                echo "<div style='
                margin-left:" . ($level * 50) . "px; 
                font-size:" . (30 - $level * 2) . "px;
                color: rgb(" . (255 - $level * 25) . ",".(0 + $level * 35). ",".(100 + $level * 25).");
                '>" . $value->name . "</div>";
                $level++;
                $this->outTree($value->code, $level);
                $level--;
            }
        }
    }


    public function actionView()
    {
        return $this->render('view');
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $signupModel = new SignupForm();
        $passwordModel = new PasswordResetRequestForm();

        if ($passwordModel->load(Yii::$app->request->post()) && $passwordModel->validate()) {
            if ($passwordModel->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свой почтовый ящик для дальнейших инструкций');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
                return $this->goBack();
            }
        }


        if ($signupModel->load(Yii::$app->request->post()) && $user = $signupModel->signup()) {
            if (Yii::$app->getUser()->login($user)) {
                Yii::$app->session->setFlash('success', 'Добро пожаловать!');
                return $this->goBack();
            }
        } else if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('success', 'Добро пожаловать!');
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', compact('model', 'signupModel', 'passwordModel'));
    }


    public function actionCabinet()
    {
        $user = Yii::$app->user->identity;

        if (!empty($phone = Yii::$app->request->post('User')['phone'])) {
            $user->phone = $phone;
            $user->save();
        }

//        $actions = Actions::getDiscounts();
//        $lastOrders = Order::find()->where(['user_id' => $user->id])->orderBy(['date' => SORT_DESC])->limit(5)->all();

        return $this->render('cabinet', compact(
//            'actions',
            'user'
//            ,'lastOrders'
        ));
    }


    public function actionActions()
    {

        $contents = ActionsContent::find()->all();
        $actions = Actions::getDiscounts();

        return $this->render('actions', compact('actions', 'contents'));
    }


    public function actionArticles()
    {

        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('articles', compact('products', 'searchModel', 'dataProvider'));
    }

    public function actionArticleView($id)
    {
        return $this->render('article-view', [
            'model' => Article::findOne($id),
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public
    function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $content = Content::findOne(1);

        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Спасибо за вопрос! Мы ответим Вам в ближайшее время!');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', compact(['model', 'content']));
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public
    function actionAbout()
    {
        return $this->render('about');
    }


    public function actionAllImages()
    {

        $products = Product::find()->all();

        return $this->render('all-images', compact('products'));
    }


    public
    function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль установлен.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
