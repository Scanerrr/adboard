<?php

namespace common\models;


/**
 * This is the model class for table "ads_images".
 *
 * @property int $id
 * @property int $ad_id
 * @property string $image
 *
 * @property Ads $ad
 */
class AdsImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ad_id', 'image'], 'required'],
            [['ad_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['ad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ads::className(), 'targetAttribute' => ['ad_id' => 'id']],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg',
                'maxSize'=> 5*1024*1024, 'maxFiles' => 7,
                'uploadRequired' => 'Выберите файл для загрузки',
                'wrongExtension' => 'Поддерживаются файлы следующих разширень: *.png, *.jpg, *.jpeg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ad_id' => 'Ad ID',
            'image' => 'Доп изображения',
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
