<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814193208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE district (id INT AUTO_INCREMENT NOT NULL, township_id INT DEFAULT NULL, author_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_31C15487B093DF6 (township_id), INDEX IDX_31C15487F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefecture (id INT AUTO_INCREMENT NOT NULL, town_id INT DEFAULT NULL, author_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_ABE6511A75E23604 (town_id), INDEX IDX_ABE6511AF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, zone_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_F62F176F675F31B (author_id), INDEX IDX_F62F1769F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, author_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_4CE6C7A498260155 (region_id), INDEX IDX_4CE6C7A4F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE township (id INT AUTO_INCREMENT NOT NULL, prefecture_id INT DEFAULT NULL, author_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_DB97BC629D39C865 (prefecture_id), INDEX IDX_DB97BC62F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_A0EBC007F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE district ADD CONSTRAINT FK_31C15487B093DF6 FOREIGN KEY (township_id) REFERENCES township (id)');
        $this->addSql('ALTER TABLE district ADD CONSTRAINT FK_31C15487F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prefecture ADD CONSTRAINT FK_ABE6511A75E23604 FOREIGN KEY (town_id) REFERENCES town (id)');
        $this->addSql('ALTER TABLE prefecture ADD CONSTRAINT FK_ABE6511AF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F1769F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A4F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE township ADD CONSTRAINT FK_DB97BC629D39C865 FOREIGN KEY (prefecture_id) REFERENCES prefecture (id)');
        $this->addSql('ALTER TABLE township ADD CONSTRAINT FK_DB97BC62F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE zone ADD CONSTRAINT FK_A0EBC007F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE township DROP FOREIGN KEY FK_DB97BC629D39C865');
        $this->addSql('ALTER TABLE town DROP FOREIGN KEY FK_4CE6C7A498260155');
        $this->addSql('ALTER TABLE prefecture DROP FOREIGN KEY FK_ABE6511A75E23604');
        $this->addSql('ALTER TABLE district DROP FOREIGN KEY FK_31C15487B093DF6');
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F1769F2C3FAB');
        $this->addSql('DROP TABLE district');
        $this->addSql('DROP TABLE prefecture');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE town');
        $this->addSql('DROP TABLE township');
        $this->addSql('DROP TABLE zone');
    }
}
