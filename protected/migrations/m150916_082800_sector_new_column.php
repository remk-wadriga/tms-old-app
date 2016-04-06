<?php

class m150916_082800_sector_new_column extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{sector}}", "params", "LONGTEXT NULL");
		$this->addColumn("{{scheme}}", "params", "LONGTEXT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{scheme}}", "params");
		$this->dropColumn("{{sector}}", "params");
	}

}