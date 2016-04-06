<?php

class m141209_082508_add_column_imported extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{scheme}}", "import", "TEXT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{scheme}}", "import");
	}
}