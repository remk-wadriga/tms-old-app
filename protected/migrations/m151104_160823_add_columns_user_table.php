<?php

class m151104_160823_add_columns_user_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{user}}","country_id","INT(11) NULL DEFAULT 0");
		$this->addColumn("{{user}}","city_id","INT(11) NULL DEFAULT 0");
		$this->addColumn("{{user}}","address","TEXT NULL");
		$this->addColumn("{{user}}","np_id","INT(11) NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{user}}","country_id");
		$this->dropColumn("{{user}}","city_id");
		$this->dropColumn("{{user}}","address");
		$this->dropColumn("{{user}}","np_id");
	}

}