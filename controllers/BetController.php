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
use app\models\HsUsers;
use app\models\Settings;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
                'except' => ['get-odds'],
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

    public function actionGetGameList()
    {
        $timeBegin = Yii::$app->request->post('timeBegin');
        $timeEnd = Yii::$app->request->post('timeEnd');
        $type = Yii::$app->request->post('type', 1);
        $type2 = Yii::$app->request->post('type2', 1);
        $leagueType = Yii::$app->request->post('leagueType', '世界杯');

        $games = HsGames::find()
            ->where(['status' => 1, 'type' => $type, 'type2' => $type2, 'league_type' => $leagueType])
            ->andWhere(['<', 'time_begin', $timeEnd])
            ->andWhere(['>', 'time_end', $timeBegin])
            ->asArray()
            ->all();

        return ['code' => 1, 'message' => '操作成功', 'data' => $games];
    }

    public function actionDoBet()
    {
        // 胜平负，0, 1, 2
        $bets = Yii::$app->request->post('bets');
        $multi = Yii::$app->request->post('multi', 1);
        $type = intval(Yii::$app->request->post('type', 1));
        $type2 = intval(Yii::$app->request->post('type2', 1));
        $userId = Yii::$app->user->id;
        $maxBetCount = Yii::$app->params['maxBetCount'];
        /**
         * @var HsUsers $user
         */
        $user = Yii::$app->user->identity->userEntity;


        if ($type2 == 3) {
            $settings = new Settings();
            $odds = $settings['odds'];
            $price = $settings['price'];

            $selection = explode(',', $bets);
            $total = (count($selection) * $price);

            if ($user->coins < $total) {
                return ['code' => 0, 'message' => '您的余额不足，请及时充值'];
            }

            $user->coins -= $total;
            $user->save();

            $model = new HsBet();
            $model->repeat = intval($multi);
            $model->type2 = $type2;
            $model->type = 1;
            $model->guess = $bets;
            $model->user_id = $userId;
            $model->half_game = $odds;
            $model->save();

            return ['code' => 1, 'message' => '投注成功'];
        }


        if (count($bets) > $maxBetCount || count($bets) < 1) {
            return ['code' => 0, 'message' => '超过最大投注数'];
        }

        /*
         * 1. 胜平负
         * 2. 让球胜平负
         * 3. 半全场
         * 4. 猜比分
         * 5. 进球数
         */


        if ($multi < 1 || $multi > 9999) {
            return ['code' => 0, 'message' => '倍数错误'];
        }

        $res = [];

        foreach ($bets as $bet) {
            if (!isset($res[$bet[0]]))
                $res[$bet[0]] = [];

            $res[$bet[0]][] = $bet[1];
        }


        if (count($res) < 1)
            return ['code' => 0, 'message' => '参数错误'];
        if (count($res) > 8)
            return ['code' => 0, 'message' => '超过最大限制'];

        if ($type2 == 1) {
            if (count($res) < 2) {
                return ['code' => 0, 'message' => '最少选2场'];
            }

            $cnt = 1;
            foreach ($res as $v) {
                $cnt = $cnt * count($v);
            }
            $total = $cnt * 2;
        } else if ($type2 == 2) {
            $cnt = 0;
            foreach ($res as $v) {
                $cnt += count($v);
            }
            $total = $cnt * 2;
        }

        if (!$cnt) {
            return ['code' => 0, 'message' => '参数错误'];
        }

        $settings = new Settings();
        $fee = $total * intval($settings->price);

        if (intval($user->coins) < $fee) {
            return ['code' => 0, 'message' => '您的余额不足，请及时充值', 'error' => -1];
        }

        $user->coins -= intval($fee);
        $user->save();

        $model = new HsBet();
        $model->repeat = $multi;
        $model->type2 = $type2;
        $model->type = $type;
        $model->guess = json_encode($res);
        $model->score = implode(',', array_keys($res));
        $model->user_id = $userId;
        $model->save();

//        if ($type2 == 1) { // 过关
//            $gameIds = array_unique(ArrayHelper::getColumn($bets, 'gameId'));
//            if (count($gameIds) < 2) {
//                return ['code' => 0, 'message' => '参数错误'];
//            }
//
//            if ($type == 1) { // 胜平负
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->repeat = intval($repeat);
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->choosed_option = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } elseif ($type == 2) { // 让球胜平负
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->repeat = intval($repeat);
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->choosed_option = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } elseif ($type == 3) { // 半全场
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->repeat = intval($repeat);
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->half_game = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } elseif ($type == 4) { // 猜比分
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->repeat = intval($repeat);
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->score = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } elseif ($type == 5) { // 进球数
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->repeat = intval($repeat);
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->goals = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } else {
//                return ['code' => 0, 'message' => '参数错误'];
//            }
//        } elseif ($type2 == 2) { // 单关
//            if ($type == 1) { // 胜平负
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->choosed_option = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } elseif ($type == 2) { // 让球胜平负
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->choosed_option = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } elseif ($type == 3) { // 半全场
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->half_game = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } elseif ($type == 4) { // 猜比分
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->score = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } elseif ($type == 5) { // 进球数
//                foreach ($bets as $bet) {
//                    $model = new HsBet();
//                    $model->type2 = $type2;
//                    $model->type = $type;
//                    $model->user_id = $userId;
//                    $model->goals = $bet['guess'];
//                    $model->game_id = intval($bet['gameId']);
//                    if ($model->save()) {
//                        return ['code' => 1, 'message' => '投注成功'];
//                    } else {
//                        return ['code' => 0, 'message' => '投注失败', 'errors' => $model->getErrors()];
//                    }
//                }
//            } else {
//                return ['code' => 0, 'message' => '参数错误'];
//            }
//        }

        return ['code' => 1, 'message' => '下注成功'];
    }

    public function actionGetTeams()
    {
        $teams = file_get_contents(Yii::getAlias('@app/data/teams.txt'));
        $teams = explode("\n", $teams);
        return ['code' => 1, 'message' => '操作成功', 'data' => $teams];
    }

    public function actionGetBetList() {
        $bets = HsBet::find()->where(['user_id' => Yii::$app->user->id])->all();
        return ['code' => 1, 'data' => $bets];
    }

    public function actionGuessChampion()
    {
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

    public function actionGetPrice() {
        $settings = new Settings();
        return ['code' => 1, 'data' => $settings->price];
    }

    public function actionGetOdds() {
        $settings = new Settings();
        return ['code' => 1, 'data' => explode(',', $settings['odds'])];
    }
}