<?php

use common\models\Categories;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Ads */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ads-form custom-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-5">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                <!--                TODO: allow edit category -->
                <?php if ($isUpdate): ?>
                    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(Categories::find()->all(), 'id', 'name'),
                        'options' => [
                            'placeholder' => 'Выбрать категорию',
                            'value' => $model->category_id
                        ],
                    ]) ?>
                <?php else: ?>
                    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
                        'data' => $categories,
                        'options' => [
                            'placeholder' => 'Выбрать категорию',
                            'id' => 'parent_category_id',
                            'value' => $model->category_id
                        ],
                    ]) ?>
                    <?= DepDrop::widget([
                        'name' => 'subcategory_id',
                        'data' => ['id' => $model->category_id, 'name' => $categories[$model->category_id]],
                        'options'=>['id'=>'category_id'],
                        'type' => DepDrop::TYPE_SELECT2,
                        'pluginOptions'=>[
                            'depends'=>['parent_category_id'],
                            'placeholder'=>'Выбрать подкатегорию',
                            'url'=>Url::to(['/ads/subcat'])
                        ]
                    ]);
                    ?>
                <?php endif; ?>
                <?= $form->field($model, 'city_id')->widget(Select2::className(), [
                    'data' => $cities,
                    'options' => [
                        'placeholder' => 'Выбрать город',
                        'value' => $model->city_id,

                    ],
                ]) ?>
                <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                <?= $form->field($userPhones, 'phone')->widget(MultipleInput::className(), [
                    'rendererClass' => \common\widgets\CustomMultipleRenderer::className(),
                    'max'               => 3,
                    'min'               => 1, // should be at least 1 rows
                    'allowEmptyList'    => false,
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
                    'data' => $isUpdate ? explode(',', $model->phone) : $phones,
                    'columns' => [
                        [
                            'name'  => 'phone',
                            'type' => \yii\widgets\MaskedInput::className(),
                            'options' => [
                                'class' => 'input-phone',
                                'mask' => '380999999999',
                            ],
                        ],
                    ],
                ])->hint('Номер должен быть в международном формате 380XXXXXXXXX')->label('Номер телефона') ?>

                <?= $form->field($model, 'status')->widget(Select2::className(), [
                    'data' => $statuses,
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => 'Выбрать статус объявления',
                        'value' => $model->status,

                    ],
                ]) ?>
            </div>
            <div class="col-sm-5 col-sm-offset-2">
                <div class="row">
                    <label class="control-label">Выбрать изображения</label>

                    <!-- image 1 -->
                    <?= $form->field($model, 'imageFile', [
                        'labelOptions' => [ 'class' => 'custom-file-upload' ]
                    ])->fileInput(['accept' => 'image/*','id'=>'main_image'])
                        ->label($isUpdate ?
                            Html::img(Yii::$app->urlManagerFrontend->createAbsoluteUrl($model->imageUrl)) :
                            '<i class="fa fa-plus-circle"></i>'); ?>
                    <?= Html::button('Добавить еще изображение', ['class' => 'btn btn-block btn-default add_image']) ?>
                </div>
                <div class="row">
                    <!--                    TODO: edit images -->

                    <div class="additional_images" style="<?php $isUpdate ? '' : 'display: none' ?>">
                        <!-- output 6 more images -->
                        <?php $i = 1; foreach (\common\models\AdsImages::find()->where(['ad_id' => $model->id])->all() as $img): ?>
                            <!-- image <?= $i ?> -->
                            <?= $form->field($adsImages, 'imageFiles['.$i.']', [
                                'labelOptions' => [ 'class' => 'custom-file-upload' ]
                            ])->fileInput(['accept' => 'image/*','class'=>'additional_image'])->label(
                                Html::img(Yii::$app->urlManagerFrontend->createAbsoluteUrl($img->getImageUrl()))); ?>
                        <?php $i++; endforeach; ?>
                        <?php for (; $i<7; $i++): ?>
                            <!-- image <?= $i ?> -->
                            <?= $form->field($adsImages, 'imageFiles['.$i.']', [
                                'labelOptions' => [ 'class' => 'custom-file-upload' ]
                            ])->fileInput(['accept' => 'image/*','class'=>'additional_image'])
                                ->label('<i class="fa fa-plus-circle"></i>'); ?>
                        <?php endfor; ?>
                    </div>


                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?= $form->field($model, 'description')->textarea(['rows'=>7]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>