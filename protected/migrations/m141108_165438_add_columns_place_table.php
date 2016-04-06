<?php

class m141108_165438_add_columns_place_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{place}}", "type_row_id", "INT(11) NOT NULL");
		$this->addColumn("{{place}}", "type_place_id", "INT(11) NOT NULL");
		$this->addColumn("{{place}}", "edited_row", "VARCHAR(128) NULL");
		$this->addColumn("{{place}}", "edited_place", "VARCHAR(128) NULL");

		$this->addForeignKey("fk_{{place}}_{{type_row}}", "{{place}}", "type_row_id", "{{type_row}}", "id");
		$this->addForeignKey("fk_{{place}}_{{type_place}}", "{{place}}", "type_place_id", "{{type_place}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{place}}_{{type_place}}", "{{place}}");
		$this->dropForeignKey("fk_{{place}}_{{type_row}}", "{{place}}");

		$this->dropColumn("{{place}}", "type_place_id");
		$this->dropColumn("{{place}}", "type_row_id");
		$this->dropColumn("{{place}}", "edited_row");
		$this->dropColumn("{{place}}", "edited_place");
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