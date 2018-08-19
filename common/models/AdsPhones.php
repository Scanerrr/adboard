<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ads_phones".
 *
 * @property int $id
 * @property int $ad_id
 * @property string $phone
 * @property int $type
 *
 * @property Ads $ad
 */
class AdsPhones extends \common\models\Ads
{
    const VIBER = 1;
    const TELEGRAM = 2;
    const WHATSAPP = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ads_phones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ad_id', 'phone', 'type'], 'required'],
            [['ad_id', 'type'], 'integer'],
            [['phone'], 'string', 'max' => 255],
            [['phone'], 'safe'],
            [['type'], 'default', 'value' => 0],
            [['ad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ads::className(), 'targetAttribute' => ['ad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_id' => 'Ad ID',
            'phone' => 'Phone',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Ads::className(), ['id' => 'ad_id']);
    }
}
