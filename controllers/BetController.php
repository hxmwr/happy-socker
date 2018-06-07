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
use app\models\HsGuessChampion;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BetController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGetGameList() {
        $timeStart = Yii::$app->request->post('timeBegin');
        $timeEnd = Yii::$app->request->post('timeEnd');

        $games = HsGames::find()
            ->where(['status' => 1])
            ->andWhere(['<', 'time_begin', $timeEnd])
            ->andWhere(['>', 'time_end', $timeStart])
            ->asArray()
            ->all();

        return ['code' => 1, 'message' => '操作成功', 'data' => $games];
    }

    public function actionDoBet() {
        // 胜平负，0, 1, 2
        $bets = Yii::$app->request->post('bets');
        $userId = Yii::$app->user->id;
        $maxBetCount = Yii::$app->params['maxBetCount'];

        if (count($bets) > $maxBetCount) {
            return ['code' => 0, 'message' => '超过最大投注数'];
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

        return ['code' => 1, 'message' => '下注成功'];
    }

    public function actionGetTeams() {
        $teams = file_get_contents(Yii::getAlias('@app/data/teams.txt'));
        $teams = explode("\n", $teams);
        return ['code' => 1, 'message' => '操作成功', 'data' => $teams];
    }

    public function actionGuessChampion() {
        $userId = Yii::$app->user->id;
        $guesses = Yii::$app->request->post('guesses');
        $teams = file_get_contents(Yii::getAlias('@app/data/teams.txt'));
        $teams = explode("\n", $teams);

        if (count($guesses) > count($teams)) {
            return ['code' => 0, 'message' => '超过最大允许数'];
        }

        $res = [];
        foreach ($guesses as $guess) {
            $bet = intval($guess['bet']);
            $team = $guess['team'];
            if (in_array($team, $teams)) {
                $res[] = ['team' => $team, 'bet' => $bet];
            }
        }

        $guessChampion = new HsGuessChampion();
        $guessChampion->user_id = $userId;
        $guessChampion->guess = json_encode($res);
        $guessChampion->save();

        return ['code' => 1, 'message' => '操作成功'];
    }
}