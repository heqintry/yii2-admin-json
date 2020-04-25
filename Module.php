<?php

namespace hqt\admin;

use Yii;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        if (!isset(Yii::$app->i18n->translations['rbac-admin'])) {
            Yii::$app->i18n->translations['rbac-admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@hqt/admin/messages',
            ];
        }
    }
}