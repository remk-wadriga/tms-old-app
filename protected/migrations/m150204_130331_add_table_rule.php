<?php

class m150204_130331_add_table_rule extends CDbMigration
{
	public function up()
	{
        $this->createTable('{{tree_rule}}', array(
            'id' => 'pk',
            'model' => 'varchar(100) NOT NULL',
            'rule' => 'text NOT NULL',
            'count' => 'int NOT NULL default 0',
            'tree_id' => 'int NOT NULL',
            'status' => 'int NOT NULL default 1',
        ));

        $this->addForeignKey("fk_{{tree_rule}}_{{tree}}", "{{tree_rule}}", "tree_id", "{{tree}}", "id");
	}

	public function down()
	{
        $this->dropForeignKey("fk_{{tree_rule}}_{{tree}}", "{{tree_rule}}");
        $this->dropTable("{{tree_rule}}");
	}

}