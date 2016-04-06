<?php

class m151030_125645_update_user_token extends CDbMigration
{
	public function up()
	{
		$this->dropForeignKey("fk_{{user_token}}_{{platform}}", "{{user_token}}");
		$this->addForeignKey("fk_{{user_token}}_{{platform}}", "{{user_token}}", "platform_id", "{{platform}}", "id");
        $this->alterColumn("{{user_token}}", "token", "BIGINT NOT NULL");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{user_token}}_{{platform}}", "{{user_token}}");
		$this->addForeignKey("fk_{{user_token}}_{{platform}}", "{{user_token}}", "platform_id", "{{user}}", "id");
        $this->alterColumn("{{user_token}}", "token", "INT(11) NOT NULL");
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