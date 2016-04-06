<?php
/* @var $this EventController */
/* @var $model Event */
/* @var $form TbActiveForm */
?>


<?php if($model->isNewRecord): ?>
    <header class="header b-b bg-dark">
        <div class="row">
            <div class="col-xs-12">
                <h4 class="m-t m-b">Створення події</h4>
            </div>
        </div>
    </header>
<?php else: $this->widget('application.widgets.eventWidget.EventWidget');
endif; ?>
<div class="wrapper">
    <?php
    if(!isset($preview))
        $preview = 0;
    if(!isset($images))
        $images = null;
    if(!isset($files))
        $files = null;

    if (!$model->isNewRecord)
        echo CHtml::hiddenField("event_id", $model->id);

    $form = $this->beginWidget('application.components.CustomActiveForm',array(
        'id'=>'new-event-form',
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data'
        ),
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>true
        )
    ));
    $this->widget(
        'booster.widgets.TbTabs',
        array(
            'type' => 'tabs',
            'tabs' => array(
                array(
                    'label' => 'Загальна інформація',
                    'content' => $this->renderPartial("_main", array( 'model' => $model,
                        'preview' => $preview,
                        'countries' => $countries,
                        'images' => $images,
                        'files' => $files,
                        'form' => $form), true),
                    'active' => true
                ),
                array('label' => 'Вигляд квитків', 'content' => $this->renderPartial("_ticketcss", array('model' => $model, 'form' => $form), true)),
                array('label' => 'SEO параметри', 'content' => $this->renderPartial("_seo", array('model' => $model, 'form' => $form), true)),
                array('label' => 'Технічні параметри', 'content' => $this->renderPartial("_techParams", array('model' => $model, 'form' => $form), true))
            ),
        )
    );
    $this->endWidget();
    $form = $this->beginWidget('application.components.CustomActiveForm',array(
        'id'=>'changeMultimediaFile',
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data',
            'style'=>'position: absolute; top: -3000px; display: none'
        ),
        'enableAjaxValidation'=>false,
        'clientOptions'=>array(
            'validateOnSubmit'=>false,
            'validateOnChange'=>false
        )
    ));

    echo CHtml::fileField('changeMultimedia',"");
    echo CHtml::textField('id_event',$model->id);
    echo CHtml::textField('prevMultimediaName','');

    $this->endWidget();

    ?>
</div>