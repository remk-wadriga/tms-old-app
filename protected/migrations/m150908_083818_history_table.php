<?php

class m150908_083818_history_table extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{history}}", array(
			"id"=>"pk",
            "model"=>"VARCHAR(128) NOT NULL",
            "model_id"=>"INT(11) NOT NULL",
            "user_id"=>"INT(11) NOT NULL",
            "date_create"=>"TIMESTAMP NOT NULL",
            "state"=>"LONGTEXT NOT NULL",
            "status"=>"INT(1) NOT NULL DEFAULT 1"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{history}}_{{user}}", "{{history}}", "user_id", "{{user}}", "id");
	}

	public function down()
	{
        $this->dropForeignKey("fk_{{history}}_{{user}}", "{{history}}");

		$this->dropTable("{{history}}");
	}
}