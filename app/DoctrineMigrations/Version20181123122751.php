<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181123122751 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE unit_time_price_construction_site_type DROP FOREIGN KEY FK_A599749269BC063B');
        $this->addSql('CREATE TABLE unit_time_price_work_site_type (unit_time_price_id INT NOT NULL, work_site_type_id INT NOT NULL, INDEX IDX_6AB270621C28142E (unit_time_price_id), INDEX IDX_6AB270623C9A3CB2 (work_site_type_id), PRIMARY KEY(unit_time_price_id, work_site_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_site (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_9080B8DC5E237E06 (name), UNIQUE INDEX UNIQ_9080B8DC989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE unit_time_price_work_site_type ADD CONSTRAINT FK_6AB270621C28142E FOREIGN KEY (unit_time_price_id) REFERENCES unit_time_price (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE unit_time_price_work_site_type ADD CONSTRAINT FK_6AB270623C9A3CB2 FOREIGN KEY (work_site_type_id) REFERENCES work_site_type (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE construction_site_type');
        $this->addSql('DROP TABLE unit_time_price_construction_site_type');
        $this->addSql('DROP INDEX UNIQ_D54C42855E237E06 ON work_site_type');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE construction_site_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_AE7F0B3D989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit_time_price_construction_site_type (unit_time_price_id INT NOT NULL, construction_site_type_id INT NOT NULL, INDEX IDX_A59974921C28142E (unit_time_price_id), INDEX IDX_A599749269BC063B (construction_site_type_id), PRIMARY KEY(unit_time_price_id, construction_site_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type ADD CONSTRAINT FK_A59974921C28142E FOREIGN KEY (unit_time_price_id) REFERENCES unit_time_price (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type ADD CONSTRAINT FK_A599749269BC063B FOREIGN KEY (construction_site_type_id) REFERENCES construction_site_type (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE unit_time_price_work_site_type');
        $this->addSql('DROP TABLE work_site');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D54C42855E237E06 ON work_site_type (name)');
    }
}
