<?php

namespace app\controllers;

use Yii;
use app\models\HsGames;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GamesController implements the CRUD actions for HsGames model.
 */
class GamesController extends BaseAdminController
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
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all HsGames models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => HsGames::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HsGames model.
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
     * Creates a new HsGames model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HsGames();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HsGames model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HsGames model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HsGames model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HsGames the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HsGames::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionTeams()
    {
        $teams = file_get_contents(Yii::getAlias('@app/data/teams.txt'));
        return $this->render('teams', ['teams' => $teams]);
    }

    public function actionSaveTeams()
    {
        $teams = Yii::$app->request->post('teams');

        $teams = trim($teams);
        if (!empty($teams)) {
            $arr = explode("\n", $teams);
            $res = [];
            foreach ($arr as $v) {
                $v = trim($v);
                if (!empty($v)) {
                    $res[] = $v;
                }
            }
            $teams = implode("\n", $res);
        }

        file_put_contents(Yii::getAlias('@app/data/teams.txt'), $teams);
        Yii::$app->session->setFlash('info', '保存成功');
        return $this->redirect('/games/teams');
    }
}
