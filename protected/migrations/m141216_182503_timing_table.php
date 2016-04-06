<?php

class m141216_182503_timing_table extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{timing}}", array(
			'id'=>'pk',
			'start_sale'=>'TIMESTAMP NOT NULL',
			'stop_sale'=>'TIMESTAMP NOT NULL',
			'entrance'=>'TIMESTAMP NOT NULL',
			'event_id'=>'INT NOT NULL',
			'status'=>'INT NULL DEFAULT 1'
		), 'ENGINE=InnoDB COLLATE=utf8_general_ci');

		$this->addForeignKey("fk_{{timing}}_{{event}}", "{{timing}}", "event_id", "{{event}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{timing}}_{{event}}", "{{timing}}");

		$this->dropTable("{{timing}}");
	}


}