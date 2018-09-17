<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180917133601 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE corporation_site DROP FOREIGN KEY FK_46737DBAF2E96AF0');
        $this->addSql('ALTER TABLE corporation_employee DROP FOREIGN KEY FK_5122C616FD15998D');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, postal_address_id INT DEFAULT NULL, corporation_group_id INT DEFAULT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, legalStatus VARCHAR(255) DEFAULT NULL, phoneNumber VARCHAR(15) DEFAULT NULL, firstName VARCHAR(255) DEFAULT NULL, lastName VARCHAR(255) DEFAULT NULL, mailAddress VARCHAR(255) DEFAULT NULL, honorific VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_81398E09989D9B62 (slug), INDEX IDX_81398E09FD54954B (postal_address_id), UNIQUE INDEX UNIQ_81398E095E237E06 (name), INDEX IDX_81398E09F2E96AF0 (corporation_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09FD54954B FOREIGN KEY (postal_address_id) REFERENCES postal_address (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F2E96AF0 FOREIGN KEY (corporation_group_id) REFERENCES customer (id)');
        $this->addSql('DROP TABLE corporation_group');
        $this->addSql('DROP TABLE corporation_site');
        $this->addSql('DROP TABLE private_individual');
        $this->addSql('ALTER TABLE corporation_employee DROP FOREIGN KEY FK_5122C616FD15998D');
        $this->addSql('ALTER TABLE corporation_employee ADD CONSTRAINT FK_5122C616FD15998D FOREIGN KEY (corporation_site_id) REFERENCES customer (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09F2E96AF0');
        $this->addSql('ALTER TABLE corporation_employee DROP FOREIGN KEY FK_5122C616FD15998D');
        $this->addSql('CREATE TABLE corporation_group (id INT AUTO_INCREMENT NOT NULL, postal_address_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, legalStatus VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_E13C08425E237E06 (name), UNIQUE INDEX UNIQ_E13C0842989D9B62 (slug), UNIQUE INDEX UNIQ_E13C0842FD54954B (postal_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE corporation_site (id INT AUTO_INCREMENT NOT NULL, corporation_group_id INT DEFAULT NULL, postal_address_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, phoneNumber VARCHAR(15) DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_46737DBA989D9B62 (slug), UNIQUE INDEX UNIQ_46737DBAFD54954B (postal_address_id), INDEX IDX_46737DBAF2E96AF0 (corporation_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE private_individual (id INT AUTO_INCREMENT NOT NULL, postal_address_id INT DEFAULT NULL, firstName VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, lastName VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, phoneNumber VARCHAR(15) DEFAULT NULL COLLATE utf8_unicode_ci, mailAddress VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, honorific VARCHAR(10) NOT NULL COLLATE utf8_unicode_ci, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_8A565660989D9B62 (slug), UNIQUE INDEX UNIQ_8A565660FD54954B (postal_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE corporation_group ADD CONSTRAINT FK_E13C0842FD54954B FOREIGN KEY (postal_address_id) REFERENCES postal_address (id)');
        $this->addSql('ALTER TABLE corporation_site ADD CONSTRAINT FK_46737DBAF2E96AF0 FOREIGN KEY (corporation_group_id) REFERENCES corporation_group (id)');
        $this->addSql('ALTER TABLE corporation_site ADD CONSTRAINT FK_46737DBAFD54954B FOREIGN KEY (postal_address_id) REFERENCES postal_address (id)');
        $this->addSql('ALTER TABLE private_individual ADD CONSTRAINT FK_8A565660FD54954B FOREIGN KEY (postal_address_id) REFERENCES postal_address (id)');
        $this->addSql('DROP TABLE customer');
        $this->addSql('ALTER TABLE corporation_employee DROP FOREIGN KEY FK_5122C616FD15998D');
        $this->addSql('ALTER TABLE corporation_employee ADD CONSTRAINT FK_5122C616FD15998D FOREIGN KEY (corporation_site_id) REFERENCES corporation_site (id)');
    }
}
