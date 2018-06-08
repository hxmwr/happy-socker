<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HsGames */

$this->title = '创建玩法';
$this->params['breadcrumbs'][] = ['label' => '玩法列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hs-games-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
