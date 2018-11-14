<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181114145135 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_price DROP FOREIGN KEY FK_8FBAC3A5251838C9');
        $this->addSql('ALTER TABLE time_price DROP FOREIGN KEY FK_8FBAC3A589329D25');
        $this->addSql('ALTER TABLE time_price DROP FOREIGN KEY FK_8FBAC3A5E308AC6F');
        $this->addSql('DROP INDEX no_duplication_price_material ON time_price');
        $this->addSql('DROP INDEX no_duplication_price_resource ON time_price');
        $this->addSql('DROP INDEX no_duplication_price_user_employee ON time_price');
        $this->addSql('DROP INDEX IDX_8FBAC3A5E308AC6F ON time_price');
        $this->addSql('DROP INDEX IDX_8FBAC3A589329D25 ON time_price');
        $this->addSql('DROP INDEX IDX_8FBAC3A5251838C9 ON time_price');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_price ADD CONSTRAINT FK_8FBAC3A5251838C9 FOREIGN KEY (user_employee_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE time_price ADD CONSTRAINT FK_8FBAC3A589329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE time_price ADD CONSTRAINT FK_8FBAC3A5E308AC6F FOREIGN KEY (material_id) REFERENCES material (id)');
        $this->addSql('CREATE UNIQUE INDEX no_duplication_price_material ON time_price (fromDate, untilDate, material_id)');
        $this->addSql('CREATE UNIQUE INDEX no_duplication_price_resource ON time_price (fromDate, untilDate, resource_id)');
        $this->addSql('CREATE UNIQUE INDEX no_duplication_price_user_employee ON time_price (fromDate, untilDate, user_employee_id)');
        $this->addSql('CREATE INDEX IDX_8FBAC3A5E308AC6F ON time_price (material_id)');
        $this->addSql('CREATE INDEX IDX_8FBAC3A589329D25 ON time_price (resource_id)');
        $this->addSql('CREATE INDEX IDX_8FBAC3A5251838C9 ON time_price (user_employee_id)');
    }
}
