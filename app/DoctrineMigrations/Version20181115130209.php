<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181115130209 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, unit VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_7CBE75955E237E06 (name), UNIQUE INDEX UNIQ_7CBE7595AEA34913 (reference), UNIQUE INDEX UNIQ_7CBE7595989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_2B04489D5E237E06 (name), UNIQUE INDEX UNIQ_2B04489D989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, working TINYINT(1) DEFAULT \'1\' NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_D338D583AEA34913 (reference), UNIQUE INDEX UNIQ_D338D583989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_C4E0A61F5E237E06 (name), UNIQUE INDEX UNIQ_C4E0A61F989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit_time_price (id INT AUTO_INCREMENT NOT NULL, equipment_id INT DEFAULT NULL, unit VARCHAR(255) NOT NULL, unitaryPrice NUMERIC(10, 2) NOT NULL, fromDate DATETIME NOT NULL, untilDate DATETIME DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_8C125565517FE9FE (equipment_id), UNIQUE INDEX no_duplication_unit_price_equipment (fromDate, untilDate, equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit_time_price_construction_site_type (unit_time_price_id INT NOT NULL, construction_site_type_id INT NOT NULL, INDEX IDX_A59974921C28142E (unit_time_price_id), INDEX IDX_A599749269BC063B (construction_site_type_id), PRIMARY KEY(unit_time_price_id, construction_site_type_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, unit VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_BC91F4165E237E06 (name), UNIQUE INDEX UNIQ_BC91F416AEA34913 (reference), UNIQUE INDEX UNIQ_BC91F416989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_price (id INT AUTO_INCREMENT NOT NULL, material_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, user_employee_id INT DEFAULT NULL, unitaryPrice NUMERIC(10, 2) NOT NULL, fromDate DATETIME NOT NULL, untilDate DATETIME DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_8FBAC3A5E308AC6F (material_id), INDEX IDX_8FBAC3A589329D25 (resource_id), INDEX IDX_8FBAC3A5251838C9 (user_employee_id), UNIQUE INDEX no_duplication_price_material (fromDate, untilDate, material_id), UNIQUE INDEX no_duplication_price_resource (fromDate, untilDate, resource_id), UNIQUE INDEX no_duplication_price_user_employee (fromDate, untilDate, user_employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, job_status_id INT DEFAULT NULL, team_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, unit VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_957A6479989D9B62 (slug), INDEX IDX_957A6479AC47EFAC (job_status_id), INDEX IDX_957A6479296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE construction_site_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_AE7F0B3D989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, percentage NUMERIC(10, 2) NOT NULL, fromDate DATETIME NOT NULL, untilDate DATETIME DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_DFEC3F39989D9B62 (slug), UNIQUE INDEX no_duplication_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE corporation_job_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_D2E89E695E237E06 (name), UNIQUE INDEX UNIQ_D2E89E69989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, postal_address_id INT DEFAULT NULL, corporation_group_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, type VARCHAR(255) NOT NULL, legalStatus VARCHAR(255) DEFAULT NULL, phoneNumber VARCHAR(15) DEFAULT NULL, firstName VARCHAR(255) DEFAULT NULL, lastName VARCHAR(255) DEFAULT NULL, mailAddress VARCHAR(255) DEFAULT NULL, honorific VARCHAR(10) DEFAULT NULL, contact_name VARCHAR(255) DEFAULT NULL, contact_email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_81398E095E237E06 (name), UNIQUE INDEX UNIQ_81398E09989D9B62 (slug), INDEX IDX_81398E09FD54954B (postal_address_id), INDEX IDX_81398E09F2E96AF0 (corporation_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE corporation_employee (id INT AUTO_INCREMENT NOT NULL, corporation_job_status_id INT DEFAULT NULL, corporation_site_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phoneNumber VARCHAR(15) DEFAULT NULL, mailAddress VARCHAR(255) DEFAULT NULL, slug VARCHAR(128) NOT NULL, honorific VARCHAR(10) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_5122C616989D9B62 (slug), INDEX IDX_5122C6166054F893 (corporation_job_status_id), INDEX IDX_5122C616FD15998D (corporation_site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postal_address (id INT AUTO_INCREMENT NOT NULL, streetNumber VARCHAR(10) DEFAULT NULL, streetName VARCHAR(255) NOT NULL, complement VARCHAR(255) DEFAULT NULL, postalCode VARCHAR(10) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_972EFBF7989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE business_case (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, externalReference VARCHAR(255) NOT NULL, internalReference VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_28939B095E237E06 (name), UNIQUE INDEX UNIQ_28939B09989D9B62 (slug), UNIQUE INDEX UNIQ_28939B09ED9E3C23 (externalReference), UNIQUE INDEX UNIQ_28939B09CD300308 (internalReference), INDEX IDX_28939B099395C3F3 (customer_id), INDEX IDX_28939B09A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE unit_time_price ADD CONSTRAINT FK_8C125565517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type ADD CONSTRAINT FK_A59974921C28142E FOREIGN KEY (unit_time_price_id) REFERENCES unit_time_price (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type ADD CONSTRAINT FK_A599749269BC063B FOREIGN KEY (construction_site_type_id) REFERENCES construction_site_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE time_price ADD CONSTRAINT FK_8FBAC3A5E308AC6F FOREIGN KEY (material_id) REFERENCES material (id)');
        $this->addSql('ALTER TABLE time_price ADD CONSTRAINT FK_8FBAC3A589329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE time_price ADD CONSTRAINT FK_8FBAC3A5251838C9 FOREIGN KEY (user_employee_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479AC47EFAC FOREIGN KEY (job_status_id) REFERENCES job_status (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09FD54954B FOREIGN KEY (postal_address_id) REFERENCES postal_address (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F2E96AF0 FOREIGN KEY (corporation_group_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE corporation_employee ADD CONSTRAINT FK_5122C6166054F893 FOREIGN KEY (corporation_job_status_id) REFERENCES corporation_job_status (id)');
        $this->addSql('ALTER TABLE corporation_employee ADD CONSTRAINT FK_5122C616FD15998D FOREIGN KEY (corporation_site_id) REFERENCES customer (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE business_case ADD CONSTRAINT FK_28939B099395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE business_case ADD CONSTRAINT FK_28939B09A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('INSERT INTO fos_user (username, username_canonical, email, email_canonical, enabled, salt, password, last_login, confirmation_token, password_requested_at, roles, first_name, last_name, slug, phone_number, created, updated, unit, job_status_id, team_id) VALUES ("ssurrier", "ssurrier", "ssurrier@edtpe.fr", "ssurrier@edtpe.fr", 1, null, "$2y$13$wM0RgazAXBDHrgH2yOqUb.K53c3J0RfwVy4TfKoipQWpZ.KdwC9Re", null, null, null, \'a:1:{i:0;s:10:"ROLE_ADMIN";}\', "Samuel", "Surrier", "samuel-surrier", null, "2018-11-15 14:04:20", "2018-11-15 14:04:20", "Heure", null, null)');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE time_price DROP FOREIGN KEY FK_8FBAC3A5E308AC6F');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479AC47EFAC');
        $this->addSql('ALTER TABLE unit_time_price DROP FOREIGN KEY FK_8C125565517FE9FE');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479296CD8AE');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type DROP FOREIGN KEY FK_A59974921C28142E');
        $this->addSql('ALTER TABLE time_price DROP FOREIGN KEY FK_8FBAC3A589329D25');
        $this->addSql('ALTER TABLE time_price DROP FOREIGN KEY FK_8FBAC3A5251838C9');
        $this->addSql('ALTER TABLE business_case DROP FOREIGN KEY FK_28939B09A76ED395');
        $this->addSql('ALTER TABLE unit_time_price_construction_site_type DROP FOREIGN KEY FK_A599749269BC063B');
        $this->addSql('ALTER TABLE corporation_employee DROP FOREIGN KEY FK_5122C6166054F893');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09F2E96AF0');
        $this->addSql('ALTER TABLE corporation_employee DROP FOREIGN KEY FK_5122C616FD15998D');
        $this->addSql('ALTER TABLE business_case DROP FOREIGN KEY FK_28939B099395C3F3');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09FD54954B');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE job_status');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE unit_time_price');
        $this->addSql('DROP TABLE unit_time_price_construction_site_type');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE time_price');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE construction_site_type');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE corporation_job_status');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE corporation_employee');
        $this->addSql('DROP TABLE postal_address');
        $this->addSql('DROP TABLE business_case');
    }
}
