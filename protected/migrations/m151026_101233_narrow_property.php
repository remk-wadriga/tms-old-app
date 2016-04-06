<?php

class m151026_101233_narrow_property extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{role_child}}", "narrow", "INT(1) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{role_child}}", "narrow");
	}
}