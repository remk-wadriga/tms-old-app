<?php

class m151224_064858_column_actions_access extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{access}}", "allow_action","TEXT NULL");
        $this->dropForeignKey("fk_{{delivery}}_{{order}}", "{{delivery}}");
        $this->dropColumn("{{delivery}}", "order_id");
        $this->addColumn("{{order}}", "delivery_id", "INT(11) NULL");
	}

	public function down()
	{
        $this->dropColumn("{{order}}", "delivery_id");
        $this->addColumn("{{delivery}}", "order_id", "INT(11) NOT NULL");
        $this->truncateTable("{{delivery}}");
        $this->addForeignKey("fk_{{delivery}}_{{order}}", "{{delivery}}", "order_id", "{{order}}", "id");
		$this->dropColumn("{{access}}", "allow_action");
	}
}