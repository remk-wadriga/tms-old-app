<?php /**
 * @var $data Comment
 */
?>
<div class="comment" data-id="<?php echo $data->id;?>">
    <div class="row">
        <div class="col-lg-6 text-left">
            <?php echo Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm:ss",$data->dateadd); ?>
        </div>
        <div class="col-lg-6 text-right">
            <div class="user-name">
                <?php echo $data->user->username; ?>
            </div>

            <?php if (Yii::app()->user->checkAccess('admin')): ?>
                <span class="glyphicon glyphicon-trash deleteComment"></span>
            <?php endif; ?>
        </div>
    </div>
    <hr/>
    <p class="comment_text">
        <?php
        if (Yii::app()->user->checkAccess('admin')) {
            $this->widget(
                'booster.widgets.TbEditableField',
                array(
                    'type' => 'textarea',
                    'model' => $data,
                    'attribute' => 'text', // $model->name will be editable
                    'url' => Yii::app()->createUrl("/comment/update"), //url for submit data
                    'title'=>'Введіть текст',
                    'validate' => 'js: function(value) {
                                      if ($.trim(value) == "")
                                        return "Заповніть поле";
                                    }'
                )
            );
        } else
            echo CHtml::decode($data->text);
        ?>
    </p>
</div>