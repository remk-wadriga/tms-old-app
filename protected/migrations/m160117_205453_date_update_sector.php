<?php

class m160117_205453_date_update_sector extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{sector}}", "date_update", "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
	}

	public function down()
	{
		$this->dropColumn("{{sector}}", "date_update");
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