<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '玩法列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hs-games-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('创建玩法', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            ['label' => '单关/过关', 'value' => 'type2'],
            ['label' => '玩法', 'value' => 'type'],
            'team_a',
            'team_b',
            'time_begin:datetime',
            'time_end:datetime',
            ['label' => '半全场设置', 'value' => 'half_game'],
            //'result',
            //'coefficient_on_win',
            //'coefficient_on_lost',
            //'coefficient_on_draw',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
