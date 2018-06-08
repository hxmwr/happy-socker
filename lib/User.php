<?php
/**
 * Code: Dx.M
 * Email: 2424428867@qq.com
 * Date: 2018/6/7
 * Time: 9:25 PM
 */

namespace app\lib;


use app\models\HsUsers;
use Yii;
use yii\web\IdentityInterface;
use app\models\User as AdminUser;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface

{

    public $userEntity;
    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
//        $session = Yii::$app->getSession();
//        $uid = $session->getHasSessionId() || $session->getIsActive() ? $session->get('__id') : null;
//
//        if ($uid == 'super_admin') {
//            return AdminUser::findIdentity($id);
//        }

        $userEntity = HsUsers::findOne($id);
        if ($userEntity) {
            $identity = new static();
            $identity->userEntity = $userEntity;
            return $identity;
        } else {
            return null;
        }
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->userEntity->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return md5($this->userEntity->id . $this->userEntity->password);
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return md5($this->userEntity->id . $this->userEntity->password) == $authKey;
    }

    public function getUsername() {
        return $this->userEntity->username;
    }
}