<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "hs_withdraw".
 *
 * @property int $id
 * @property int $total
 * @property string $alipay
 * @property int $created_at
 * @property int $updated_at
 * @property int $user_id
 * @property int $status
 */
class HsWithdraw extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hs_withdraw';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total', 'created_at', 'updated_at', 'user_id', 'status'], 'integer'],
            [['alipay'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total' => 'Total',
            'alipay' => 'Alipay',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }
}
