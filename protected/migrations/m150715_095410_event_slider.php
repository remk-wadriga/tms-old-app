<?php

class m150715_095410_event_slider extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{event}}", "slider_main", "INT(1) NULL");
		$this->addColumn("{{event}}", "slider_city", "INT(1) NULL");
	}

	public function down()
	{
		$this->dropColumn("{{event}}", "slider_city");
		$this->dropColumn("{{event}}", "slider_main");
	}
}