<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Главная';
?>

<div class="site-index">

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Новые объявления</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= \common\widgets\GridListView::widget([
                    'dataProvider' => $dp,
                    'itemView' => '_main_grid',
                    'summary'=>'',
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="text-right">
                    <?= Html::a('Посмотреть все', Url::to(['ads/all']), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1>Seo text</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto aspernatur deleniti error exercitationem inventore itaque maiores modi nesciunt quis repellat, repellendus unde voluptatem? Ad, aperiam beatae corporis explicabo in laboriosam nulla provident quisquam sapiente sint. Accusamus adipisci aspernatur beatae culpa cum distinctio dolore doloremque eaque est expedita facere facilis hic id incidunt nisi nostrum obcaecati omnis, pariatur quibusdam reiciendis rem, repellat reprehenderit, saepe temporibus totam unde veritatis voluptates! At corporis dignissimos dolorum error exercitationem, illum ipsa iure magni nam natus nobis non perferendis qui velit, vitae. Error et eveniet laboriosam natus, rem tenetur. Ab accusantium atque commodi eius et odio officiis possimus quaerat qui quisquam? Accusantium animi culpa eveniet laborum modi quia repellendus repudiandae, sequi! Accusamus aperiam, dolor dolores doloribus facilis in laudantium minima obcaecati officiis omnis quasi reiciendis rem! Aspernatur aut beatae, consequuntur dicta dolore, doloremque earum eligendi enim eveniet ex exercitationem facere fugit itaque iure minus molestiae natus nesciunt nostrum quae recusandae temporibus, voluptatem voluptatum? Amet architecto placeat repellat sapiente! Aliquid amet debitis dignissimos esse fuga ipsam molestiae voluptate. A commodi corporis delectus ea esse impedit maxime natus, necessitatibus nesciunt non officiis, perferendis, placeat quidem reprehenderit sequi sint voluptatibus! Aliquam aperiam asperiores commodi eaque excepturi magni odio pariatur perspiciatis reprehenderit, sint sunt totam voluptate! Ex excepturi exercitationem facere illo laborum nisi tenetur. Dolorem ex incidunt ipsa iure possimus sed totam voluptatem. Accusantium amet ea eius ipsam iste modi perferendis praesentium provident, repellat similique. Accusantium ad adipisci, aperiam aspernatur blanditiis debitis delectus deserunt dicta dolores eligendi eum explicabo iusto minus nostrum perspiciatis quibusdam unde veniam voluptas voluptate voluptatem! Aliquam assumenda beatae culpa cum cupiditate dignissimos dolore eaque earum, ex hic inventore ipsum mollitia nesciunt nostrum obcaecati provident quae quaerat quos reprehenderit sed totam ut veritatis vero? Consequuntur earum enim in magnam natus quibusdam, sed. Alias amet cupiditate dignissimos dolores, ea fugiat laudantium obcaecati pariatur possimus repellat rerum sapiente temporibus totam veniam veritatis. Alias asperiores aspernatur debitis est et fugit odit pariatur sapiente soluta unde? Ab cumque delectus distinctio eius enim et eveniet harum iste itaque magni, non quam, quia quis quisquam reprehenderit sunt vel. Ad alias autem distinctio eligendi enim facere fugit labore magnam mollitia neque non nulla obcaecati quae quibusdam, quidem quis quisquam rem repellendus sapiente sint ullam ut, velit? Amet architecto cum, facilis optio quidem repellat suscipit! Accusamus adipisci alias at consequuntur doloribus dolorum harum ipsa minima molestias officiis pariatur possimus quos repellat saepe sapiente suscipit, voluptatum.</p>
            </div>
        </div>
    </div>
</div>
