<?php
/**
 * Created by PhpStorm.
 * User: nodosauridae
 * Date: 14.07.15
 * Time: 12:51
 * @var $model Event
 * @var $form TbActiveForm
 */
?>

<section class="panel m-t">
    <header class="panel-heading bg-primary">
        <ul class="nav nav-tabs nav-justified text-uc">
            <li class="active"><a href="#css_ticket" data-toggle="tab">Квиток звичайний</a></li>
            <li><a href="#css_e_ticket" data-toggle="tab">Електронний квиток</a></li>
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="css_ticket">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel-group m-b" id="accordion1">
                            <div class="panel">
                                <div class="panel-heading bg-info">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseExample">Підказки</a>
                                </div>
                                <div id="collapseExample" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?= CHtml::label(htmlentities('<div class="sub_2">Вхід з: <span>{enter}</span></div>'),'')?>

                                    </div>
                                </div>
                                <div class="panel-heading bg-success">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">HTML</a>
                                </div>
                                <div id="collapseOne" class="panel-collapse in">
                                    <div class="panel-body">
                                        <?= $form->textArea($model, "blank[ticket]", array(
                                            "class"=>"form-control",
                                            "rows"=>"6",
                                            "data-minwords"=>"6",
                                            "data-required"=>"true"
                                        ))?>
                                        <br/>
                                        <?php
                                        $this->widget('booster.widgets.TbButton', array(
                                            'context'=>'info',
                                            'label'=>'Reset',
                                            'htmlOptions'=>array(
                                                'data-type'=>'ticket',
                                                'data-container'=>'index.html',
                                                'class'=>'reset'
                                            )
                                        ));?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading bg-success">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo">CSS</a>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?= $form->textArea($model, "blank[style]", array(
                                            "class"=>"form-control",
                                            "rows"=>"6",
                                            "data-minwords"=>"6",
                                            "data-required"=>"true"
                                        ))?>
                                        <br/>
                                        <?php
                                        $this->widget('booster.widgets.TbButton', array(
                                            'context'=>'info',
                                            'label'=>'Reset',
                                            'htmlOptions'=>array(
                                                'data-type'=>'ticket',
                                                'data-container'=>'ticket.css',
                                                'class'=>'reset'
                                            )
                                        ));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $this->widget('booster.widgets.TbButton', array(
                            'context'=>'success',
                            'label'=>'Попередній перегляд',
                            'htmlOptions'=>array(
                                'data-type'=>'blank',
                                'class'=>'previewTicket'
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        Онлайн вигляд квитка
                        <div class="blank_preview">

                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="css_e_ticket">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel-group m-b" id="accordion2">
                            <div class="panel">
                                <div class="panel-heading bg-info">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOnlineExample">Підказки</a>
                                </div>
                                <div id="collapseOnlineExample" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?= CHtml::label(htmlentities('<p><span>Вхід з:</span>{enter}</p>'),'')?>
                                    </div>
                                </div>
                                <div class="panel-heading bg-success">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">HTML</a>
                                </div>
                                <div id="collapseThree" class="panel-collapse in">
                                    <div class="panel-body">
                                        <?= $form->textArea($model, "e_ticket[ticket]", array(
                                            "class"=>"form-control",
                                            "rows"=>"6",
                                            "data-minwords"=>"6",
                                            "data-required"=>"true"
                                        ))?>
                                        <br/>
                                        <?php
                                        $this->widget('booster.widgets.TbButton', array(
                                            'context'=>'info',
                                            'label'=>'Reset',
                                            'htmlOptions'=>array(
                                                'data-type'=>'e_ticket',
                                                'data-container'=>'index.html',
                                                'class'=>'reset'
                                            )
                                        ));?>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading bg-success">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">CSS</a>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <?= $form->textArea($model, "e_ticket[style]", array(
                                            "class"=>"form-control",
                                            "rows"=>"6",
                                            "data-minwords"=>"6",
                                            "data-required"=>"true"
                                        ))?>
                                        <br/>
                                        <?php
                                        $this->widget('booster.widgets.TbButton', array(
                                            'context'=>'info',
                                            'label'=>'Reset',
                                            'htmlOptions'=>array(
                                                'data-type'=>'e_ticket',
                                                'data-container'=>'e_ticket.css',
                                                'class'=>'reset'
                                            )
                                        ));?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $this->widget('booster.widgets.TbButton', array(
                            'context'=>'success',
                            'label'=>'Попередній перегляд',
                            'htmlOptions'=>array(
                                'data-type'=>'e_ticket',
                                'class'=>'previewTicket'
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-sm-6">
                        Онлайн вигляд квитка
                        <div class="e_ticket_preview">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>