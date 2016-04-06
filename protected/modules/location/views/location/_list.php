
<div class="panel">
    <div class="panel-heading" role="tab" id="heading<?php echo "_".$data->id ?>">
        <div class="panel-title">
            <div class="row">
                <div class="col-md-8">
                    <a class="collapse-link" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo "_".$data->id ?>" aria-expanded="true" aria-controls="collapse<?php echo "_".$data->id ?>">
                        <strong><?php echo CHtml::encode($data->name) ?></strong> <small><?php echo $data->city->name ?></small>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="pull-right">
                        <?php
                            $this->widget("booster.widgets.TbButton", array(
                                'url'=>'#',
                                'label'=>' Додати схему',
                                'icon' => 'plus',
                                'buttonType'=>'link',
                                'context' => 'success',
                                'size'=>'extra_small',
                                'htmlOptions'=>array(
                                    'data-toggle' => 'modal',
                                    'data-target' => '#newScheme',
                                    'data-id' => $data->id,
                                    'class' => 'addNewScheme',
                                    'onclick' => 'addNewScheme('.$data->id.')'
                                )
                            ));
                        ?>
                        <?php
                            $this->widget("booster.widgets.TbButton", array(
                                'url' => Yii::app()->createUrl("/location/location/view", array("id"=>$data->id)),
                                'label' => ' Переглянути',
                                'icon' => 'eye-open',
                                'buttonType' => 'link',
                                'context' => 'success',
                                'size' => 'extra_small'
                            ));
                        ?>
                        <?php
                            $this->widget("booster.widgets.TbButton", array(
                                'url' => Yii::app()->createUrl('/location/location/update', array('id' => $data->id)),
                                'label' => ' Редагувати',
                                'icon' => 'pencil',
                                'buttonType' => 'link',
                                'context' => 'success',
                                'size' => 'extra_small'
                            ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="collapse<?php echo "_".$data->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo "_".$data->id ?>">
        <ul class="list-group">
            <?php foreach ($data->scheme as $scheme) { ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                                $this->widget(
                                    'booster.widgets.TbEditableField',
                                    array(
                                        'title'=>'Редагування назви схеми',
                                        'type' => 'text',
                                        'model' => $scheme,
                                        'attribute' => 'name',
                                        'url' => Yii::app()->createUrl("/configuration/configuration/update", array("model"=>"Scheme")),
                                    )
                                );
                            ?>
                        </div>
                        <div class="col-md-6">
                            <div class="pull-right">
                                <?php
                                    if($scheme->hasSectors)
                                        $this->widget("booster.widgets.TbButton", array(
                                            'url'=>Yii::app()->createUrl("/location/scheme/index", array('scheme_id'=>$scheme->id)),
                                            'label'=>' Перегляд',
                                            'icon' => 'eye-open',
                                            'buttonType'=>'link',
                                            'context' => 'success',
                                            'size'=>'extra_small',
                                        ));
                                    $this->widget("booster.widgets.TbButton", array(
                                        'url'=>Yii::app()->createUrl("/location/location/copyScheme", array('scheme_id'=>$scheme->id)),
                                        'label'=>' Копія',
                                        'icon' => 'resize-horizontal',
                                        'buttonType'=>'link',
                                        'context' => 'success',
                                        'size'=>'extra_small',
                                    ));
                                    $this->widget("booster.widgets.TbButton", array(
                                        'url'=>Yii::app()->createUrl("/location/sector/structure", array('scheme_id'=>$scheme->id)),
                                        'label'=>' Структурний ред.',
                                        'icon' => 'th',
                                        'buttonType'=>'link',
                                        'context' => 'success',
                                        'size'=>'extra_small',
                                    ));
                                    $this->widget("booster.widgets.TbButton", array(
                                        'url'=>Yii::app()->createUrl("/location/sector/visualScheme", array('scheme_id'=>$scheme->id)),
                                        'label'=>' Візуальний ред.',
                                        'icon' => 'fire',
                                        'buttonType'=>'link',
                                        'context' => 'success',
                                        'size'=>'extra_small',
                                    ));
                                ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
