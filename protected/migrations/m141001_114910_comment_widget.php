<?php

class m141001_114910_comment_widget extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{comment}}", array(
			"id"=>"pk",
			"model"=>"VARCHAR(255) NOT NULL",
			"model_id"=>"INT NOT NULL",
			"text"=>"TEXT NOT NULL",
			"dateadd"=>"TIMESTAMP NOT NULL",
			"user_id"=>"INT NOT NULL",
			"status"=>"INT NOT NULL DEFAULT 1"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{comment}}_{{user}}", "{{comment}}", "user_id", "{{user}}", "id", "cascade", "cascade");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{comment}}_{{user}}", "{{comment}}");

		$this->dropTable("{{comment}}");
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