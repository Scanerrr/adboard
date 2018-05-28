<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 6/15/2018
 * Time: 18:41
 */

namespace common\widgets;


use yii\helpers\Html;
use yii\widgets\LinkSorter;

class CustomLinkSorter extends LinkSorter
{
    protected function renderSortLinks()
    {
        $attributes = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links = [];
        foreach ($attributes as $name) {
            $links[] = $this->sort->link($name, $this->linkOptions);
        }

        return Html::ul($links, array_merge($this->options, [
            'encode' => false, 'class' => 'text-right list-unstyled list-inline'
        ]));
    }
}