<?php

class m150423_132329_add_columns_user extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{user}}", "surname", "VARCHAR(128) NOT NULL");
		$this->addColumn("{{user}}", "patr_name", "VARCHAR(128) NOT NULL");
		$this->addColumn("{{user}}", "phone", "VARCHAR(128) NOT NULL");
		$this->addColumn("{{user}}", "description", "TEXT NULL");

		$this->alterColumn("{{scheme}}", "import", "LONGTEXT NULL");

		$this->createTable("{{user_access}}", array(
			"action"=>"VARCHAR(255) NOT NULL",
			"user_id"=>"INT(11) NOT NULL",
			"event_id"=>"INT(11) NOT NULL"
		), "ENGINE=InnoDB  COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{user_access}}_{{user}}", "{{user_access}}", "user_id", "{{user}}", "id");
		$this->addForeignKey("fk_{{user_access}}_{{event}}", "{{user_access}}", "event_id", "{{event}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{user_access}}_{{event}}", "{{user_access}}");
		$this->dropForeignKey("fk_{{user_access}}_{{user}}", "{{user_access}}");

		$this->dropTable("{{user_access}}");

		$this->alterColumn("{{scheme}}", "import", "TEXT NULL");

		$this->dropColumn("{{user}}", "description");
		$this->dropColumn("{{user}}", "phone");
		$this->dropColumn("{{user}}", "patr_name");
		$this->dropColumn("{{user}}", "surname");
	}
}