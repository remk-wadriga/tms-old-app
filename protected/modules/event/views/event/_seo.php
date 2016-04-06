<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.06.2015
 * Time: 12:58
 */
?>
<br/>
<div class="col-sx-12">
    <?php
    echo $form->textFieldGroup($model,'url');
    $this->widget('booster.widgets.TbButton', array(
        'context' => 'info',
        'label' => 'Генерувати url',
        'id' => 'generateUrl',
        'htmlOptions' => array(
            'style' => 'margin-bottom:15px'
    )));
    echo $form->textFieldGroup($model,'html_header');
    echo $form->textFieldGroup($model,'meta_description');
    echo $form->textFieldGroup($model,'keywords');
    ?>
</div>
