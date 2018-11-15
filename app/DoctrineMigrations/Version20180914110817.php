<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180914110817 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE private_individual (id INT AUTO_INCREMENT NOT NULL, postal_address_id INT DEFAULT NULL, firstName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, phoneNumber VARCHAR(15) DEFAULT NULL, mailAddress VARCHAR(255) DEFAULT NULL, slug VARCHAR(128) NOT NULL, honorific VARCHAR(10) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_8A565660989D9B62 (slug), UNIQUE INDEX UNIQ_8A565660FD54954B (postal_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE private_individual ADD CONSTRAINT FK_8A565660FD54954B FOREIGN KEY (postal_address_id) REFERENCES postal_address (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE private_individual');
    }
}
