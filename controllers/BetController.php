<?php
/**
 * Code: Dx.M
 * Email: 2424428867@qq.com
 * Date: 2018/6/7
 * Time: 11:56 PM
 */

namespace app\controllers;


use app\models\HsBet;
use app\models\HsGames;
use Yii;

class BetController extends BaseController
{
    public function actionGetGameList() {
        $timeStart = Yii::$app->request->post('timeBegin');
        $timeEnd = Yii::$app->request->post('timeEnd');

        $games = HsGames::find()
            ->where(['status' => 1])
            ->andWhere(['<', 'time_begin', $timeEnd])
            ->andWhere(['>', 'time_end', $timeStart])
            ->asArray()
            ->all();

        return ['code' => 0, 'msg' => '操作成功', 'data' => $games];
    }

    public function actionDoBet() {
        // 胜平负，0, 1, 2
        $bets = Yii::$app->request->post('bets');
        $userId = Yii::$app->user->id;
        $maxBetCount = Yii::$app->params['maxBetCount'];

        if (count($bets) > $maxBetCount) {
            return ['code' => 101, 'msg' => '超过最大投注数'];
        }

        foreach ($bets as $bet) {
            $guess = intval($bet['guess']);
            if (HsGames::findOne($bet['gameId']) && $guess >=0 && $guess <=2) {
                $model = new HsBet();
                $model->user_id = $userId;
                $model->game_id = $bet['gameId'];
                $model->save();
            }
        }

        return ['code' => 0, 'msg' => '下注成功'];
    }

    public function actionGuessChampion() {

    }
}