<?php

class m141202_223003_change_sector_places_type_field extends CDbMigration
{
	public function up()
	{
		$this->alterColumn("{{sector}}", "places", "LONGTEXT NULL");
	}

	public function down()
	{
		$this->alterColumn("{{sector}}", "places", "TEXT NULL");
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