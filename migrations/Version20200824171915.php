<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200824171915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pointofsale (id INT AUTO_INCREMENT NOT NULL, msisdn_id INT DEFAULT NULL, district_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, activity VARCHAR(190) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, longitude VARCHAR(120) NOT NULL, latitude VARCHAR(120) DEFAULT NULL, contact VARCHAR(15) NOT NULL, is_active TINYINT(1) DEFAULT NULL, update_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B310EDD7D429DFE8 (msisdn_id), INDEX IDX_B310EDD7B08FA272 (district_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trader (id INT AUTO_INCREMENT NOT NULL, msisdn_id INT DEFAULT NULL, region_id INT DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, picture_name VARCHAR(255) DEFAULT NULL, is_trader TINYINT(1) DEFAULT NULL, update_at DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_C8A621B3D429DFE8 (msisdn_id), INDEX IDX_C8A621B398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pointofsale ADD CONSTRAINT FK_B310EDD7D429DFE8 FOREIGN KEY (msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE pointofsale ADD CONSTRAINT FK_B310EDD7B08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE trader ADD CONSTRAINT FK_C8A621B3D429DFE8 FOREIGN KEY (msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE trader ADD CONSTRAINT FK_C8A621B398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE config DROP FOREIGN KEY FK_D48A2F7C93CB796C');
        $this->addSql('DROP INDEX IDX_D48A2F7C93CB796C ON config');
        $this->addSql('ALTER TABLE config DROP file_id');
        $this->addSql('ALTER TABLE config_file DROP FOREIGN KEY FK_1C3F6AE0F675F31B');
        $this->addSql('DROP INDEX IDX_1C3F6AE0F675F31B ON config_file');
        $this->addSql('ALTER TABLE config_file CHANGE author_id config_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE config_file ADD CONSTRAINT FK_1C3F6AE024DB0683 FOREIGN KEY (config_id) REFERENCES config (id)');
        $this->addSql('CREATE INDEX IDX_1C3F6AE024DB0683 ON config_file (config_id)');
        $this->addSql('ALTER TABLE sim_card ADD name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pointofsale');
        $this->addSql('DROP TABLE trader');
        $this->addSql('ALTER TABLE config ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C93CB796C FOREIGN KEY (file_id) REFERENCES config_file (id)');
        $this->addSql('CREATE INDEX IDX_D48A2F7C93CB796C ON config (file_id)');
        $this->addSql('ALTER TABLE config_file DROP FOREIGN KEY FK_1C3F6AE024DB0683');
        $this->addSql('DROP INDEX IDX_1C3F6AE024DB0683 ON config_file');
        $this->addSql('ALTER TABLE config_file CHANGE config_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE config_file ADD CONSTRAINT FK_1C3F6AE0F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1C3F6AE0F675F31B ON config_file (author_id)');
        $this->addSql('ALTER TABLE sim_card DROP name');
    }
}
