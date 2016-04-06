<?php

class m151123_100503_modify_cashier_percent extends CDbMigration
{
	public function up()
	{
		$this->alterColumn("{{cashier_percent}}", "order_cash_print_percent", "FLOAT(5,2) NOT NULL DEFAULT 0");
		$this->alterColumn("{{cashier_percent}}", "cash_print_percent", "FLOAT(5,2) NOT NULL DEFAULT 0");
		$this->alterColumn("{{cashier_percent}}", "print_percent", "FLOAT(5,2) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->alterColumn("{{cashier_percent}}", "order_cash_print_percent", "INT(11) NOT NULL DEFAULT 0");
		$this->alterColumn("{{cashier_percent}}", "cash_print_percent", "INT(11) NOT NULL DEFAULT 0");
		$this->alterColumn("{{cashier_percent}}", "print_percent", "INT(11) NOT NULL DEFAULT 0");
	}

}