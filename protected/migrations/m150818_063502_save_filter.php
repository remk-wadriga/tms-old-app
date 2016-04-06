<?php

class m150818_063502_save_filter extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{order_filter}}", array(
			"id"=>"pk",
			"user_id"=>"INT(11) NOT NULL",
			"name"=>"VARCHAR(128) NOT NULL",
			"settings"=>"TEXT NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{order_filter}}_{{user}}", "{{order_filter}}", "user_id", "{{user}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{order_filter}}_{{user}}", "{{order_filter}}");

		$this->dropTable("{{order_filter}}");
	}
}