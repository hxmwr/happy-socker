<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hs_bet".
 *
 * @property int $id
 * @property int $game_id
 * @property int $choosed_option
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 */
class HsBet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hs_bet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['game_id', 'choosed_option', 'user_id', 'created_at', 'updated_at', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'choosed_option' => 'Choosed Option',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }
}
