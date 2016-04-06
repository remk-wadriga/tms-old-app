<?php

class m141213_094711_drop_key_for_group extends CDbMigration
{
	public function up()
	{
		$this->dropForeignKey("fk_{{group}}_{{classification}}", "{{group}}");
		$this->dropColumn("{{group}}", "classification_id");
	}

	public function down()
	{
		$this->addColumn("{{group}}", "classification_id", "INT NOT NULL");
        $this->addForeignKey("fk_{{group}}_{{classification}}", "{{group}}", "classification_id", "{{classification}}", "id");
	}


}