<?php

class m150609_132809_drop_foreign_sectorType extends CDbMigration
{
	public function up()
	{
		$this->dropForeignKey("fk_{{sector}}_{{type_sector}}", "{{sector}}");
	}

	public function down()
	{
		$this->addForeignKey("fk_{{sector}}_{{type_sector}}", "{{sector}}", "type_sector_id", "{{type_sector}}", "id");
	}
}