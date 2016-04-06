<?php

class m140925_235231_unique_name_country_city extends CDbMigration
{
	public function up()
	{
        $this->createIndex("name_u", "{{country}}", "name", true);
        $this->createIndex("name_u", "{{city}}", "name", true);
	}

	public function down()
	{
		$this->dropIndex("name_u", "{{country}}");
		$this->dropIndex("name_u", "{{city}}");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}
CREATE TABLE IF NOT EXISTS `mydb`.`country` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(128) NOT NULL,
  `country_code` VARCHAR(2) NOT NULL,
  `status` INT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
	public function safeDown()
	{
	}
	*/
}