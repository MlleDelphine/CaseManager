<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171123195906 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE job_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_2B04489D5E237E06 (name), UNIQUE INDEX UNIQ_2B04489D989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user ADD job_status_id INT DEFAULT NULL, ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD slug VARCHAR(128) NOT NULL, ADD phone_number INT NOT NULL, ADD created DATETIME NOT NULL, ADD updated DATETIME NOT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479AC47EFAC FOREIGN KEY (job_status_id) REFERENCES job_status (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_957A6479989D9B62 ON fos_user (slug)');
        $this->addSql('CREATE INDEX IDX_957A6479AC47EFAC ON fos_user (job_status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479AC47EFAC');
        $this->addSql('DROP TABLE job_status');
        $this->addSql('DROP INDEX UNIQ_957A6479989D9B62 ON fos_user');
        $this->addSql('DROP INDEX IDX_957A6479AC47EFAC ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP job_status_id, DROP first_name, DROP last_name, DROP slug, DROP phone_number, DROP created, DROP updated');
    }
}
