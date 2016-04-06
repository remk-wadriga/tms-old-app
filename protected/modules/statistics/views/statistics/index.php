<?php
/* @var $this StatisticsController */
?>
<header class="header b-b bg-dark">
	<div class="row">
		<div class="col-xs-12">
			<h4 class="m-t m-b pull-left">Статистика</h4>
		</div>
	</div>
</header>
<div class="wrapper">
	<div class="col-md-12">
		<div class="col-md-2"><H3><?= CHtml::link("Загальна статистика", array("/statistics/statistics/basic", "event_id"=>$model->id), array('class'=>'font-family-base')) ?></H3></div>
		<div class="col-md-2"><H3><?= CHtml::link("Розширена статистика", array("/statistics/statistics/extended", "event_id"=>$model->id), array('class'=>'anim m-r')) ?></H3></div>
	</div>
	<p>
	</p>
	<div class="btn-info" align="center">
	<H1>Under development</H1>
	</div>
	<p>

	</p>
</div>
