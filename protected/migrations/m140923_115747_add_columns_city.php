<?php

class m140923_115747_add_columns_city extends CDbMigration
{
	public function up()
	{
        $this->addColumn("{{city}}", "root", "INT(10) DEFAULT NULL");
        $this->addColumn("{{city}}", "lft", "INT(10) NOT NULL");
        $this->addColumn("{{city}}", "rgt", "INT(10) NOT NULL");
        $this->addColumn("{{city}}", "level", "SMALLINT(5) NOT NULL");

	}

	public function down()
	{
        $this->dropColumn("{{city}}", "root");
        $this->dropColumn("{{city}}", "lft");
        $this->dropColumn("{{city}}", "rgt");
        $this->dropColumn("{{city}}", "level");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}