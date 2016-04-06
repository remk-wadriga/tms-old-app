<?php

class m151014_101737_add_sector_columns extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{sector}}", "frontend", "INT(1) NOT NULL DEFAULT 1");
		$this->addColumn("{{sector}}", "backend", "INT(1) NOT NULL DEFAULT 1");
	}

	public function down()
	{
		$this->dropColumn("{{sector}}", "backend");
        $this->dropColumn("{{sector}}", "frontend");
	}
}