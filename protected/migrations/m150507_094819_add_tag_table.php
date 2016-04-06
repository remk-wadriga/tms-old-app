<?php

class m150507_094819_add_tag_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{tag}}', array(
            'id'=>'pk',
            'model_name' => 'varchar(100) NOT NULL',
            'model_id' => 'int NOT NULL',
            'relation_id' => 'int NOT NULL',
            'relation_name' => 'varchar(100) NOT NULL',
            'template_id' => 'int NOT NULL default 0',
        ));
	}

	public function down()
	{
        $this->dropTable("{{tag}}");
	}

}