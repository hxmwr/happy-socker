<?php
/**
 * Code: Dx.M
 * Email: 2424428867@qq.com
 * Date: 2018/6/7
 * Time: 9:11 PM
 */

namespace app\controllers;


use app\lib\User;
use app\models\HsUsers;
use Yii;

class UsersController extends BaseController
{
    public function actionLogin() {
        $username = Yii::$app->request->get('username');
        $password = Yii::$app->request->get('password');

        $user = HsUsers::findOne(['username' => $username, 'password' => md5($password)]);
        if ($user) {
            $identity = new User();
            $identity->userEntity = $user;
            Yii::$app->user->login($identity);

            return ['code' => 0, 'msg' => '登录成功'];
        } else {
            return ['code' => 101, 'msg' => '登录失败'];
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return ['code' => 0, 'msg' => '退出成功'];
    }

    public function actionRegister() {
        $username = Yii::$app->request->get('username');
        $password = Yii::$app->request->get('password');
        $uniqueCode = Yii::$app->request->get('uniqueCode');

        if (($linkedUser = HsUsers::findOne(['unique_code' => $uniqueCode])) == null) {
            return ['code' => 102, 'msg' => '无效的邀请码'];
        }

        $user = new HsUsers();
        $user->password = $password;
        $user->username = $username;
        $user->linked_user_id = $linkedUser->id;

        if ($user->save()) {
            return ['code' => 0, 'msg' => '注册成功'];
        } else {
            return ['code' => 103, 'msg' => '注册失败', 'errors' => $user->getErrors()];
        }
    }
}