<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HsGames */

$this->title = '创建比赛';
$this->params['breadcrumbs'][] = ['label' => '比赛列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hs-games-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
