<?php

class m151218_140644_add_order_parentname extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{order}}", "patr_name", "VARCHAR(255) NULL");

	}

	public function down()
	{
		$this->dropColumn("{{order}}", "patr_name");
	}
}