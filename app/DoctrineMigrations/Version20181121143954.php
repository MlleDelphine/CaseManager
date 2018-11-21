<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181121143954 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE business_case ADD customer_contact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE business_case ADD CONSTRAINT FK_28939B091A6821C8 FOREIGN KEY (customer_contact_id) REFERENCES customer_contact (id)');
        $this->addSql('CREATE INDEX IDX_28939B091A6821C8 ON business_case (customer_contact_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE business_case DROP FOREIGN KEY FK_28939B091A6821C8');
        $this->addSql('DROP INDEX IDX_28939B091A6821C8 ON business_case');
        $this->addSql('ALTER TABLE business_case DROP customer_contact_id');
    }
}
