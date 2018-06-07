<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\HsGames */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '比赛列表', 'url' => ['index']];
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
        ],
    ]) ?>

</div>
