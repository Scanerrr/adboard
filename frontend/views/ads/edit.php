<?php

use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $ad common\models\Ads */
/* @var $form ActiveForm */
$this->title = 'Редактировать объявление';
?>
<div class="ads-edit ads-form custom-form message-form">

    <div class="row">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-5">
                    <?= $form->field($ad, 'title') ?>


                    <?= $form->field($ad, 'category_id')->widget(Select2::className(), [
                        'language' => 'ru',
                        'data' => $categories,
                        'options' => [
                            'placeholder' => 'Выбрать город',
                            'value' => $ad->category_id
                        ],
                    ]) ?>

                    <?= $form->field($ad, 'city_id')->widget(Select2::className(), [
                        'language' => 'ru',
                        'data' => $cities,
                        'options' => [
                            'placeholder' => 'Выбрать город',
                        ],
                    ]) ?>
                    <?= $form->field($ad, 'price') ?>
                    <?= $form->field($userPhones, 'phone')->widget(MultipleInput::className(), [
                        'rendererClass' => \common\widgets\CustomMultipleRenderer::className(),
                        'max' => 3,
                        'min' => 1, // should be at least 1 rows
                        'allowEmptyList' => false,
                        'addButtonPosition' => MultipleInput::POS_FOOTER,
                        'addButtonOptions' => [
                            'label' => 'Добавить еще один',
                            'class' => 'btn multiple-input-list__btn btn-block js-input-plus btn-default'
                        ],
                        'enableError' => true,
                        'attributeOptions' => [
                            //'enableAjaxValidation' => true,
                            'enableClientValidation' => true,
                            'validateOnChange' => true,
                            'validateOnSubmit' => true,
                            'validateOnBlur' => false,
                        ],
                        'data' => explode(',', $ad->phone),
                        'columns' => [
                            [
                                'name' => 'phone',
                                'type' => \yii\widgets\MaskedInput::className(),
                                'options' => [
                                    'class' => 'input-phone',
                                    'mask' => '380999999999',
                                ],
                            ],
                        ],
                    ])->hint('Номер должен быть в международном формате 380XXXXXXXXX')->label('Номер телефона') ?>
                </div>

                <!--                IMAGES BLOCK -->
                <?php $images = \common\models\AdsImages::find()->where(['ad_id' => $ad->id])->all() ?>
                <div class="col-sm-5 col-sm-offset-2">
                    <div class="row">
                        <label class="control-label">Выбрать изображения</label>

                        <!-- image 1 -->
                        <?= $form->field($ad, 'imageFile', [
                            'labelOptions' => ['class' => 'custom-file-upload']
                        ])->fileInput(['accept' => 'image/*', 'id' => 'main_image'])
                            ->label($ad->imageUrl ?
                                Html::img($ad->imageUrl) :
                                '<i class="fa fa-plus-circle"></i>'); ?>
                        <?= Html::button('Добавить еще изображение', ['class' => 'btn btn-block btn-default add_image', 'disabled' => !$images ? false : true]) ?>
                    </div>

                    <div class="row gallery-block additional_images" style="<?= !$images ? 'display: none' : '' ?>">

                        <!--  start: Images Gallery Upload Widget  -->
                        <div id="galleryPlaceArea" class="col-sm-12 form-field gallery-place">
                            <div id="photosArea" class="photos">
                                <div id="fileupload" data-action="https://besplatka.ua/attachment/upload">
                                    <div class="fileupload-buttonbar">
                                        <div class="col-sm-12">
                                            <span class="fileinput-button modal-title button hidden" id="btnLoadPhotos">
                                                <input type="file" name="files_btn[]" multiple="" id="fileUpload"
                                                       accept="image/jpeg,image/png,image/jpg">
                                            </span>
                                        </div>
                                    </div>
                                    <div id="fileUploadGallery" class="gallery-wrap">
                                        <div id="sortable" class="gallery-sortable files clearfix ui-sortable">
                                            <!--  check if images isset -->
                                            <?php $i = 0; foreach ($images as $img): ?>
                                                <div class="photo-preview ui-state-default in ui-sortable-handle" data-id="<?= $img->id ?>" data-position="<?= $i ?>">
                                                    <div class="photo-container">
                                                        <a>
                                                            <?= Html::img($img->getImageUrl(), ['data' => ['id' => $img->id]]) ?>
                                                        </a>
                                                    </div>
                                                    <input type="hidden" name="files[]" value="<?= $img->getImageUrl() ?>">
                                                    <a rel="nofollow" role="button" class="btn-delete delete" data-name="<?= $img->getImageUrl() ?>"></a>
                                                </div>
                                            <?php $i++; endforeach; ?>
                                            <?php for (; $i <= 7; $i++): ?>
                                                <div class="photo-preview ui-state-default ui-sortable-handle">
                                                    <span class="fa-stack fa-2x">
                                                        <i class="fa fa-camera fa-stack-2x"></i>
                                                        <i class="fa fa-circle fa-stack-1x"></i>
                                                        <i class="fa fa-plus fa-stack-1x"></i>
                                                    </span>

                                                </div>
                                            <?php endfor; ?>
                                        </div>

                                        <label for="sortable" class="error-gallery" style="display: none;">Дождитесь
                                            окончания загрузки файлов</label>

                                        <div class="tooltip">
                                            <div class="tooltip-help">
                                                Для загрузки более одной фотографии - удерживайте Ctrl при выборе
                                                фото.<br>
                                                Максимальный размер файла - 5 Мб, файлы формата .jpg .jpeg .gif .png
                                            </div>
                                        </div>
                                    </div>
                                    <div class="errors">
                                        <span id="error_1">Максимальный размер файла - 5 Мб</span>
                                        <span id="error_2">Изображение в неподдерживаемом формате. Допустимые форматы -  JPEG, JPG, GIF, PNG</span>
                                        <span id="error_3">Превышен лимит количества изображений</span>
                                        <span id="coverText">сделать главным</span>
                                    </div>
                                </div>
                            </div>

<!--                            <div id="photosDefault" class="photosdef" style="display: none">-->
<!--                                --><?php //for ($i = 0; $i <= 7; $i++): ?>
<!--                                    <div class="col-xs-12 col-sm-11 col-md-6">-->
<!--                                        <input type="file" id="--><?//= $i ?><!--" name="filesdef[--><?//= $i ?><!--]" value=""-->
<!--                                               accept="image/jpeg,image/png,image/jpg">-->
<!--                                        <button type="button" id="fileupload--><?//= $i ?><!--" class="delete"-->
<!--                                                style="display: none;"></button>-->
<!--                                    </div>-->
<!---->
<!--                                --><?php //endfor; ?>
<!---->
<!--                            </div>-->
<!--                            <div class="tooltip-gallery-bottom">Если у вас проблемы при загрузке фотографий,-->
<!--                                воспользуйтесь <a id="show-gallery-html">альтернативной формой</a></div>-->
                        </div>
                        <div class="hidden-xs col-sm-1 col-md-2"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($ad, 'description')->textarea(['rows' => 7]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div><!-- ads-edit -->
