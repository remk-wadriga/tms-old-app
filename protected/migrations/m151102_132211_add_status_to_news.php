<?php

class m151102_132211_add_status_to_news extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{post}}","status","INT(11) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{post}}","status");
	}

}