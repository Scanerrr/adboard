<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "shipping".
 *
 * @property int $id
 * @property string $method
 * @property string $description
 *
 * @property Ads[] $ads
 */
class Shipping extends \common\models\Ads
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['method'], 'required'],
            [['method'], 'string', 'max' => 24],
            [['description'], 'string', 'max' => 255],
            [['method'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'method' => 'Method',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ads::className(), ['shipping_id' => 'id']);
    }
}
