<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/13/2018
 * Time: 19:10
 */

namespace common\models;


use yii\base\Model;

class AdsSearch extends Model
{
    public $ad;
    public $region;
    public function rules()
    {
        return [
            [['ad', ['region']], 'string']
        ];
    }

}