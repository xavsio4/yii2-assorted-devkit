<?php
namespace common\helpers;

use Yii;
use yii\filters\Cors;

/**
 * Allow POST GET PUT with preflight options
 */

class CorsCustom extends Cors

{
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT');
            Yii::$app->end();
        }

        return true;
    }
}