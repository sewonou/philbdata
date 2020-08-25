<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821095652 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, file_id INT DEFAULT NULL, author_id INT DEFAULT NULL, config_at DATETIME DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_D48A2F7C93CB796C (file_id), INDEX IDX_D48A2F7CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config_file (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, author_id INT DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_1C3F6AE012469DE2 (category_id), INDEX IDX_1C3F6AE0F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_category (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(155) DEFAULT NULL, slug VARCHAR(15) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_B71C965CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE master_sim (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, msisdn VARCHAR(25) NOT NULL, name VARCHAR(155) NOT NULL, update_at DATETIME NOT NULL, INDEX IDX_2FBD39AAF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_8157AA0FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sim_card (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, profile_id INT DEFAULT NULL, master_id INT DEFAULT NULL, msisdn VARCHAR(25) NOT NULL, is_active TINYINT(1) NOT NULL, update_at DATETIME NOT NULL, INDEX IDX_60AA437DF675F31B (author_id), INDEX IDX_60AA437DCCFA12B8 (profile_id), INDEX IDX_60AA437D13B3DB11 (master_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C93CB796C FOREIGN KEY (file_id) REFERENCES config_file (id)');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE config_file ADD CONSTRAINT FK_1C3F6AE012469DE2 FOREIGN KEY (category_id) REFERENCES file_category (id)');
        $this->addSql('ALTER TABLE config_file ADD CONSTRAINT FK_1C3F6AE0F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file_category ADD CONSTRAINT FK_B71C965CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE master_sim ADD CONSTRAINT FK_2FBD39AAF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sim_card ADD CONSTRAINT FK_60AA437DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sim_card ADD CONSTRAINT FK_60AA437DCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE sim_card ADD CONSTRAINT FK_60AA437D13B3DB11 FOREIGN KEY (master_id) REFERENCES master_sim (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config DROP FOREIGN KEY FK_D48A2F7C93CB796C');
        $this->addSql('ALTER TABLE config_file DROP FOREIGN KEY FK_1C3F6AE012469DE2');
        $this->addSql('ALTER TABLE sim_card DROP FOREIGN KEY FK_60AA437D13B3DB11');
        $this->addSql('ALTER TABLE sim_card DROP FOREIGN KEY FK_60AA437DCCFA12B8');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE config_file');
        $this->addSql('DROP TABLE file_category');
        $this->addSql('DROP TABLE master_sim');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE sim_card');
    }
}
