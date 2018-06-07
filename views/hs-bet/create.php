<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HsBet */

$this->title = 'Create Hs Bet';
$this->params['breadcrumbs'][] = ['label' => 'Hs Bets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hs-bet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
