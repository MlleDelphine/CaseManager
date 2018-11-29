<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181129152437 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE unit_time_price_work_site_type DROP FOREIGN KEY FK_6AB270623C9A3CB2');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, color VARCHAR(8) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_51C88FAD665648E9 (color), UNIQUE INDEX UNIQ_51C88FAD989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE unit_time_price_work_site_type');
        $this->addSql('DROP TABLE work_site_type');
        $this->addSql('ALTER TABLE unit_time_price ADD prestation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unit_time_price ADD CONSTRAINT FK_8C1255659E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id)');
        $this->addSql('CREATE INDEX IDX_8C1255659E45C554 ON unit_time_price (prestation_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE unit_time_price DROP FOREIGN KEY FK_8C1255659E45C554');
        $this->addSql('CREATE TABLE unit_time_price_work_site_type (unit_time_price_id INT NOT NULL, work_site_type_id INT NOT NULL, INDEX IDX_6AB270621C28142E (unit_time_price_id), INDEX IDX_6AB270623C9A3CB2 (work_site_type_id), PRIMARY KEY(unit_time_price_id, work_site_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_site_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, created DATETIME NOT NULL, updated DATETIME NOT NULL, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, color VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_D54C4285989D9B62 (slug), UNIQUE INDEX UNIQ_D54C4285665648E9 (color), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE unit_time_price_work_site_type ADD CONSTRAINT FK_6AB270621C28142E FOREIGN KEY (unit_time_price_id) REFERENCES unit_time_price (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE unit_time_price_work_site_type ADD CONSTRAINT FK_6AB270623C9A3CB2 FOREIGN KEY (work_site_type_id) REFERENCES work_site_type (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP INDEX IDX_8C1255659E45C554 ON unit_time_price');
        $this->addSql('ALTER TABLE unit_time_price DROP prestation_id');
    }
}
