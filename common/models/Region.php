<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $name_ru
 * @property string $name_uk
 * @property string $url
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property City[] $cities
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_uk'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_uk'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 50],
            [['url'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name Ru',
            'name_uk' => 'Name Uk',
            'url' => 'Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['region_id' => 'id']);
    }
}
