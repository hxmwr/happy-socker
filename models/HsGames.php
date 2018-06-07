<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "hs_games".
 *
 * @property int $id
 * @property string $team_a
 * @property string $team_b
 * @property string $time_begin
 * @property string $time_end
 * @property int $result
 * @property string $coefficient_on_win
 * @property string $coefficient_on_lost
 * @property string $coefficient_on_draw
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 */
class HsGames extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hs_games';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time_begin', 'time_end'], 'safe'],
            [['time_begin', 'time_end'], 'required'],
            [['result', 'created_at', 'updated_at', 'status', 'type'], 'integer'],
            [['coefficient_on_win', 'coefficient_on_lost', 'coefficient_on_draw'], 'number'],
            [['coefficient_on_win', 'coefficient_on_lost', 'coefficient_on_draw'], 'required'],
            [['team_a', 'team_b'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'team_a' => '主队',
            'team_b' => '客队',
            'time_begin' => '起始时间',
            'time_end' => '截止时间',
            'result' => '比赛结果',
            'coefficient_on_win' => '获胜赔率',
            'coefficient_on_lost' => '失败赔率',
            'coefficient_on_draw' => '平局赔率',
            'created_at' => '创建日期',
            'updated_at' => '更新日期',
            'status' => '状态',
            'type' => '赛事'
        ];
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }
}
