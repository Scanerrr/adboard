<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;

/**
 * This is the model class for table "ads".
 *
 * @property int $id
 * @property string $title
 * @property int $category_id
 * @property int $city_id
 * @property int $user_id
 * @property string $price
 * @property string $description
 * @property string $phone
 * @property string $image
 * @property int $status
 * @property int $updated_at
 * @property int $created_at
 *
 * @property User $user
 * @property Categories $category
 * @property City $city
 * @property Statuses $status0
 * @property AdsImages[] $adsImages
 * @property UserAdWatches[] $userAdWatches
 */
class Ads extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;


    /**
     * @Statuses
     */
    const STATUS_ACTIVE = 1;
    const STATUS_MODERATING = 2;
    const STATUS_DISABLED = 3;
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
            [['title', 'category_id', 'city_id', 'price'], 'required', 'message' => '{attribute} должно быть заполнено'],
            [['category_id', 'city_id', 'user_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['updated_at', 'created_at'], 'default', 'value' => time()],

            [['price'], 'double', 'min' => 0, 'message' => '{attribute} должно быть числом'],

            [['title'], 'string', 'max' => 70],
            [['title'], 'string'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg',
                'maxSize'=> 5*1024*1024, 'maxFiles' => 1,
                'uploadRequired' => 'Выберите файл для загрузки',
                'wrongExtension' => 'Поддерживаются файлы следующих разширень: *.png, *.jpg, *.jpeg'],
            ['status', 'default', 'value' => self::STATUS_MODERATING],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_MODERATING, self::STATUS_DISABLED]],
            [['title', 'description'], 'filter', 'filter' => function($value) {
                return strip_tags(HTMLPurifier::process($value));
            }]
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                // 'value' => new Expression('NOW()'),
            ],
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
            'user_id' => 'Пользователь',
            'price' => 'Цена',
            'description' => 'Описание',
            'image' => 'Главное изображение',
            'status' => 'Статус объявления',
            'phone' => 'Номер телефона',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Statuses::className(), ['id' => 'status']);
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

    public function getImageUrl()
    {
        return $this->image ? \Yii::$app->request->BaseUrl . DIRECTORY_SEPARATOR . $this->image : '/img/placeholder.png';
    }

    public function upload()
    {
        if ($this->validate('imageFile')) {
            $dir = 'uploads/';
            $subdir = Yii::$app->session->id . DIRECTORY_SEPARATOR;
            $directory = Yii::getAlias('@frontend/web/'.$dir) . $subdir;
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }
            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($directory . $fileName);
            return $dir . $subdir . $fileName;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        //Delete images location
        try {
            @unlink(Yii::getAlias('@frontend/web/' . $this->image));
        } catch (\Exception $e) {

        }
    }

}
