<?php

class m150626_085721_add_seo_collums_to_event extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{event}}", "url", "TEXT NULL");
		$this->addColumn("{{event}}", "html_header", "TEXT NULL");
		$this->addColumn("{{event}}", "meta_description", "TEXT NULL");
		$this->addColumn("{{event}}", "keywords", "TEXT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{event}}", "keywords");
		$this->dropColumn("{{event}}", "meta_description");
		$this->dropColumn("{{event}}", "html_header");
		$this->dropColumn("{{event}}", "url");
	}


}