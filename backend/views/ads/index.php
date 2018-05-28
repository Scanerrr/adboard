<?php

use common\widgets\CustomLinkSorter;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AdsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Объявления';
?>
<div class="ads-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
<!--    --><?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php /*<p>
        <?= Html::a('Создать объявление', ['create'], ['class' => 'btn btn-success']) ?>
    </p> */ ?>
    <ul class="listview_sort text-right list-unstyled list-inline">
        <li><?= Html::a('Самые новые', ['/ads/index?sort=-updated_at'], ['data' => ['sort' => '-updated_at']]) ?></li>
        <li><?= Html::a('Самые дешевые', ['/ads/index?sort=price'], ['data' => ['sort' => 'price']]) ?></li>
        <li><?= Html::a('Самые дорогие', ['/ads/index?sort=-price'], ['data' => ['sort' => '"-price']]) ?></li>
    </ul>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '_all',

    ]) ?>
    <?php Pjax::end(); ?>
</div>

<script>
    const getURLParameter = sParam => {
        const sPageURL = window.location.search.substring(1);
        const sURLVariables = sPageURL.split('&');
        for (let i = 0; i < sURLVariables.length; i++) {
            const sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return sParameterName[1];
            }
        }
    };
    window.addEventListener('DOMContentLoaded', () => {
        const sortGetParam = getURLParameter('sort');
        const parent = $('.listview_sort').find('[data-sort="' + sortGetParam + '"]').parent();
        if (sortGetParam) parent.toggleClass('active');
        $(document).on('pjax:success', () => {
            const sortGetParam = getURLParameter('sort');
            if (sortGetParam) $('.listview_sort').find('[data-sort="' + sortGetParam + '"]').parent().toggleClass('active');
        });
    });
</script>
