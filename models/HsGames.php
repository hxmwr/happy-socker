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
 * @property string $type [varchar(32)]
 * @property int $goals_a [int(11)]
 * @property int $goals_b [int(11)]
 * @property int $goals_diff [int(11)]
 * @property bool $type2 [tinyint(4)]
 * @property string $result_possibilities [varchar(1024)]
 * @property string $half_game [varchar(512)]
 * @property string $goals [varchar(512)]
 * @property string $league_type [varchar(32)]
 * @property int $h_goals_a [int(11)]
 * @property int $h_goals_b [int(11)]
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
            [['result', 'created_at', 'updated_at', 'status', 'type', 'goals_a', 'goals_b', 'h_goals_a', 'h_goals_b', 'goals_diff', 'type2'], 'integer'],
            [['coefficient_on_win', 'coefficient_on_lost', 'coefficient_on_draw'], 'number'],
            [['team_a', 'team_b'], 'string', 'max' => 64],
            [['team_a', 'team_b'], 'required'],
            [['result_possibilities'], 'string', 'max' => 1024],
            [['half_game'], 'string', 'max' => 512],
            [['goals'], 'string', 'max' => 512],
            [['league_type'], 'string', 'max' => 32],
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
            'goals_a' => '主队进球数',
            'h_goals_a' => '半场主队进球数',
            'h_goals_b' => '半场客队进球数',
            'goals_b' => '客队进球数',
            'time_begin' => '起始时间',
            'time_end' => '截止时间',
            'result' => '比赛结果',
            'coefficient_on_win' => '获胜赔率',
            'coefficient_on_lost' => '失败赔率',
            'coefficient_on_draw' => '平局赔率',
            'created_at' => '创建日期',
            'updated_at' => '更新日期',
            'status' => '状态',
            'type' => '竞猜类型',
            'type2' => '过关/单关',
            'result_possibilities' => '比分设置',
            'goals' => '进球数设置',
            'half_game' => '半全场设置',
            'goals_diff' => '让球数',
            'league_type' => '赛事'
        ];
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }
}
