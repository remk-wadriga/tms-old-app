<?php

class m151228_091707_add_status_to_slider extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{slider}}", "status","INT(11) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{slider}}", "status");
	}


}