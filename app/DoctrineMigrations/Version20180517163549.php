<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180517163549 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX no_duplication_unit_price_equipment ON unit_time_price (fromDate, untilDate, equipment_id)');
        $this->addSql('CREATE UNIQUE INDEX no_duplication_price_material ON time_price (fromDate, untilDate, material_id)');
        $this->addSql('CREATE UNIQUE INDEX no_duplication_price_resource ON time_price (fromDate, untilDate, resource_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX no_duplication_price_material ON time_price');
        $this->addSql('DROP INDEX no_duplication_price_resource ON time_price');
        $this->addSql('DROP INDEX no_duplication_unit_price_equipment ON unit_time_price');
    }
}
