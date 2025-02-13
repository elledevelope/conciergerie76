<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213093318 extends AbstractMigration
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
        $this->addSql('DROP INDEX UNIQ_8933C43267B3B43D ON favoris');
        $this->addSql('DROP INDEX IDX_8933C432AEF5A6C1 ON favoris');
        $this->addSql('ALTER TABLE favoris ADD user_id INT NOT NULL, ADD service_id INT NOT NULL, DROP users_id, DROP services_id');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_8933C432A76ED395 ON favoris (user_id)');
        $this->addSql('CREATE INDEX IDX_8933C432ED5CA9E6 ON favoris (service_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432A76ED395');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432ED5CA9E6');
        $this->addSql('DROP INDEX IDX_8933C432A76ED395 ON favoris');
        $this->addSql('DROP INDEX IDX_8933C432ED5CA9E6 ON favoris');
        $this->addSql('ALTER TABLE favoris ADD users_id INT DEFAULT NULL, ADD services_id INT DEFAULT NULL, DROP user_id, DROP service_id');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43267B3B43D FOREIGN KEY (users_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432AEF5A6C1 FOREIGN KEY (services_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8933C43267B3B43D ON favoris (users_id)');
        $this->addSql('CREATE INDEX IDX_8933C432AEF5A6C1 ON favoris (services_id)');
    }
}
