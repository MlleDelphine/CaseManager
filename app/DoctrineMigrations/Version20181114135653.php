<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181114135653 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE unit_time_price DROP FOREIGN KEY FK_8C125565A76ED395');
        $this->addSql('DROP INDEX IDX_8C125565A76ED395 ON unit_time_price');
        $this->addSql('ALTER TABLE unit_time_price DROP user_id');
        $this->addSql('ALTER TABLE time_price ADD user_employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE time_price ADD CONSTRAINT FK_8FBAC3A5251838C9 FOREIGN KEY (user_employee_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_8FBAC3A5251838C9 ON time_price (user_employee_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_price DROP FOREIGN KEY FK_8FBAC3A5251838C9');
        $this->addSql('DROP INDEX IDX_8FBAC3A5251838C9 ON time_price');
        $this->addSql('ALTER TABLE time_price DROP user_employee_id');
        $this->addSql('ALTER TABLE unit_time_price ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE unit_time_price ADD CONSTRAINT FK_8C125565A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_8C125565A76ED395 ON unit_time_price (user_id)');
    }
}
