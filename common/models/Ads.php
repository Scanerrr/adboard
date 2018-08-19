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
 * @property int $price_type
 * @property int $currency_id
 * @property int $state
 * @property string $vendor
 * @property string $model
 * @property int $shipping_id
 * @property int $payment_id
 * @property string $description
 * @property string $image
 * @property int $status
 * @property int $count_view
 * @property string $contact_name
 * @property string $contact_email
 * @property int $updated_at
 * @property int $created_at
 *
 * @property Currency $currency
 * @property Payment $payment
 * @property Shipping $shipping
 * @property User $user0
 * @property Categories $category0
 * @property City $city0
 * @property AdsImages[] $adsImages0
 * @property AdsPhones[] $adsPhones
 * @property Favorite[] $favorites
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

    const STATE_NEW = 1;
    const STATE_OLD = 2;
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
            [['title', 'category_id', 'city_id', 'price'], 'required'],
            [['category_id', 'city_id', 'user_id', 'status', 'count_view'], 'integer'],
            [['description'], 'string'],
            [['updated_at', 'created_at'], 'default', 'value' => time()],

            [['price'], 'number', 'min' => 0],

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
            ['state', 'in', 'range' => [self::STATE_NEW, self::STATE_OLD]],
            [['title', 'description'], 'filter', 'filter' => function($value) {
                return strip_tags(HTMLPurifier::process($value));
            }],

            [['title', 'category_id', 'city_id', 'user_id', 'price', 'currency_id', 'state', 'vendor', 'model', 'shipping_id', 'payment_id', 'contact_name', 'contact_email', 'updated_at', 'created_at'], 'required'],
            [['category_id', 'city_id', 'user_id', 'price_type', 'currency_id', 'state', 'shipping_id', 'payment_id', 'status', 'count_view', 'updated_at', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['title', 'model'], 'string', 'max' => 70],
            [['image'], 'string', 'max' => 255],
            [['vendor'], 'string', 'max' => 64],
            [['contact_name', 'contact_email'], 'string', 'max' => 128],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['payment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_id' => 'id']],
            [['shipping_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shipping::className(), 'targetAttribute' => ['shipping_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],

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
            'price_type' => 'Price Type',
            'currency_id' => 'Currency ID',
            'state' => 'State',
            'vendor' => 'Vendor',
            'model' => 'Model',
            'shipping_id' => 'Shipping ID',
            'payment_id' => 'Payment ID',
            'contact_name' => 'Contact Name',
            'contact_email' => 'Contact Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShipping()
    {
        return $this->hasOne(Shipping::className(), ['id' => 'shipping_id']);
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
    public function getAdsImages()
    {
        return $this->hasMany(AdsImages::className(), ['ad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdsPhones()
    {
        return $this->hasMany(AdsPhones::className(), ['ad_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::className(), ['ad_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AdsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AdsQuery(get_called_class());
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
