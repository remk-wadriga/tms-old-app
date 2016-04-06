<?php

class m141108_202356_change_unique_type_for_city extends CDbMigration
{
	public function up()
	{
		$this->dropIndex("name_u", "{{city}}");
	}

	public function down()
	{
		$this->createIndex("name_u", "{{city}}", "name", true);
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