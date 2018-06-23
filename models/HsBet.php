<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
 * @property int $type [int(11)]
 * @property int $goals_a [int(11)]
 * @property int $goals_b [int(11)]
 * @property int $type2 [int(11)]
 * @property string $goals [varchar(1024)]
 * @property string $half_game [varchar(1024)]
 * @property string $score [varchar(1024)]
 * @property int $repeat [int(11)]
 * @property string $guess [varchar(1024)]
 * @property string $odds [varchar(512)]
 * @property string $fee [varchar(32)]
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
            [['game_id', 'choosed_option', 'user_id', 'created_at', 'updated_at', 'status', 'goals_a', 'goals_b', 'repeat'], 'integer'],
            ['goals', 'string', 'max' => 1024],
            ['half_game', 'string', 'max' => 1024],
            ['score', 'string', 'max' => 1024],
            ['guess', 'string', 'max' => 1024],
            ['odds', 'string', 'max' => 512],
            ['fee', 'string', 'max' => 32],
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

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }
}
