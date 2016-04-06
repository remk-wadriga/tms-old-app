<?php
/* @var $this QuoteController
 * @var $dataProvider CActiveDataProvider
 * @var $form TbActiveForm
 * @var $model Quote
 */

?>
<h3>Перегляд квот </h3>
	<div class="alert alert-danger danger-sector-alert" style="display: none" role="alert">Помилка</div>
	<div class="alert alert-success success-sector-alert" style="display: none" role="alert">Успішно збережено</div>
<?php

Yii::app()->clientScript->registerScript("filterQuoteGrid", '
	function filterQuoteGrid() {
		$.fn.yiiGridView.update("QuoteGridView", {data:$("#Quote-filter-form").serialize()})
	}

	$("#Quote-filter-form input,select").on("change", filterQuoteGrid);

', CClientScript::POS_READY);

Yii::app()->clientScript->registerScript('eventSort', '
            $("#Quote-filter-form input:first").change();
', CClientScript::POS_LOAD);

$form = $this->beginWidget("booster.widgets.TbActiveForm", array(
	"id"=>"Quote-filter-form",
	"type"=>"inline",
));
?>
	<div class="col-lg-6">
	<?php
		echo $form->dropDownListGroup($model, "event_id", array(
			"widgetOptions"=>array(
				"data"=>Quote::getEventList(),
				"htmlOptions"=>array(
					"empty"=>"Назва події"
				)
			)
		));
		echo $form->dropDownListGroup($model, "contractor_id", array(
			"widgetOptions"=>array(
				"data"=>Quote::getContractorList(),
				"htmlOptions"=>array(
					"empty"=>"Контрагент"
				)
			)
		));

		echo $form->checkboxGroup($model, "status", array(
			"label"=>"Активні"
		));
			?>
	</div>
	<div class="col-lg-6">
		<?php
		echo CHtml::label("Сортування", "Quote_sorting");
		echo CHtml::tag("br");
		echo $form->radioButtonListGroup($model, "sorting", array(
			"widgetOptions"=>array(
				"data"=>Quote::$order
			)
		));
		?>
	</div>
<?php

$this->endWidget();

$this->widget("booster.widgets.TbExtendedGridView", array(
	"id"=>"QuoteGridView",
	"dataProvider"=>$dataProvider,
	"hideHeader"=>true,
	"columns"=>array(
		"id",
		array(
			"value"=>'$data->event->name."<br/>".$data->event->scheme->location->city->name."<br/>".$data->event->startTime."<br/>#".$data->order_id',
			"type"=>"raw"
		),
		array(
			"value"=>'$data->roleTo->name'
		),
		array(
			"value"=>'$data->name."<br/>".$data->order->user->username',
			"type"=>"raw"
		),
		array(
			"value"=>'$data->order->date_add'
		),
		array(
			"value"=>'count($data->order->tickets). " шт"'
		),
		array(
			"value"=>'$data->order->total. " грн"'
		),
		array(
			"value"=>'$data->order->status ? "активна" : "закрита"'
		),
		array(
			"class"=>"booster.widgets.TbButtonColumn",
			"template"=>"{view}<br/>{update}<br/>{invoice}<br/>{close}",
			"buttons"=>array(
				"view"=>array(
					"url"=>'Yii::app()->createUrl("/order/quote/view", array("quote_id"=>$data->id))',
				),
				"update"=>array(
					"url"=>'Yii::app()->createUrl("/order/quote/update", array("quote_id"=>$data->id))',
					"visible"=>'$data->order->status==Order::STATUS_ACTIVE'
				),
				"invoice"=>array(
					"label"=>"Накладна",
					"url"=>'Yii::app()->createUrl("/order/order/getInvoice", array("id"=>$data->id))',
//					"visible"=>'$data->order->status==Order::STATUS_ACTIVE',
					"options"=>array("target"=>"_blank"),
				),
				"close"=>array(
					"label"=>"Закрити",
					"url"=>'$data->order_id',
					"visible"=>'$data->order->status==Order::STATUS_ACTIVE',
					'options' => array(
						'confirm' => 'Ви впевнені що хочете закрити квоту?',
						'ajax' => array(
							'type' => 'POST',
							'url' => CController::createUrl("/order/quote/ajaxCloseQuote"),
							'data'=>'js:{id:$(this).attr("href")}',
							'success' => 'function(data){
							 if(data.includes("Додайте"))
                            	showAlert("danger",data,3000);
                        	 else
                            	window.location.replace(data);
                        }'
						)
					)
				),
			)
		)
	)
));

