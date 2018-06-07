<?php
/**
 * Code: Dx.M
 * Email: 2424428867@qq.com
 * Date: 2018/6/7
 * Time: 9:37 PM
 */

namespace app\controllers;


use Yii;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function init()
    {
        parent::init();
        \Yii::$app->language = 'zh-CN';
        Yii::$app->request->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $session = Yii::$app->session;
        if ($session->get(Yii::$app->user->idParam) == 'super_admin') {
            Yii::$app->user->logout();
        }
    }
}