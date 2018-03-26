<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180326162230 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE corporation_job_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_D2E89E695E237E06 (name), UNIQUE INDEX UNIQ_D2E89E69989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE corporation_employee ADD corporation_job_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE corporation_employee ADD CONSTRAINT FK_5122C6166054F893 FOREIGN KEY (corporation_job_status_id) REFERENCES corporation_job_status (id)');
        $this->addSql('CREATE INDEX IDX_5122C6166054F893 ON corporation_employee (corporation_job_status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE corporation_employee DROP FOREIGN KEY FK_5122C6166054F893');
        $this->addSql('DROP TABLE corporation_job_status');
        $this->addSql('DROP INDEX IDX_5122C6166054F893 ON corporation_employee');
        $this->addSql('ALTER TABLE corporation_employee DROP corporation_job_status_id');
    }
}
