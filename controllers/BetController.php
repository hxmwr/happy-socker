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
use app\models\HsWithdraw;
use app\models\Settings;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class BetController extends BaseController
{
    protected $teams = ["俄罗斯", "沙特", "埃及", "乌拉圭", "摩洛哥", "伊朗", "葡萄牙", "西班牙", "法国", "澳大利亚", "秘鲁", "丹麦", "阿根廷", "冰岛", "克罗地亚", "尼日利亚", "哥斯达黎加", "瑞士", "巴西", "塞尔维亚", "德国", "墨西哥", "瑞典", "韩国", "比利时", "巴拿马", "突尼斯", "英格兰", "哥伦比亚", "日本", "波兰", "塞尔维亚"];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['get-odds', 'update-odds'],
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
            ->orderBy('time_end asc')
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
            $model->guess = implode(',', $bets);
            $model->user_id = $userId;
            $model->half_game = $odds; // 使用 half_game 存放赔率
            $model->fee = strval($price);
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
            $total = $cnt;
        } else if ($type2 == 2) {
            $cnt = 0;
            foreach ($res as $v) {
                $cnt += count($v);
            }
            $total = $cnt;
        }

        if (!$cnt) {
            return ['code' => 0, 'message' => '参数错误'];
        }

        $settings = new Settings();
        $fee = $total * intval($settings->price) * intval($multi);

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
        $model->half_game = $this->getOdds($type, $res);
        $model->score = implode(',', array_keys($res));
        $model->user_id = $userId;
        $model->fee = strval($settings->price);
        $model->save();

        return ['code' => 1, 'message' => '下注成功'];
    }

    public function actionApplyWithdraw()
    {
        $alipay = Yii::$app->request->post('alipay');
        $total = Yii::$app->request->post('total');
        $total = intval($total);
        $user = Yii::$app->user->identity->userEntity;
        if ($total < 1 || $total > $user->coins) {
            return ['code' => 0, 'message' => '金额需大于1小于剩余金额'];
        }
        $withDraw = new HsWithdraw();
        $withDraw->total = $total;
        $withDraw->user_id = $user->id;
        $withDraw->alipay = $alipay;
        $withDraw->status = 0;
        $withDraw->save();

        $user->coins = $user->coins - $total;
        $user->save();

        return ['code' => 1, 'message' => '申请成功'];
    }

    public function actionGetTeams()
    {
        $teams = file_get_contents(Yii::getAlias('@app/data/teams.txt'));
        $teams = explode("\n", $teams);
        return ['code' => 1, 'message' => '操作成功', 'data' => $teams];
    }

    public function actionGetBetList()
    {
        $bets = HsBet::find()->where(['user_id' => Yii::$app->user->id])->orderBy('created_at desc')->all();
        return ['code' => 1, 'data' => $bets];
    }


    public function actionGetBetDetail()
    {
        $id = Yii::$app->request->post('id');
        $bet = HsBet::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);
        $type = $bet->type;

        $benefits = null;

        if ($bet->type2 == 1 || $bet->type2 == 2) {
            $bets = json_decode($bet->guess, true);
            $odds = json_decode($bet->half_game, true);
            $games = [];
            $results = [];
            foreach ($bets as $gameId => $b) {
                $game = HsGames::findOne(intval($gameId));
                $selection = $b;
                if ($type == 1) { // 胜平负
                    $parsedSelection = [];
                    foreach ($selection as $sel) {
                        $parsedSelection[] = ['胜', '平', '负'][intval($sel)] . "(" . sprintf('%.3f', $odds[$gameId]['odds'][intval($sel)]) . ")";
                    }
                    $games[] = [
                        'selection' => $parsedSelection,
                        'team_a' => $game->team_a,
                        'team_b' => $game->team_b,
                        'gameDate' => $game->time_end,
                        'goals_a' => $game->goals_a,
                        'goals_b' => $game->goals_b,
                        'h_goals_a' => $game->h_goals_a,
                        'h_goals_b' => $game->h_goals_b,
                        'game_id' => $game->id
                    ];
                    if ($game->goals_a === null) {
                        $results[] = null;
                    } else {
                        $results[] = $this->checkResult($game, $selection);
                    }
                } elseif ($type == 2) { // 让球胜平负
                    $parsedSelection = [];
                    foreach ($selection as $sel) {
                        $parsedSelection[] = ['胜', '平', '负'][intval($sel)] . "(" . sprintf('%.3f', $odds[$gameId]['odds'][intval($sel)]) . ")";
                    }
                    $games[] = [
                        'selection' => $parsedSelection,
                        'team_a' => $game->team_a,
                        'team_b' => $game->team_b,
                        'game_date' => $game->time_end,
                        'goals_a' => $game->goals_a,
                        'goals_b' => $game->goals_b,
                        'h_goals_a' => $game->h_goals_a,
                        'h_goals_b' => $game->h_goals_b,
                        'game_id' => $game->id
                    ];
                    if ($game->goals_a === null) {
                        $results[] = null;
                    } else {
                        $results[] = $this->checkResult($game, $selection);
                    }
                } elseif ($type == 3) { // 半全场
                    $parsedSelection = [];
                    $realOdds = explode(',', $odds[$gameId]['odds']);
                    foreach ($selection as $sel) {
                        $parsedSelection[] = ['胜胜', '胜平', '胜负', '平胜', '平平', '平负', '负胜', '负平', '负负'][intval($sel)] . "(" . sprintf('%.3f', $realOdds[intval($sel)]) . ")";
                    }
                    $games[] = [
                        'selection' => $parsedSelection,
                        'team_a' => $game->team_a,
                        'team_b' => $game->team_b,
                        'game_date' => $game->time_end,
                        'goals_a' => $game->goals_a,
                        'goals_b' => $game->goals_b,
                        'h_goals_a' => $game->h_goals_a,
                        'h_goals_b' => $game->h_goals_b,
                        'game_id' => $game->id
                    ];
                    if ($game->goals_a === null) {
                        $results[] = null;
                    } else {
                        $results[] = $this->checkResult($game, $selection);
                    }
                } elseif ($type == 4) { // 猜比分
                    $parsedSelection = [];
                    $oddsCategorized = explode("\n", $odds[$gameId]['odds']);
                    $realOdds = [];
                    foreach ($oddsCategorized as $ar) {
                        $realOdds = array_merge($realOdds, explode(',', $ar));
                    }
                    foreach ($selection as $sel) {
                        $odd = $realOdds[intval($sel)];
                        $tmp = explode("#", $odd);
                        $parsedSelection[] = $tmp[0] . "(" . sprintf('%.3f', $tmp[1]) . ")";
                    }
                    $games[] = [
                        'selection' => $parsedSelection,
                        'team_a' => $game->team_a,
                        'team_b' => $game->team_b,
                        'game_date' => $game->time_end,
                        'goals_a' => $game->goals_a,
                        'goals_b' => $game->goals_b,
                        'h_goals_a' => $game->h_goals_a,
                        'h_goals_b' => $game->h_goals_b,
                        'game_id' => $game->id
                    ];
                    if ($game->goals_a === null) {
                        $results[] = null;
                    } else {
                        $results[] = $this->checkResult($game, $selection);
                    }
                } elseif ($type == 5) { // 进球数
                    $parsedSelection = [];
                    $realOdds = explode(',', $odds[$gameId]['odds']);
                    foreach ($selection as $sel) {
                        $parsedSelection[] = ['0', '1', '2', '3', '4', '5', '6', '7+'][intval($sel)] . "(" . sprintf('%.3f', $realOdds[intval($sel)]) . ")";
                    }
                    $games[] = [
                        'selection' => $parsedSelection,
                        'team_a' => $game->team_a,
                        'team_b' => $game->team_b,
                        'game_date' => $game->time_end,
                        'goals_a' => $game->goals_a,
                        'goals_b' => $game->goals_b,
                        'h_goals_a' => $game->h_goals_a,
                        'h_goals_b' => $game->h_goals_b,
                        'game_id' => $game->id
                    ];
                    if ($game->goals_a === null) {
                        $results[] = null;
                    } else {
                        $results[] = $this->checkResult($game, $selection);
                    }
                }
            }
            if (empty($results))
                $benefits = null;
            $f = true;
            foreach ($results as $r) {
                if ($r === null)
                    $f = false;
            }
            if ($f) {
                if ($bet->type2 == 2) {
                    $total = 0;
                    foreach ($results as $k => $r) {
                        if ($r !== false) {
                            if ($bet->type == 1) {
                                $odd = $odds[$games[$k]['game_id']]['odds'][$r];
                                $total = $total + $odd * $bet->fee;
                            } elseif ($bet->type == 2) {
                                $odd = $odds[$games[$k]['game_id']]['odds'][$r];
                                $total = $total + $odd * $bet->fee;
                            } elseif ($bet->type == 3) {
                                $ar = explode(',', $odds[$games[$k]['game_id']]['odds']);
                                $odd = $ar[$r];
                                $total = $total + floatval($odd) * $bet->fee;
                            } elseif ($bet->type == 4) {
                                $ar = explode(',', $odds[$games[$k]['game_id']]['odds']);
                                $tmp = explode('#', $ar[$r]);
                                $odd = floatval($tmp[1]);
                                $total = $total + $odd * $bet->fee;
                            } elseif ($bet->type == 5) {
                                $ar = explode(',', $odds[$games[$k]['game_id']]['odds']);
                                $odd = $ar[$r];
                                $total = $total + floatval($odd) * $bet->fee;
                            }
                        }
                    }
                    $benefits = $total * $bet->repeat;
                } else {
                    $flag = true;
                    $total = 0;
                    foreach ($results as $r) {
                        if ($r === false || $r === null)
                            $flag = false;
                    }
                    if ($flag) {
                        $odd = 1;
                        foreach ($results as $k => $r) {
                            if ($bet->type == 1) {
                                $odd = $odd * $odds[$games[$k]['game_id']]['odds'][$r];
                            } elseif ($bet->type == 2) {
                                $odd = $odd * $odds[$games[$k]['game_id']]['odds'][$r];
                            } elseif ($bet->type == 3) {
                                $ar = explode(',', $odds[$games[$k]['game_id']]['odds']);
                                $odd = $odd * floatval($ar[$r]);
                            } elseif ($bet->type == 4) {
                                $ar = explode(',', $odds[$games[$k]['game_id']]['odds']);
                                $tmp = explode('#', $ar[$r]);
                                $odd = $odd * floatval($tmp[1]);
                            } elseif ($bet->type == 5) {
                                $ar = explode(',', $odds[$games[$k]['game_id']]['odds']);
                                $odd = $odd * floatval($ar[$r]);
                            }
                        }
                        $benefits = $odd * $bet->fee * $bet->repeat;
                    } else {
                        $benefits = 0;
                    }
                }
            }
        } elseif ($bet->type2 == 3) {
            $guess = $bet->guess;
            $selection = explode(',', $guess);
            $odds = explode(',', $bet->half_game);
            $games = [];
            $games[0] = [];
            $games[0]['type'] = 3;
            $games[0]['selection'] = array_map(function ($item) use ($odds) {
                return $this->teams[$item] . "(" . sprintf('%.3f', $odds[$item]) . ")";
            }, $selection);
//            $this->calcBenefits(null, $selection, $odds, $bet->fee);
        }

        $settings = new Settings();
        return ['code' => 1, 'data' => [
            'games' => $games,
            'repeat' => $bet->repeat,
            'price' => $settings->price,
            'benefits' => $benefits
        ]];
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

    public function actionGetPrice()
    {
        $settings = new Settings();
        return ['code' => 1, 'data' => $settings->price];
    }

    public function actionGetOdds()
    {
        $settings = new Settings();
        return ['code' => 1, 'data' => explode(',', $settings['odds'])];
    }

    public function actionGetUserCoins()
    {
        $user = Yii::$app->user->identity->userEntity;
        return ['code' => 1, 'data' => intval($user->coins), 'unicode' => $user->unique_code];
    }

    private function getOdds($type, $bets)
    {
        $odds = [];
        foreach ($bets as $gameId => $bet) {
            $game = HsGames::findOne(intval($gameId));
            if ($game) {
                if ($type == 1) {
                    $odds[$gameId] = ['odds' => [$game->coefficient_on_win, $game->coefficient_on_draw, $game->coefficient_on_lost]];
                } elseif ($type == 2) {
                    $odds[$gameId] = ['odds' => [$game->coefficient_on_win, $game->coefficient_on_draw, $game->coefficient_on_lost], 'diff' => $game->goals_diff];
                } elseif ($type == 3) {
                    $odds[$gameId] = ['odds' => $game->half_game];
                } elseif ($type == 4) {
                    $odds[$gameId] = ['odds' => $game->result_possibilities];
                } elseif ($type == 5) {
                    $odds[$gameId] = ['odds' => $game->goals];
                }
            }
        }
        return json_encode($odds);
    }

    public function actionUpdateOdds() {
        $bets = HsBet::find()->where('half_game is null')->all();
        foreach ($bets as $bet) {
            $bet->half_game = $this->getOdds($bet->type, json_decode($bet->guess, true));
            $bet->save();
        }
    }

    /**
     * @param HsGames $game
     * @param $selection
     * @return bool
     */
    protected function checkResult($game, $selection)
    {
        if ($game == null) {
            foreach ($selection as $sel) {

            }
            return false;
        } else {
            $type = $game->type;
            $type2 = $game->type2;
            $goals_a = intval($game->goals_a);
            $goals_b = intval($game->goals_b);
            $h_goals_a = intval($game->h_goals_a);
            $h_goals_b = intval($game->h_goals_b);
            $diff = ['', -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5, 0, 1, 1.5, 2, 2.5, 3, 3.5, 4][intval($game->goals_diff)];
            $result = ($goals_a + $h_goals_a) - ($goals_b + $h_goals_b);
            $half_result_1 = $h_goals_a - $h_goals_b;
            $half_result_2 = $goals_a - $goals_b;
            $total_goals = $goals_b + $goals_a + $h_goals_b + $h_goals_a;

            if ($type == 1) {
                if ($result > 0) {
                    foreach($selection as $sel) {
                        if (intval($sel) === 0)
                            return intval($sel);
                    }
                } elseif ($result == 0) {
                    foreach ($selection as $sel) {
                        if (intval($sel) === 1)
                            return intval($sel);
                    }
                } else {
                    foreach ($selection as $sel) {
                        if (intval($sel) === 2)
                            return intval($sel);
                    }
                }
            } elseif ($type == 2) {
                $result = ($diff + $goals_a + $h_goals_a) - ($goals_b + $h_goals_b);
                if ($result > 0) {
                    foreach($selection as $sel) {
                        if (intval($sel) === 0)
                            return intval($sel);
                    }
                } elseif ($result == 0) {
                    foreach ($selection as $sel) {
                        if (intval($sel) === 1)
                            return intval($sel);
                    }
                } else {
                    foreach ($selection as $sel) {
                        if (intval($sel) === 2)
                            return intval($sel);
                    }
                }
            } elseif ($type == 3) {
                if ($half_result_1 > 0)
                    $a = 1;
                elseif ($half_result_1 == 0)
                    $a = 2;
                elseif ($half_result_1 < 0)
                    $a = 3;
                if ($half_result_2 > 0)
                    $b = 1;
                elseif ($half_result_2 == 0)
                    $b = 2;
                elseif ($half_result_2 < 0)
                    $b = 3;

                $r = intval($a . $b);
                $ar = [11, 12, 13, 21, 22, 23, 31, 32, 33];
                $idx = array_search($r, $ar);
                foreach ($selection as $sel) {
                    if (intval($sel) == $idx)
                        return $idx;
                }
            } elseif ($type == 4) {
                $score = ($goals_a + $h_goals_a) . ':' . ($goals_b + $h_goals_b);
                $scoreCategorized = explode("\n", $game->result_possibilities);
                $scores = [];
                foreach ($scoreCategorized as $s) {
                    $a = explode(',', $s);
                    $a2 = array_map(function($v) {
                        $tmp = explode('#', $v);
                        $tmp2 = explode(':', $tmp[0]);
                        return $tmp2[0] . ':' . $tmp2[1];
                    }, $a);
                    $scores = array_merge($scores, $a2);
                }
                $idx = array_search($score, $scores);
                foreach ($selection as $sel) {
                    if (intval($sel) == $idx)
                        return $idx;
                }
            } elseif ($type == 5) {
                $ar = explode(',', $game->goals);
                if ($total_goals > count($ar) - 1) {
                    $idx = count($ar) - 1;
                } else {
                    $idx = $total_goals;
                }
                foreach ($selection as $sel) {
                    if (intval($sel) == $idx)
                        return $idx;
                }
            }
            return false;
        }
    }
}