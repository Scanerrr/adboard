<?php

namespace common\models;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
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
            [['category_id', 'city_id', 'user_id', 'status', 'count_view'], 'integer'],
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
            ]
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
            'count_view' => 'Просмотренно',
            'updated_at' => 'Время',
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


    public function getImageUrl()
    {
        $url =  isset(Yii::$app->urlManagerFrontend) ? Yii::$app->urlManagerFrontend->BaseUrl : Yii::$app->request->BaseUrl;
        return $this->image ? $url . DIRECTORY_SEPARATOR . $this->image : '';
    }

    public function upload()
    {
        if ($this->validate('imageFile')) {
            $dir = 'uploads/';
            $subdir = $this->user_id . '/';
            $directory = Yii::getAlias('@root/'.$dir) . $subdir;
            if (!is_dir($directory)) {
                try {
                    FileHelper::createDirectory($directory);
                } catch (Exception $e) {
                }
            }
            $uid = uniqid(time());
            $fileName = $uid . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs(FileHelper::normalizePath($directory . $fileName));
            return FileHelper::normalizePath($dir . $subdir . $fileName);
        } else {
            return false;
        }
    }

//    TODO: delete images
    public function afterDelete()
    {
//        FileHelper::unlink();
        //Delete images location
        try {
//            FileHelper::removeDirectory(Yii::getAlias('@frontend/web/' . dirname($this->image)));
        } catch (ErrorException $e) {
        }
    }

    public static function searchAds($place = null, $ad = null)
    {
        // check if only place is set then return ads by place
        if ($place && !$ad) return self::getAdsByPlace($place);
        // check if only ad name is set then return ads by name
        elseif ($ad && ! $place) return self::getAdsByName($ad);
        // check if place and ad name is set then return ads by name and by place
        else {
            $adsByPlace = self::getAdsByPlace($place);
            var_dump($adsByPlace); die();
        }
    }

    /**
     * get Ads by ad name
     *
     * @param null $query
     * @return $this|ActiveQuery
     */
    public static function getAdsByName($query = null)
    {
        return Ads::find()->filterWhere(['or', ['like', 'title', $query], ['like', 'description', $query]]);
    }

    /**
     * get Ads by place name (region or city name)
     *
     * @param null $query
     * @return $this|array|bool|ActiveQuery
     */
    public static function getAdsByPlace($query = null)
    {
        if (!$query) return false;
        // check if the place in regions table
        if (!$region = Region::findOne(['name_ru' => $query])) {
            $city = City::findOne(['name_ru' => $query]);
            return Ads::find()->where(['city_id' => $city->id]);
        }
        return self::getAdsByRegionId($region->id);
    }

    /**
     * get array of Ads object
     *
     * @param null $id
     * @return $this|bool|ActiveQuery
     */
    public static function getAdsByRegionId($id = null)
    {
        if (!$id) return false;
        $cities = City::find()->select('id')->where(['region_id' => $id])->asArray()->all();
        $cityIds = ArrayHelper::getColumn($cities, 'id');

        return Ads::find()->where(['city_id' => $cityIds]);
    }

    /**
     * Счетчик просмотров поста с записью id в сессию
     * данный подход исключает накрутку просмотров за сессию
     * @return bool
     */
    public function processCountViewPost()
    {
        $session = Yii::$app->session;
        // Если в сессии отсутствуют данные,
        // создаём, увеличиваем счетчик, и записываем в бд
        if (!isset($session['ad_post_view'])) {
            $session->set('ad_post_view', [$this->id]);
            $this->updateCounters(['count_view' => 1]);
            // Если в сессии уже есть данные то проверяем засчитывался ли данный пост
            // если нет то увеличиваем счетчик, записываем в бд и сохраняем в сессию просмотр этого поста
        } else {
            if (!ArrayHelper::isIn($this->id, $session['ad_post_view'])) {
                $array = $session['ad_post_view'];
                array_push($array, $this->id);
                $session->remove('ad_post_view');
                $session->set('ad_post_view', $array);
                $this->updateCounters(['count_view' => 1]);
            }
        }
        return true;
    }

}
