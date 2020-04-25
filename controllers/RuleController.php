<?php

namespace hqt\admin\controllers;

use hqt\admin\components\Controller;
use Yii;
use hqt\admin\models\BizRule;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use hqt\admin\components\Helper;
use hqt\admin\components\Configs;

/**
 * Description of RuleController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RuleController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        return ['code' => 20000, 'data' => [
            'rules' => BizRule::getAllModels(),
        ]];
    }

    /**
     * Displays a single AuthItem model.
     * @param  string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return ['code' => 20000, 'data' => [
            'rule' => $model,
        ]];
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BizRule(null);
        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            Helper::invalidate();

            return ['code' => 20000, 'data' => [
                'rule' => $model,
            ]];
        } else {
            return ['code' => -1, 'message' => $model->getFirstErrors()];
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            Helper::invalidate();

            return ['code' => 20000, 'data' => [
                'rule' => $model,
            ]];
        } else {
            return ['code' => -1, 'message' => $model->getFirstErrors()];
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (Configs::authManager()->remove($model->item)) {
            Helper::invalidate();

            return ['code' => 20000, 'message' => 'success'];
        } else {
            return ['code' => 20000, 'message' => 'error'];
        }
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  string $id
     * @return AuthItem      the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $item = Configs::authManager()->getRule($id);
        if ($item) {
            return new BizRule($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
