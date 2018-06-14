<?php
/**
 * Code: Dx.M
 * Email: 2424428867@qq.com
 * Date: 2018/6/13
 * Time: 9:01 PM
 */

namespace app\models;


use yii\base\Model;

class ResultSetting extends Model
{
    public $setting;

    public function save() {

    }

    public function rules()
    {
        return [
            ['setting', 'string']
        ];
    }

    public function attributeLabels()
    {
        return ['setting' => '比分设置'];
    }
}