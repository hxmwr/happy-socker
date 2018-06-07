<?php

use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HsGames */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hs-games-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'team_a')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team_b')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'time_begin')->textInput(['placeholder' => 'e.g. 2018-06-07 22:00:00']) ?>

    <?= $form->field($model, 'time_end')->textInput(['placeholder' => 'e.g. 2018-06-07 22:00:00']) ?>

    <?= $form->field($model, 'result')->textInput() ?>

    <?= $form->field($model, 'coefficient_on_win')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coefficient_on_lost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coefficient_on_draw')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => '开启', '0' => '关闭']) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
