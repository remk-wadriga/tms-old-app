<?php

class m150428_112636_drop_clusterCategory_table extends CDbMigration
{
	public function up()
	{
		$this->dropTable("{{cluster_category}}");
	}

	public function down()
	{
		$this->createTable("{{cluster_category}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(128) NOT NULL",
			"description"=>"TEXT NULL",
			"status"=>"INT NULL DEFAULT 1"
		), "ENGINE=InnoDb COLLATE=utf8_general_ci");
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