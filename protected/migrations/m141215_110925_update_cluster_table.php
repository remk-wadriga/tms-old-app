<?php

class m141215_110925_update_cluster_table extends CDbMigration
{
	public function up()
	{
		$this->dropColumn("{{cluster}}", "root");
		$this->dropColumn("{{cluster}}", "lft");
		$this->dropColumn("{{cluster}}", "rgt");
		$this->dropColumn("{{cluster}}", "level");

		$this->createTable("{{cluster_category}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(128) NOT NULL",
			"description"=>"TEXT NULL",
			"status"=>"INT NULL DEFAULT 1"
		), "ENGINE=InnoDb COLLATE=utf8_general_ci");

		$this->addColumn("{{cluster}}", "category_id", "INT(11) NULL");

		$this->addForeignKey("fk_{{cluster}}_{{cluster_category}}", "{{cluster}}", "category_id", "{{cluster_category}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{cluster}}_{{cluster_category}}", "{{cluster}}");

		$this->dropColumn("{{cluster}}", "category_id");

		$this->dropTable("{{cluster_category}}");

		$this->addColumn("{{cluster}}", "root", "INT(10) NULL");
		$this->addColumn("{{cluster}}", "lft", "INT(10) NOT NULL");
		$this->addColumn("{{cluster}}", "rgt", "INT(10) NOT NULL");
		$this->addColumn("{{cluster}}", "level", "SMALLINT(5) NOT NULL");
	}
}