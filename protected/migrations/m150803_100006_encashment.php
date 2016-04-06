<?php

class m150803_100006_encashment extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{encashment}}", array(
			"id"=>"pk",
			"date_create"=>"TIMESTAMP NOT NULL",
			"sum"=>"DECIMAL(8,2) NOT NULL",
			"collector_id"=>"INT(11) NOT NULL",
			"event_id"=>"INT(11) NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{cashier_encashment}}", array(
			"cashier_id"=>"INT(11) NOT NULL",
			"encashment_id"=>"INT(11) NOT NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{cashier_encashment}}_{{encashment}}", "{{cashier_encashment}}", "encashment_id", "{{encashment}}", "id");
		$this->addForeignKey("fk_{{cashier_encashment}}_{{user}}", "{{cashier_encashment}}", "cashier_id", "{{user}}", "id");

		$this->addForeignKey("fk_{{encashment}}_{{user}}", "{{encashment}}", "collector_id", "{{user}}", "id");
		$this->addForeignKey("fk_{{encashment}}_{{event}}", "{{encashment}}", "event_id", "{{event}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{encashment}}_{{event}}", "{{encashment}}");
		$this->dropForeignKey("fk_{{encashment}}_{{user}}", "{{encashment}}");

		$this->dropForeignKey("fk_{{cashier_encashment}}_{{user}}", "{{cashier_encashment}}");
		$this->dropForeignKey("fk_{{cashier_encashment}}_{{encashment}}", "{{cashier_encashment}}");

		$this->dropTable("{{cashier_encashment}}");

		$this->dropTable("{{encashment}}");

	}
}