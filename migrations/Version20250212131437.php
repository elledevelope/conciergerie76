<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212131437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43267B3B43D');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432AEF5A6C1');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD datedenaissance DATETIME NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD avatar VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, services_id INT DEFAULT NULL, INDEX IDX_8933C432AEF5A6C1 (services_id), UNIQUE INDEX UNIQ_8933C43267B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43267B3B43D FOREIGN KEY (users_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432AEF5A6C1 FOREIGN KEY (services_id) REFERENCES service (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP datedenaissance, DROP adresse, DROP avatar');
    }
}
