<?php

class m150928_161129_update_city_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{city}}","region_id", "INT(11) NULL");
	}

	public function down()
	{
        $this->dropColumn("{{city}}", "region_id");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}