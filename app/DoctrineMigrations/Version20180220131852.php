<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180220131852 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, unit VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_BC91F4165E237E06 (name), UNIQUE INDEX UNIQ_BC91F416AEA34913 (reference), UNIQUE INDEX UNIQ_BC91F416989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE time_price ADD resource_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE time_price ADD CONSTRAINT FK_8FBAC3A589329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('CREATE INDEX IDX_8FBAC3A589329D25 ON time_price (resource_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_price DROP FOREIGN KEY FK_8FBAC3A589329D25');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP INDEX IDX_8FBAC3A589329D25 ON time_price');
        $this->addSql('ALTER TABLE time_price DROP resource_id');
    }
}
