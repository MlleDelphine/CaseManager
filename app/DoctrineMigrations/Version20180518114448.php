<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180518114448 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE unit_time_price_construction_site_type (unit_time_price_id INT NOT NULL, construction_site_type_id INT NOT NULL, INDEX IDX_A59974921C28142E (unit_time_price_id), INDEX IDX_A599749269BC063B (construction_site_type_id), PRIMARY KEY(unit_time_price_id, construction_site_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE constructionSiteType (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_A70DE092989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type ADD CONSTRAINT FK_A59974921C28142E FOREIGN KEY (unit_time_price_id) REFERENCES unit_time_price (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type ADD CONSTRAINT FK_A599749269BC063B FOREIGN KEY (construction_site_type_id) REFERENCES constructionSiteType (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE unit_time_price_construction_site_type DROP FOREIGN KEY FK_A599749269BC063B');
        $this->addSql('DROP TABLE unit_time_price_construction_site_type');
        $this->addSql('DROP TABLE constructionSiteType');
    }
}
