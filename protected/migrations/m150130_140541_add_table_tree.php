<?php

class m150130_140541_add_table_tree extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{tree}}', array(
            'id' => 'pk',
            'name' => 'varchar(50) NOT NULL',
            'description' => 'text NULL ',
            'root' => 'int NOT NULL',
            'lft' => 'int NOT NULL',
            'rgt' => 'int NOT NULL',
            'level' => 'int NOT NULL',
            'status' => 'int NOT NULL default 1',
        ));
	}

	public function down()
	{
        $this->dropTable('{{tree}}');
	}

}