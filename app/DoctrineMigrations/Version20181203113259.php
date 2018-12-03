<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181203113259 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE unit_time_point (id INT AUTO_INCREMENT NOT NULL, customer_article_id INT NOT NULL, unit VARCHAR(255) NOT NULL, unitary_point INT NOT NULL, from_date DATETIME NOT NULL, until_date DATETIME DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_F17F84988F5D821E (customer_article_id), UNIQUE INDEX no_duplication_unit_point_article (from_date, until_date, unit, customer_article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_article (id INT AUTO_INCREMENT NOT NULL, customer_chapter_id INT NOT NULL, name VARCHAR(255) NOT NULL, designation LONGTEXT DEFAULT NULL, color VARCHAR(8) NOT NULL, slug VARCHAR(128) NOT NULL, reference VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_1EE7AAD8665648E9 (color), UNIQUE INDEX UNIQ_1EE7AAD8989D9B62 (slug), UNIQUE INDEX UNIQ_1EE7AAD8AEA34913 (reference), INDEX IDX_1EE7AAD8AA5643EA (customer_chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_chapter (id INT AUTO_INCREMENT NOT NULL, customer_serial_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_E55C1190989D9B62 (slug), INDEX IDX_E55C119047F288F (customer_serial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_serial (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_62B1158D989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE unit_time_point ADD CONSTRAINT FK_F17F84988F5D821E FOREIGN KEY (customer_article_id) REFERENCES customer_article (id)');
        $this->addSql('ALTER TABLE customer_article ADD CONSTRAINT FK_1EE7AAD8AA5643EA FOREIGN KEY (customer_chapter_id) REFERENCES customer_chapter (id)');
        $this->addSql('ALTER TABLE customer_chapter ADD CONSTRAINT FK_E55C119047F288F FOREIGN KEY (customer_serial_id) REFERENCES customer_serial (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE unit_time_point DROP FOREIGN KEY FK_F17F84988F5D821E');
        $this->addSql('ALTER TABLE customer_article DROP FOREIGN KEY FK_1EE7AAD8AA5643EA');
        $this->addSql('ALTER TABLE customer_chapter DROP FOREIGN KEY FK_E55C119047F288F');
        $this->addSql('DROP TABLE unit_time_point');
        $this->addSql('DROP TABLE customer_article');
        $this->addSql('DROP TABLE customer_chapter');
        $this->addSql('DROP TABLE customer_serial');
    }
}
