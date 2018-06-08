<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HsGames */

$this->title = 'Update Hs Games: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '玩法', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hs-games-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
