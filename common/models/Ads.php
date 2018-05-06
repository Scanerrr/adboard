<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ads".
 *
 * @property int $id
 * @property string $title
 * @property int $category_id
 * @property int $city_id
 * @property string $price
 * @property string $description
 * @property string $telephone
 * @property string $image
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Categories $category
 * @property City $city
 * @property AdsImages[] $adsImages
 * @property UserAdWatches[] $userAdWatches
 */
class Ads extends \yii\db\ActiveRecord
{

    public $imageFiles;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'category_id', 'city_id', 'price', 'telephone'], 'required', 'message' => 'Поле {attribute} должно быть заполнено'],
            [['category_id', 'city_id'], 'integer'],
            [['description'], 'string'],
            [['telephone'], 'number'],
            [['price'], 'double'],
            [['title'], 'string', 'max' => 70],
            [['title'], 'string'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg',
                'maxSize'=> 5*1024*1024, 'maxFiles' => 10,
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
            'title' => 'Название объявления',
            'category_id' => 'Категория',
            'city_id' => 'Город',
            'price' => 'Цена',
            'description' => 'Описание',
            'telephone' => 'Номер телефона',
            'image' => 'Выбрать изображения'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdsImages()
    {
        return $this->hasMany(AdsImages::className(), ['ad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAdWatches()
    {
        return $this->hasMany(UserAdWatches::className(), ['ad_id' => 'id']);
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $key => $file) {
                $this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
                if ($key == 0) $this->image = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            }
            return true;
        } else {
            return false;
        }
    }
}
