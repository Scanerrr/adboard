<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/20/2018
 * Time: 22:01
 */

namespace common\models;


use yii\base\Model;

class Site extends Model
{
    public $siteName, $siteDescription;
    public function rules()
    {
        return [
            [['siteName', 'siteDescription'], 'string'],
        ];
    }

    public function fields()
    {
        return ['siteName', 'siteDescription'];
    }

    public function attributes()
    {
        return ['siteName', 'siteDescription'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'siteName' => 'Название сайта',
            'siteDescription' => 'Описание сайта',
        ];
    }

}