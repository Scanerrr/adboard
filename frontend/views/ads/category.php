<?php
/* @var $this yii\web\View */

use common\widgets\CustomLinkSorter;
use yii\widgets\Pjax;

$slug = $parentSlug ? $parentSlug . ' <i class="fa fa-arrow-right"></i> ' . $slug : $slug;
$this->title = $slug;
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1><?= $this->title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php Pjax::begin(); ?>
            <ul class="listview_sort text-right list-unstyled list-inline">
                <li><a href="/ads/category/<?= Yii::$app->session->get('category_slug') ?>?sort=-updated_at" data-sort="-updated_at">Самые новые</a></li>
                <li><a href="/ads/category/<?= Yii::$app->session->get('category_slug') ?>?sort=price" data-sort="price">Самые дешевые</a></li>
                <li><a href="/ads/category/<?= Yii::$app->session->get('category_slug') ?>?sort=-price" data-sort="-price">Самые дорогие</a></li>
            </ul>
            <?= \yii\widgets\ListView::widget([
                'dataProvider' => $dp,
                'itemView' => '_all',
                'summary'=>'',
                'emptyText' => 'Сейчас на сайте нет товаров или услуг, соответствующих Вашему запросу. Попробуйте изменить параметры поиска',
            ]) ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
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
