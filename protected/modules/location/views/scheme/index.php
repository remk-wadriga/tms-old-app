<?php
/* @var $this SchemeController */

$this->widget(
	'booster.widgets.TbTabs',
	array(
		'type' => 'pills',
		'justified' => true,
		'tabs' => array(
			array('label' => 'Візуальна схема', 'content' => $this->renderPartial('_visual', array('model'=>$model), true), 'active'=>true),
			array('label' => 'Структурна схема', 'content' => 'Структурна схема'),
			array('label' => 'Технічна інформація', 'content' => $this->renderPartial('_techInfo', array("model"=>$model), true)),
			array('label' => 'Історія змін', 'content' => 'Даний функціонал буде перенесено на Реліз № 2. Тому на цю сторінку уваги не звертаємо.'),
		)
	)
);