<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180917153425 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE unit_time_price_construction_site_type DROP FOREIGN KEY FK_A59974921C28142E');
        $this->addSql('CREATE TABLE construction_site_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_AE7F0B3D989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE constructionSiteType');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type DROP FOREIGN KEY FK_A59974921C28142E');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type ADD CONSTRAINT FK_A59974921C28142E FOREIGN KEY (construction_site_type_id) REFERENCES construction_site_type (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE unit_time_price_construction_site_type DROP FOREIGN KEY FK_A59974921C28142E');
        $this->addSql('CREATE TABLE constructionSiteType (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, description LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_A70DE092989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE construction_site_type');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type DROP FOREIGN KEY FK_A59974921C28142E');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type ADD CONSTRAINT FK_A59974921C28142E FOREIGN KEY (construction_site_type_id) REFERENCES constructionSiteType (id) ON DELETE CASCADE');
    }
}
