<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HsUsers */

$this->title = '新建用户';
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hs-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
