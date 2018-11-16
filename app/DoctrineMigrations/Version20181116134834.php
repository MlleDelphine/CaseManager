<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181116134834 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer_contact (id INT AUTO_INCREMENT NOT NULL, corporation_job_status_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phoneNumber VARCHAR(15) DEFAULT NULL, mailAddress VARCHAR(255) DEFAULT NULL, slug VARCHAR(128) NOT NULL, honorific VARCHAR(10) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_50BF4286989D9B62 (slug), INDEX IDX_50BF42866054F893 (corporation_job_status_id), INDEX IDX_50BF42869395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_contact ADD CONSTRAINT FK_50BF42866054F893 FOREIGN KEY (corporation_job_status_id) REFERENCES corporation_job_status (id)');
        $this->addSql('ALTER TABLE customer_contact ADD CONSTRAINT FK_50BF42869395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE corporation_employee');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE corporation_employee (id INT AUTO_INCREMENT NOT NULL, corporation_job_status_id INT DEFAULT NULL, corporation_site_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, last_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, phoneNumber VARCHAR(15) DEFAULT NULL COLLATE utf8_unicode_ci, mailAddress VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci, honorific VARCHAR(10) NOT NULL COLLATE utf8_unicode_ci, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_5122C616989D9B62 (slug), INDEX IDX_5122C616FD15998D (corporation_site_id), INDEX IDX_5122C6166054F893 (corporation_job_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE corporation_employee ADD CONSTRAINT FK_5122C6166054F893 FOREIGN KEY (corporation_job_status_id) REFERENCES corporation_job_status (id)');
        $this->addSql('ALTER TABLE corporation_employee ADD CONSTRAINT FK_5122C616FD15998D FOREIGN KEY (corporation_site_id) REFERENCES customer (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE customer_contact');
    }
}
