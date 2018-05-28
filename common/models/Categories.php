<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $parent_id
 *
 * @property Ads[] $ads
 */
class Categories extends \yii\db\ActiveRecord
{

    public $imageFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'default', 'value'=> 0],
            [['parent_id'], 'integer'],
            [['name', 'image', 'slug'], 'string', 'max' => 255],
            [['name', 'slug'], 'unique'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg',
                'maxSize'=> 5*1024*1024, 'maxFiles' => 1,
                'uploadRequired' => 'Выберите файл для загрузки',
                'wrongExtension' => 'Поддерживаются файлы следующих разширень: *.png, *.jpg, *.jpeg'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'immutable' => true,
                'ensureUnique' => true,
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
            'name' => 'Название',
            'slug' => 'Слаг',
            'image' => 'Изображение категории',
            'parent_id' => 'Родительская категория',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ads::className(), ['category_id' => 'id']);
    }

    public static function getCategories($parent_id = 0)
    {
       return Categories::find()->select(['id', 'name', 'slug' ,'image', 'parent_id'])
           ->where(['parent_id' => $parent_id])->asArray()->all();
    }

    public function getImageUrl()
    {
        $url =  isset(Yii::$app->urlManagerFrontend) ? Yii::$app->urlManagerFrontend->BaseUrl : Yii::$app->request->BaseUrl;
        return $this->image ? $url . DIRECTORY_SEPARATOR . $this->image : '';
    }

    public function upload()
    {
        if ($this->validate('imageFile')) {
            $dir = '/img/categories/';

            $directory = Yii::getAlias('@root/'.$dir);
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }

            $uid = uniqid(time(), true);
            $fileName = $uid . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs(FileHelper::normalizePath($directory . $fileName));
            return FileHelper::normalizePath($dir . $fileName);
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        //Delete images location
        try {
            @unlink(Yii::getAlias('@root/' . $this->image));
        } catch (\Exception $e) {

        }
    }

    public function getCategoryName($id)
    {
        return Categories::findOne($id) ? Categories::findOne($id)->name : '';
    }

}
