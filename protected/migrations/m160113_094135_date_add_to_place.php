<?php

class m160113_094135_date_add_to_place extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{place}}", "date_add", "TIMESTAMP NOT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{place}}", "date_add");
	}
}