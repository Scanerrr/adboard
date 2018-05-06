<?php

namespace common\models;


/**
 * This is the model class for table "user_phones".
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone
 *
 * @property User $user
 */
class UserPhones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_phones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            ['phone', 'trim'],
            ['phone', 'required', 'message' => '{attribute} не может быть пустым'],
            ['phone', 'number', 'message' => '{attribute} не соответсвует международному формату'],
            ['phone', 'string', 'min' => 10, 'max' => 12,
                'tooShort' => '{attribute} не соответсвует международному формату',
                'tooLong' => '{attribute} не соответсвует международному формату'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'phone' => 'Номер телефона',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
