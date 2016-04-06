<?php

class m150811_064203_social_user extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{soc_network_user}}", array(
			"id"=>"pk",
			"network"=>"VARCHAR(45) NOT NULL",
			"network_id"=>"VARCHAR(128) NOT NULL",
			"user_id"=>"INT(11) NOT NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{soc_network_user}}_{{user}}", "{{soc_network_user}}", "user_id", "{{user}}", "id");

		$this->addColumn("{{user}}", "type", "INT(11) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{user}}", "type");

		$this->dropForeignKey("fk_{{soc_network_user}}_{{user}}", "{{soc_network_user}}");

		$this->dropTable("{{soc_network_user}}");
	}
}