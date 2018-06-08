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

    <?= $form->field($model, 'goals_diff')->dropDownList([
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
    ]) ?>

    <?= $form->field($model, 'coefficient_on_win')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coefficient_on_lost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coefficient_on_draw')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'result_possibilities')->textarea(['maxlength' => true, 'rows' => 5]) ?>

    <?= $form->field($model, 'goals')->textarea(['maxlength' => true, 'rows' => 5]) ?>

    <?= $form->field($model, 'half_game')->textarea(['maxlength' => true, 'rows' => 5]) ?>

    <?= $form->field($model, 'type')->dropDownList(['1' => '胜平负', '2' => '让球胜平负', '3' => '半全场', '4' => '猜比分', '5' => '进球数']) ?>

    <?= $form->field($model, 'type2')->dropDownList(['1' => '过关', '2' => '单关']) ?>

    <?= $form->field($model, 'status')->dropDownList(['1' => '开启', '0' => '关闭']) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
