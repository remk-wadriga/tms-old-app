<?php

class m150821_141936_user_token extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{user_token}}", array(
			"id"=>"pk",
			"user_id"=>"INT(11) NOT NULL",
			"token"=>"INT(11) NOT NULL",
			"platform_id"=>"INT(11) NOT NULL",
			"date_create"=>"TIMESTAMP NOT NULL",
			"status"=>"INT(11) NOT NULL",
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{user_token}}_{{user}}", "{{user_token}}", "user_id", "{{user}}", "id");
		$this->addForeignKey("fk_{{user_token}}_{{platform}}", "{{user_token}}", "platform_id", "{{user}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{user_token}}_{{platform}}", "{{user_token}}");
		$this->dropForeignKey("fk_{{user_token}}_{{user}}", "{{user_token}}");
		$this->dropTable("{{user_token}}");
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