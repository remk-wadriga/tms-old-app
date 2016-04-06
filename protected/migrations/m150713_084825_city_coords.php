<?php

class m150713_084825_city_coords extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{city}}", "lat", "DECIMAL(18,12) NOT NULL DEFAULT 0");
		$this->addColumn("{{city}}", "lng", "DECIMAL(18,12) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{city}}", "lat");
		$this->dropColumn("{{city}}", "lng");
	}
}