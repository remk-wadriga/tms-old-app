<?php

class m151122_162356_add_cashier_percent extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{cashier_percent}}", array(
			'id'=>'pk',
			"user_id"=>"INT(11) NOT NULL DEFAULT 0",
			"role_id"=>"INT(11) NOT NULL DEFAULT 0",
			"event_id"=>"INT(11) NOT NULL DEFAULT 0",
			"order_cash_print_percent" => "INT(11) NOT NULL DEFAULT 0",
			"cash_print_percent" => "INT(11) NOT NULL DEFAULT 0",
			"print_percent" => "INT(11) NOT NULL DEFAULT 0",
		), 'ENGINE=InnoDB COLLATE=utf8_general_ci');
	}

	public function down()
	{
		$this->dropTable("{{cashier_percent}}");
	}
}