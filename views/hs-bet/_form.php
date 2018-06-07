<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HsBet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hs-bet-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'game_id')->textInput() ?>

    <?= $form->field($model, 'choosed_option')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
