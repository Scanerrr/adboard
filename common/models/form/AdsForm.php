<?php
namespace common\models\form;

use common\models\Categories;
use common\models\City;
use Yii;
use yii\base\Model;
use yii\web\Session;
use yii\web\UploadedFile;

/**
 * Login form
 */
class AdsForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public $title;
    public $price;
    public $price_type;
    public $state;
    public $vendor;
    public $model;
    public $description;

    public $category_id;
    public $parent_category_id;
    public $city_id;
    public $user_id;
    public $shipping_id;
    public $payment_id;
    public $currency_id;

    public $contact_name;
    public $contact_email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg',
                'maxSize'=> 5*1024*1024, 'maxFiles' => 8,
                'uploadRequired' => 'Выберите файл для загрузки',
                'wrongExtension' => 'Поддерживаются файлы следующих разширень: *.png, *.jpg, *.jpeg'],

            [['title', 'category_id', 'parent_category_id', 'city_id', 'user_id', 'price', 'currency_id', 'state', 'vendor', 'model', 'shipping_id', 'payment_id', 'contact_name', 'contact_email', 'updated_at', 'created_at'], 'required'],
            [['category_id', 'parent_category_id', 'city_id', 'user_id', 'price_type', 'currency_id', 'state', 'shipping_id', 'payment_id', 'status', 'count_view', 'updated_at', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number', 'min' => 0],
            [['title', 'model'], 'string', 'max' => 70],
            [['image'], 'string', 'max' => 255],
            [['vendor'], 'string', 'max' => 64],
            [['contact_name', 'contact_email'], 'string', 'max' => 128],
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
            'category_id' => 'Выбрать подкатегорию',
            'parent_category_id' => 'Выбрать категорию',
            'city_id' => 'Ваш город',
            'user_id' => 'Пользователь',
            'price' => 'Цена',
            'description' => 'Описание',
            'image' => 'Главное изображение',
            'imageFile' => 'Фотографии',
            'phone' => 'Номер телефона',
            'price_type' => 'Price Type',
            'currency_id' => 'Currency ID',
            'state' => 'Состояние',
            'vendor' => 'Артикул',
            'Модель' => 'Model',
            'shipping_id' => 'Способ доставки',
            'Способ оплаты' => 'Payment ID',
            'Контактное лицо' => 'Contact Name',
            'Ваш  email' => 'Contact Email',
        ];
    }

}
