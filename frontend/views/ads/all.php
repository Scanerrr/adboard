<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/13/2018
 * Time: 23:32
 */

use yii\helpers\Html;

$this->title = 'Все объявления';
?>

<div class="top-categories">
    <div class="container">
        <div class="row">
            <div class="main-categories">
                <?php foreach ($categories as $category): ?>
                    <div class="col-sm-3">
                        <div class="main-category">
                            <?php $image = $category['image'] ? $category['image'] : '/img/categories/default.png'; ?>

                            <?= Html::img($image) ?>
                            <?= Html::a($category['name'], '#', [
                                'class' => 'get-subcategories main-subcategory-caption',
                                'data-category_id' => $category['id'],
                                'data-category_slug' => $category['slug']
                            ]) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1><?= $this->title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= \yii\widgets\ListView::widget([
                'dataProvider' => $dp,
                'itemView' => '_all',
                'summary'=>'',
//                'emptyText' => 'Список объявлений пуст',
            ]) ?>
        </div>
    </div>
</div>