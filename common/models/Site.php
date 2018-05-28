<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/20/2018
 * Time: 22:01
 */

namespace common\models;


use pheme\settings\models\BaseSetting;
//use yii\base\Model;

class Site extends BaseSetting
{
    public $siteName, $siteDescription, $seoText;
    public function rules()
    {
        return [
            [['siteName', 'siteDescription', 'seoText'], 'string'],
        ];
    }

    public function fields()
    {
        return ['siteName', 'siteDescription', 'seoText'];
    }

    public function attributes()
    {
        return ['siteName', 'siteDescription', 'seoText'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'siteName' => 'Название сайта',
            'siteDescription' => 'Описание сайта',
            'seoText' => 'Описание на главной',
        ];
    }

}