<?php

namespace hqt\admin\components;

use hqt\admin\models\BizRule;
use Yii;
use hqt\admin\models\AuthItem;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @property integer $type
 * @property array $labels
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ItemController extends Controller
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
                    'assign' => ['post'],
                    'remove' => ['post'],
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
            'items' => AuthItem::getAllModels($this->type),
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

        return ['code' => 20000, 'data' => array_merge([
            'item' => $model,
        ], $model->getItems())];
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem(null);
        $model->type = $this->type;
        if ($model->load(Yii::$app->getRequest()->post(), '') && $model->save()) {
            return ['code' => 20000, 'data' => [
                'item' => $model,
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
        if ($model->load(Yii::$app->getRequest()->post(), '') && $model->save()) {
            return ['code' => 20000, 'data' => [
                'item' => $model,
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
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->addChildren($items);
        return ['code' => 20000, 'data' => array_merge($model->getItems(), ['success' => $success])];
    }

    /**
     * Assign or remove items
     * @param string $id
     * @return array
     */
    public function actionRemove($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->removeChildren($items);
        return ['code' => 20000, 'data' => array_merge($model->getItems(), ['success' => $success])];
    }

    /**
     * Type of Auth Item.
     * @return integer
     */
    public function getType()
    {

    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $auth = Configs::authManager();
        $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($id) : $auth->getPermission($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
