<?php

class m150706_075007_add_payment_prop extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{ticket}}", "np_number", "VARCHAR(128) NULL");
		$this->addColumn("{{ticket}}", "pay_type", "INT(11) NULL");
		$this->addColumn("{{ticket}}", "pay_status", "INT(11) NULL");
		$this->addColumn("{{ticket}}", "delivery_status", "INT(11) NOT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{ticket}}", "delivery_status");
		$this->dropColumn("{{ticket}}", "pay_status");
		$this->dropColumn("{{ticket}}", "pay_type");
		$this->dropColumn("{{ticket}}", "np_number");
	}
}