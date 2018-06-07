<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HsBet */

$this->title = 'Update Hs Bet: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Hs Bets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hs-bet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
