<?php

namespace frontend\controllers;

use common\models\Actions;
use common\models\ActionsContent;
use common\models\Article;
use common\models\ArticleSearch;
use common\models\Category;
use common\models\Content;
use common\models\Order;
use common\models\PageContent;
use common\models\Post;
use common\models\Product;
use common\models\User;
use frontend\components\LiqPay;
use frontend\components\NovaPoshta;
use frontend\components\NovaPoshtaApi2;
use frontend\components\Tree_1C;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
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


    private $pages;

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

    public function init()
    {
        parent::init();
        $this->pages = PageContent::getPages();
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

        $this->view->title = Content::findOne(1)->title;

        return $this->render('index');
    }


    public function action1cTree()
    {
        $tree = new Tree_1C();
        echo $tree->renderTree();
        exit;
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
        if (!$user = Yii::$app->user->getIdentity())
            return $this->redirect('/login');

        $user->load(Yii::$app->request->post());
        $user->save();

        $postProvider = new ActiveDataProvider([
            'query' => Post::find()->andWhere(['user_id' => $user->id]),
            'sort' => array(
                'defaultOrder' => ['time' => SORT_DESC],
            ),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

//        $actions = Actions::getDiscounts();
        $lastOrders = Order::find()->where(['user_id' => $user->id])->orderBy(['date' => SORT_DESC])->limit(5)->all();

        return $this->render('cabinet', compact(
//            'actions',
            'user',
            'postProvider'
            , 'lastOrders'
        ));
    }


    public function actionOrderView($id)
    {

        $model = Order::find()->where(['id' => $id, 'user_id' => Yii::$app->user->getIdentity()->id])->one();
        if ($model) {
            return $this->render('order-view', compact('model'));
        } else {
            return $this->redirect('/cabinet');
        }
    }

    public function actionActions()
    {
//        $contents = ActionsContent::find()->all();
//        $actions = Actions::getDiscounts();
        $content = $this->pages->actions;
        return $this->render('actions', compact('content'));
    }


    public function actionDelivery()
    {
        $content = $this->pages->delivery;
        return $this->render('delivery', compact('content'));
    }

    public function actionPayment()
    {
        $content = $this->pages->payment;
        return $this->render('payment', compact('content'));
    }

    public function actionGuarantee()
    {
        $content = $this->pages->guarantee;
        return $this->render('guarantee', compact('content'));
    }

    public function actionAgreements()
    {
        $content = $this->pages->agreements;
        return $this->render('agreements', compact('content'));
    }

    public function actionVacancies()
    {
        $content = $this->pages->vacancies;
        return $this->render('vacancies', compact('content'));
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
