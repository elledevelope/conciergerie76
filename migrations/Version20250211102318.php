<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211102318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, objet VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drinking_water_node (id INT AUTO_INCREMENT NOT NULL, lat DOUBLE PRECISION NOT NULL, lon DOUBLE PRECISION NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955ED5CA9E6');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('ALTER TABLE service ADD url VARCHAR(500) DEFAULT NULL, DROP description, DROP price, DROP duration, DROP address, DROP phone, DROP website, DROP is_available, DROP opening_hours, DROP cuisine, DROP takeaway, DROP delivery, DROP average_price, DROP has_atm, DROP has_parcel_service, DROP waiting_time, DROP total_spaces, DROP available_spaces, DROP has_electric_charging, DROP has_handicap_spaces, DROP parking_type, DROP image_url, CHANGE latitude latitude DOUBLE PRECISION NOT NULL, CHANGE longitude longitude DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE user DROP first_name, DROP last_name, DROP phone, DROP reset_token');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_IDENTIFIER_EMAIL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, service_id INT NOT NULL, date_time DATETIME NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, notes LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C84955ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE drinking_water_node');
        $this->addSql('DROP TABLE place');
        $this->addSql('ALTER TABLE service ADD description LONGTEXT NOT NULL, ADD price DOUBLE PRECISION NOT NULL, ADD duration INT NOT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD website VARCHAR(255) DEFAULT NULL, ADD is_available TINYINT(1) NOT NULL, ADD opening_hours JSON DEFAULT NULL, ADD cuisine VARCHAR(255) DEFAULT NULL, ADD takeaway TINYINT(1) DEFAULT NULL, ADD delivery TINYINT(1) DEFAULT NULL, ADD average_price INT DEFAULT NULL, ADD has_atm TINYINT(1) DEFAULT NULL, ADD has_parcel_service TINYINT(1) DEFAULT NULL, ADD waiting_time INT DEFAULT NULL, ADD total_spaces INT DEFAULT NULL, ADD available_spaces INT DEFAULT NULL, ADD has_electric_charging TINYINT(1) DEFAULT NULL, ADD has_handicap_spaces TINYINT(1) DEFAULT NULL, ADD parking_type VARCHAR(255) DEFAULT NULL, ADD image_url VARCHAR(255) DEFAULT NULL, DROP url, CHANGE latitude latitude NUMERIC(10, 8) NOT NULL, CHANGE longitude longitude NUMERIC(11, 8) NOT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD reset_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_identifier_email TO UNIQ_8D93D649E7927C74');
    }
}
