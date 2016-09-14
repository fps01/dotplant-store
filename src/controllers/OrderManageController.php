<?php

namespace DotPlant\Store\controllers;

use DevGroup\Multilingual\models\Context;
use Yii;
use DotPlant\Store\models\order\Order;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderManageController implements the CRUD actions for Order model.
 */
class OrderManageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    { // @todo: add permissions
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex($contextId = null)
    {
        $contexts = Context::find()->all();
        if ($contextId === null && count($contexts) > 0) {
            $contextId = reset($contexts)->id;
        }
        $tabs = [];
        foreach ($contexts as $context) {
            $tabs[] = [
                'active' => $context->id == $contextId,
                'label' => $context->name,
                'url' => ['index', 'contextId' => $context->id],
            ];
        }
        $dataProvider = new ActiveDataProvider(
            [
                'query' => Order::find()->where(['context_id' => $contextId]),
            ]
        );
        return $this->render(
            'index',
            [
                'contextId' => $contextId,
                'dataProvider' => $dataProvider,
                'tabs' => $tabs,
            ]
        );
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id = null)
    {
        if ($id === null) {
            $model = new Order;
            $model->loadDefaultValues();
        } else {
            $model = $this->findModel($id);
        }
        $model->scenario = 'backend-order-updating';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['edit', 'id' => $model->id]);
        } else {
            return $this->render(
                'edit',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
