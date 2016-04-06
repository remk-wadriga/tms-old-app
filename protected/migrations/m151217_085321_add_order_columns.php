<?php

class m151217_085321_add_order_columns extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{order}}", "name", "VARCHAR(255) NULL");
		$this->addColumn("{{order}}", "surname", "VARCHAR(255) NULL");
		$this->addColumn("{{order}}", "phone", "VARCHAR(255) NULL");
		$this->addColumn("{{order}}", "email", "VARCHAR(255) NULL");
		$this->addColumn("{{order}}", "np_number", "VARCHAR(255) NULL");
	}

	public function down()
	{
		$this->dropColumn("{{order}}", "np_number");
		$this->dropColumn("{{order}}", "email");
		$this->dropColumn("{{order}}", "phone");
		$this->dropColumn("{{order}}", "surname");
		$this->dropColumn("{{order}}", "name");
	}

}