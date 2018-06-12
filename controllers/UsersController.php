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
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        $user = HsUsers::findOne(['username' => $username, 'password' => md5($password)]);
        if ($user) {
            $identity = new User();
            $identity->userEntity = $user;
            Yii::$app->user->login($identity);

            return ['code' => 1, 'message' => '登录成功'];
        } else {
            return ['code' => 0, 'message' => '登录失败'];
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return ['code' => 1, 'message' => '退出成功'];
    }

    public function actionRegister() {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $uniqueCode = Yii::$app->request->post('uniqueCode');

        if (($linkedUser = HsUsers::findOne(['unique_code' => $uniqueCode])) == null) {
            return ['code' => 0, 'message' => '无效的邀请码'];
        }

        if (HsUsers::findOne(['username' => $username])) {
            return ['code' => 0, 'message' => '用户已存在'];
        }

        $user = new HsUsers();
        $user->password = md5($password);
        $user->username = $username;
        $user->linked_user_id = $linkedUser->id;

        if ($user->save()) {
            return ['code' => 1, 'message' => '注册成功'];
        } else {
            return ['code' => 0, 'message' => '注册失败', 'errors' => $user->getErrors()];
        }
    }

    public function actionStatus() {
        return ['code' => Yii::$app->user->isGuest?0:1];
    }
}