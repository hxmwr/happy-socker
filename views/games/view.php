<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\HsGames */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '玩法列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hs-games-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'team_a',
            'team_b',
            'time_begin:datetime',
            'time_end:datetime',
            'result',
            'coefficient_on_win',
            'coefficient_on_lost',
            'coefficient_on_draw',
            'created_at:datetime',
            'updated_at:datetime',
            ['label' => '让球数', 'value' => [
                '1' => '-4',
                '2' => '-3.5',
                '3' => '-3',
                '4' => '-2.5',
                '5' => '-2',
                '6' => '-1.5',
                '7' => '-1',
                '8' => '-0.5',
                '9' => '0',
                '10' => '0.5',
                '11' => '1',
                '12' => '1.5',
                '13' => '2',
                '14' => '2.5',
                '15' => '3',
                '16' => '3.5',
                '17' => '4',
            ][$model->goals_diff]],
            ['label' => '竞猜类型', 'value' => ['', '胜平负', '让球胜平负', '半全场', '猜比分', '进球数', ''][$model->type]],
            ['label' => '过关/单关', 'value' => ['','过关', '单关'][$model->type2]],
            ['label' => '比分设置', 'value' => $model->result_possibilities],
            ['label' => '半全场设置', 'value' => $model->half_game],
            ['label' => '进球数设置', 'value' => $model->goals],
        ],
    ]) ?>

</div>
