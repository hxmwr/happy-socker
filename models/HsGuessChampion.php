<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hs_guess_champion".
 *
 * @property int $id
 * @property string $guess
 * @property int $user_id
 * @property int $created_at
 */
class HsGuessChampion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hs_guess_champion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at'], 'integer'],
            [['guess'], 'string', 'max' => 2048],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'guess' => 'Guess',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }
}
