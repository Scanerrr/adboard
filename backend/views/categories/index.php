<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\CategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
?>
<div class="categories-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            [
//                'class' => 'yii\grid\CheckboxColumn',
//                // you may configure additional properties here
//            ],
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
//                'label' => 'Balance',
                'attribute' => 'name',
                'value' => function ($model) {
                    if ($model->parent_id != 0) $name = $model->getCategoryName($model->parent_id).' > '.$model->name;
                    else $name = $model->name;
                    return Html::a(Html::encode($name), Url::to(['update', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],
//            'name',
//            'slug',
//            'parent_id',
//            'image',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
