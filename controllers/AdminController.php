<?php

namespace app\controllers;

use yii\filters\AccessControl;
use app\models\Request;
use yii\data\ActiveDataProvider;
use app\models\Status;
use yii\web\NotFoundHttpException;

class AdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['*'],
                    'rules' => [
                        [
                            'actions' => ['index', 'success', 'reject'],
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return \Yii::$app->user->identity->isAdmin();
                            }
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Request::find(),

            'pagination' => [
                'pageSize' => 3
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],

        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSuccess($id)
    {
        $model = $this->findModel($id);

        if ($model->status->code === 'new') {
            $model->link('status', Status::findOne(['code' => 'approve']));
            $model->save();
        }

        return $this->redirect(['index']);
    }

    public function actionReject($id)
    {
        $model = $this->findModel($id);

        if ($model->status->code === 'new') {
            $model->link('status', Status::findOne(['code' => 'rejected']));
            $model->save();
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Request::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
