<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "hs_users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $mobile
 * @property string $email
 * @property string $unique_code
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $type
 * @property int $exp
 * @property int $gender
 * @property int $linked_user_id
 * @property int $coins [int(11)]
 */
class HsUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hs_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'type', 'exp', 'gender', 'linked_user_id', 'coins'], 'integer'],
            [['username', 'password'], 'string', 'max' => 32],
            [['mobile'], 'string', 'max' => 11],
            [['email'], 'string', 'max' => 64],
            [['unique_code'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'mobile' => '手机',
            'email' => '邮箱',
            'unique_code' => '邀请码',
            'status' => '状态',
            'created_at' => '创建日期',
            'updated_at' => '更新日期',
            'type' => '类型',
            'exp' => 'Exp',
            'gender' => '性别',
            'linked_user_id' => '邀请人ID'
        ];
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }
}
