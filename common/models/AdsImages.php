<?php

namespace common\models;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;


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
     * @var UploadedFile[]
     */
    public $imageFiles;
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
            [['ad_id'], 'required'],
            [['ad_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
            [['ad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ads::className(), 'targetAttribute' => ['ad_id' => 'id']],
            [['image', 'imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg',
                'maxSize'=> 5*1024*1024, 'maxFiles' => 7,
                'uploadRequired' => 'Выберите файл для загрузки',
                'wrongExtension' => 'Поддерживаются файлы следующих разширень: *.png, *.jpg, *.jpeg', 'on' => 'update-photo-upload'],
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
    public function getImageUrl()
    {
        $url =  isset(Yii::$app->urlManagerFrontend) ? Yii::$app->urlManagerFrontend->BaseUrl : Yii::$app->request->BaseUrl;
        return $this->image ? $url . DIRECTORY_SEPARATOR . $this->image : '';
    }

    public function upload()
    {
        if ($this->validate('imageFiles')) {
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
            $pathes = [];
            foreach ($this->imageFiles as $file) {
                $fileName = $uid . $file->name;
                if ($file->saveAs($directory . $fileName)) $pathes[] = $dir . $subdir . $fileName;
            }
            return $pathes;
        } else {
            return false;
        }
    }

//    TODO: delete images

}
