<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HsGames */

$this->title = '编辑球队';
$this->params['breadcrumbs'][] = ['label' => '编辑球队', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hs-games-create">

    <h1><?= Html::encode($this->title) ?></h1>
<form action="/games/save-teams" method="post">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
    <textarea name="teams" class="form-control" rows="32" placeholder="参赛球队，每行一个"><?php echo $teams ?></textarea>
    <div style="margin-top: 10px;">
        <button class="btn btn-primary">保存</button>
    </div>
</form>

</div>
