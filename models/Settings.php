<?php
/**
 * Code: Dx.M
 * Email: 2424428867@qq.com
 * Date: 2018/6/12
 * Time: 11:32 PM
 */

namespace app\models;


use yii\base\Model;

class Settings extends Model
{
    public $price;
    public $odds;

    public function __construct(array $config = [])
    {
        parent::__construct();
        $json = file_get_contents(\Yii::getAlias('@app/data/settings.json'));
        $data = json_decode($json, true);
        $this->price = $data['price'];
        $this->odds = $data['odds'];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            ['price', 'integer'],
            ['odds', 'string'],
        ];
    }

    /**
     * @return array customized attribute labels
     */

    public function attributeLabels()
    {
        return [
            'price' => '每注单价',
            'odds' => '猜冠军赔率（逗号分割的整数，共32个）',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }

    public function save() {
        $json = json_encode(['price' => $this->price, 'odds' => $this->odds]);
        file_put_contents(\Yii::getAlias('@app/data/settings.json'), $json);
    }
}