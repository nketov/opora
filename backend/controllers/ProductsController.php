<?php

namespace backend\controllers;

use app\models\ImageUploadForm;
use Yii;
use common\models\Product;
use common\models\ProductSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductsController implements the CRUD actions for Product model.
 */
class ProductsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $searchModel->withoutImageShow= 1;
        $searchModel->withoutPricesShow= 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        exit;
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelImageUpload = new ImageUploadForm();


        if (!empty(Yii::$app->request->post()['ImageUploadForm']['key'])) {

            if (empty($modelImageUpload->image = UploadedFile::getInstance($modelImageUpload, 'image'))) {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            $modelImageUpload->load(Yii::$app->request->post());

            if ($modelImageUpload->key == 'new') {
                $images = $model->getImages();
                for ($i = 1; $i < 6; $i++) {
                    if (!array_key_exists('image_' . $i, $images)) {
                        $modelImageUpload->key = 'image_' . $i;
                        break;
                    }
                }
            }

            if ($modelImageUpload->upload()) {
                $key = $modelImageUpload->key;
                $model->$key = 'p_' . $id . '_' . $key . '.' . $modelImageUpload->image->extension;
                $model->save();
                return $this->render('update', [
                    'model' => $model,
                    'message' => 'Фото успешно загружено!',
                ]);
            }

        } else {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(Yii::$app->user->returnUrl);
            }
        }

        if (!mb_strpos( Yii::$app->request->referrer ,'image-upload')) {
            Yii::$app->user->returnUrl = Yii::$app->request->referrer;
        }


        if (!empty($delete_key=Yii::$app->request->get()['delete_key'])){
            $model->$delete_key = '';
            $model->save();
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public
    function actionImageUpload()
    {
        $model = new ImageUploadForm();

        foreach (Yii::$app->request->get() as $key => $val) {
            $model[$key] = $val;
        };

        return $this->render('image-upload', compact('model'));
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public
    function actionActive()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model = self::findModel($data['product']);
            $model->active = $data['active'];
            $model->save();
            return true;
        }
    }


    protected
    function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
