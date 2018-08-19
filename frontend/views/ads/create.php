<?php /** @noinspection PhpUnhandledExceptionInspection */

use common\models\AdsPhones;
use common\models\Currency;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ads */
/* @var $form ActiveForm */
/* @var $categories array */
/* @var $cities array */

$this->title = 'Подать объявление';
?>
<div class="ads-form custom-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-5">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'parent_category_id')->widget(Select2::classname(), [
                    'data' => $categories,
                    'options' => [
                        'placeholder' => 'Выбрать категорию',
                        'id' => 'parent_category_id'
                    ],
                ]) ?>

                <?= $form->field($model, 'category_id')->widget(DepDrop::classname(), [
                    'options' => ['id' => 'category_id'],
                    'type' => DepDrop::TYPE_SELECT2,
                    'pluginOptions' => [
                        'depends' => ['parent_category_id'],
                        'placeholder' => 'Выбрать подкатегорию',
                        'url' => Url::to(['/ads/subcat'])
                    ]
                ]) ?>

                <?= $form->field($model, 'city_id')->widget(Select2::className(), [
                    'data' => $cities,
                    'options' => [
                        'placeholder' => 'Выбрать город',
                        'value' => $user->city_id,

                    ],
                ]) ?>
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'currency_id')
                            ->dropDownList(Currency::find()->select('description')->indexBy('id')->column())
                            ->label(false) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'price_type')
                            ->checkbox(['value' => 1, 'label' => 'Договорная']) ?>
                    </div>
                </div>

                <label class="control-label">Телефон</label>
                <div class="form-inline">
                    <div class="phone-list">

                        <div class="input-group phone-input">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                                        <span class="type-text">Выбрать тип</span>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a class="changeType" href="javascript:;" data-type-value="1"><i
                                                        class="fab fa-viber"></i> Viber</a></li>
                                        <li><a class="changeType" href="javascript:;" data-type-value="2"><i
                                                        class="fab fa-telegram"></i> Telegram</a></li>
                                        <li><a class="changeType" href="javascript:;" data-type-value="3"><i
                                                        class="fab fa-whatsapp"></i> Whatsapp</a></li>
                                    </ul>
                                </span>
                            <?= $form->field($adsPhones, 'type[0]')
                                ->hiddenInput(['class' => 'type-input'])->label(false) ?>

                            <?= $form->field($adsPhones, 'phone[0]', [
                                'options' => ['style' => 'display: inherit'],
                                'template' => '{input}'
                            ])
                                ->textInput(['style' => 'margin-top: 5px', 'placeholder' => '380999999999', 'mask' => '380999999999'])->label(false) ?>
                        </div>

                    </div>


                    <button type="button" class="btn btn-primary btn-sm btn-add-phone">
                        <span class="glyphicon glyphicon-plus"></span> Добавить телефон
                    </button>
                    <div class="col-sm-1">
                        <div class="phone-type-icon"></div>
                    </div>

                </div>

                <? /*= $form->field($adsPhones, 'phone')->widget(MultipleInput::className(), [
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
                    'data' => [0 => ['phone' => '380507380495', 'phone_type' => 2]],
                    'columns' => [
                        [
                            'name' => 'phone',
                            'type' => \yii\widgets\MaskedInput::className(),
                            'options' => [
                                'class' => 'input-phone',
                                'mask' => '380999999999',
                            ],
                        ],
                        [
                            'name' => 'phone_type',
                            'type' => 'dropDownList',
                            'options' => [
                                'class' => 'select-phone_type',
                            ],
                            'defaultValue' => 1,
                            'items' => [
                                AdsPhones::VIBER => 'VIBER',
                                AdsPhones::TELEGRAM => 'TELEGRAM',
                                AdsPhones::WHATSAPP => 'WHATSAPP'
                            ]
                        ],
                    ],
                ])->hint('Номер должен быть в международном формате 380XXXXXXXXX')->label('Номер телефона') */ ?>

            </div>
            <div class="col-sm-5 col-sm-offset-2">
                <div class="row gallery-block additional_images">

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
                                        <?php for ($i = 0; $i <= 7; $i++): ?>
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
                        <!--                                        <input type="file" id="-->
                        <? //= $i ?><!--" name="filesdef[--><? //= $i ?><!--]" value=""-->
                        <!--                                               accept="image/jpeg,image/png,image/jpg">-->
                        <!--                                        <button type="button" id="fileupload-->
                        <? //= $i ?><!--" class="delete"-->
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
                <?= $form->field($model, 'description')->textarea(['rows' => 7]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
