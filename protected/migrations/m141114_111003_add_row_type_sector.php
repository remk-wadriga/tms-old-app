<?php

class m141114_111003_add_row_type_sector extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{sector}}", "type_row_id", "INT(11) NOT NULL");
		$this->addColumn("{{sector}}", "type_place_id", "INT(11) NOT NULL");

		$this->addForeignKey("fk_{{sector}}_{{type_row}}", "{{sector}}", "type_row_id", "{{type_row}}", "id");
		$this->addForeignKey("fk_{{sector}}_{{type_place}}", "{{sector}}", "type_place_id", "{{type_place}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{sector}}_{{type_place}}", "{{sector}}");
		$this->dropForeignKey("fk_{{sector}}_{{type_row}}", "{{sector}}");

		$this->dropColumn("{{sector}}", "type_place_id");
		$this->dropColumn("{{sector}}", "type_row_id");
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