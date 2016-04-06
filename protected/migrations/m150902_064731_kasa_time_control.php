<?php

class m150902_064731_kasa_time_control extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{ticket}}", "delivery_type", "INT(11) NULL");

		$this->createTable("{{kasa_control}}", array(
				"id"=>"pk",
				"user_id"=>"INT(11) NOT NULL",
				"sum"=>"DECIMAL(8,2) NOT NULL",
				"date"=>"TIMESTAMP NOT NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{kasa_control}}_{{user}}", "{{kasa_control}}", "user_id", "{{user}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{kasa_control}}_{{user}}", "{{kasa_control}}");

		$this->dropTable("{{kasa_control}}");

		$this->dropColumn("{{ticket}}", "delivery_type");
	}
}