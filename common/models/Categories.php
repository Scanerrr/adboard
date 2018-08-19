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
 * @property string $image
 *
 * @property Ads[] $ads
 * @property Categories $parent
 * @property Categories[] $categories
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
//            [['parent_id'], 'default', 'value'=> 0],
            [['parent_id'], 'integer'],
            [['name', 'image', 'slug'], 'string', 'max' => 128],
            [['name', 'slug'], 'unique'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg',
                'maxSize' => 5 * 1024 * 1024, 'maxFiles' => 1,
                'uploadRequired' => 'Выберите файл для загрузки',
                'wrongExtension' => 'Поддерживаются файлы следующих разширень: *.png, *.jpg, *.jpeg'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
    public function getParent()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAds()
    {
        return $this->hasMany(Ads::className(), ['category_id' => 'id']);
    }

    /**
     * @param int $parent_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getCategoriesAsArray($parent_id = null)
    {
        return Yii::$app->cache->getOrSet('categories-as-array-' . $parent_id, function () use ($parent_id) {
            return self::find()->select(['id', 'name', 'slug', 'image', 'parent_id'])
                ->where(['parent_id' => $parent_id])->orderBy('name')->asArray()->all();
        }, 300);
    }

    public function getImageUrl()
    {
        $url = isset(Yii::$app->urlManagerFrontend) ? Yii::$app->urlManagerFrontend->BaseUrl : Yii::$app->request->BaseUrl;
        return $this->image ? $url . DIRECTORY_SEPARATOR . $this->image : '';
    }

    public function upload()
    {
        if ($this->validate('imageFile')) {
            $dir = '/img/categories/';

            $directory = Yii::getAlias('@root/' . $dir);
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
