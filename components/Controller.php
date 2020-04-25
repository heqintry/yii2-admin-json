<?php
namespace hqt\admin\components;

use Yii;

class Controller extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {
        parent::init();
        Yii::$app->getResponse()->format = 'json';
    }
}