<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915143910 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE balance (id BIGINT AUTO_INCREMENT NOT NULL, msisdn_id INT DEFAULT NULL, execution_at DATETIME DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_ACF41FFED429DFE8 (msisdn_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, config_at DATETIME DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_D48A2F7CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE config_file (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, config_id INT DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, update_at DATETIME DEFAULT NULL, is_upload TINYINT(1) NOT NULL, is_load TINYINT(1) DEFAULT NULL, INDEX IDX_1C3F6AE012469DE2 (category_id), INDEX IDX_1C3F6AE024DB0683 (config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE control (id INT AUTO_INCREMENT NOT NULL, trader_id INT DEFAULT NULL, pointofsale_id INT DEFAULT NULL, start_at DATETIME DEFAULT NULL, end_at DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, INDEX IDX_EDDB2C4B1273968F (trader_id), INDEX IDX_EDDB2C4B18E07BF3 (pointofsale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE district (id INT AUTO_INCREMENT NOT NULL, township_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_31C15487B093DF6 (township_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_category (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(155) DEFAULT NULL, slug VARCHAR(15) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_B71C965CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE master_sim (id INT AUTO_INCREMENT NOT NULL, msisdn VARCHAR(25) NOT NULL, name VARCHAR(155) NOT NULL, update_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pointofsale (id INT AUTO_INCREMENT NOT NULL, msisdn_id INT DEFAULT NULL, district_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, activity VARCHAR(190) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, longitude VARCHAR(120) DEFAULT NULL, latitude VARCHAR(120) DEFAULT NULL, contact VARCHAR(15) DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, update_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B310EDD7D429DFE8 (msisdn_id), INDEX IDX_B310EDD7B08FA272 (district_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prefecture (id INT AUTO_INCREMENT NOT NULL, town_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_ABE6511A75E23604 (town_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, zone_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_F62F1769F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(85) DEFAULT NULL, description VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id BIGINT AUTO_INCREMENT NOT NULL, msisdn_id INT DEFAULT NULL, type_id INT DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, d_comm DOUBLE PRECISION DEFAULT NULL, pos_comm DOUBLE PRECISION DEFAULT NULL, transaction_at DATETIME DEFAULT NULL, ref_id BIGINT DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_E54BC005D429DFE8 (msisdn_id), INDEX IDX_E54BC005C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sim_card (id INT AUTO_INCREMENT NOT NULL, profile_id INT DEFAULT NULL, master_id INT DEFAULT NULL, msisdn VARCHAR(25) NOT NULL, is_active TINYINT(1) NOT NULL, update_at DATETIME NOT NULL, INDEX IDX_60AA437DCCFA12B8 (profile_id), INDEX IDX_60AA437D13B3DB11 (master_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_4CE6C7A498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE township (id INT AUTO_INCREMENT NOT NULL, prefecture_id INT DEFAULT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_DB97BC629D39C865 (prefecture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trade (id BIGINT AUTO_INCREMENT NOT NULL, from_msisdn_id INT DEFAULT NULL, to_msisdn_id INT DEFAULT NULL, type_id INT DEFAULT NULL, ref_id BIGINT DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, transaction_at DATETIME DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_7E1A4366C4B54A52 (from_msisdn_id), INDEX IDX_7E1A4366E58A0899 (to_msisdn_id), INDEX IDX_7E1A4366C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trader (id INT AUTO_INCREMENT NOT NULL, msisdn_id INT DEFAULT NULL, region_id INT DEFAULT NULL, full_name VARCHAR(255) DEFAULT NULL, picture_name VARCHAR(255) DEFAULT NULL, is_trader TINYINT(1) DEFAULT NULL, update_at DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, msisdn_name VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C8A621B3D429DFE8 (msisdn_id), INDEX IDX_C8A621B398260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(15) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, login VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, office VARCHAR(255) DEFAULT NULL, picture_name VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(155) DEFAULT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE balance ADD CONSTRAINT FK_ACF41FFED429DFE8 FOREIGN KEY (msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE config_file ADD CONSTRAINT FK_1C3F6AE012469DE2 FOREIGN KEY (category_id) REFERENCES file_category (id)');
        $this->addSql('ALTER TABLE config_file ADD CONSTRAINT FK_1C3F6AE024DB0683 FOREIGN KEY (config_id) REFERENCES config (id)');
        $this->addSql('ALTER TABLE control ADD CONSTRAINT FK_EDDB2C4B1273968F FOREIGN KEY (trader_id) REFERENCES trader (id)');
        $this->addSql('ALTER TABLE control ADD CONSTRAINT FK_EDDB2C4B18E07BF3 FOREIGN KEY (pointofsale_id) REFERENCES pointofsale (id)');
        $this->addSql('ALTER TABLE district ADD CONSTRAINT FK_31C15487B093DF6 FOREIGN KEY (township_id) REFERENCES township (id)');
        $this->addSql('ALTER TABLE file_category ADD CONSTRAINT FK_B71C965CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pointofsale ADD CONSTRAINT FK_B310EDD7D429DFE8 FOREIGN KEY (msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE pointofsale ADD CONSTRAINT FK_B310EDD7B08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE prefecture ADD CONSTRAINT FK_ABE6511A75E23604 FOREIGN KEY (town_id) REFERENCES town (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F1769F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005D429DFE8 FOREIGN KEY (msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE sim_card ADD CONSTRAINT FK_60AA437DCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE sim_card ADD CONSTRAINT FK_60AA437D13B3DB11 FOREIGN KEY (master_id) REFERENCES master_sim (id)');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE township ADD CONSTRAINT FK_DB97BC629D39C865 FOREIGN KEY (prefecture_id) REFERENCES prefecture (id)');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366C4B54A52 FOREIGN KEY (from_msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366E58A0899 FOREIGN KEY (to_msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE trader ADD CONSTRAINT FK_C8A621B3D429DFE8 FOREIGN KEY (msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE trader ADD CONSTRAINT FK_C8A621B398260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config_file DROP FOREIGN KEY FK_1C3F6AE024DB0683');
        $this->addSql('ALTER TABLE pointofsale DROP FOREIGN KEY FK_B310EDD7B08FA272');
        $this->addSql('ALTER TABLE config_file DROP FOREIGN KEY FK_1C3F6AE012469DE2');
        $this->addSql('ALTER TABLE sim_card DROP FOREIGN KEY FK_60AA437D13B3DB11');
        $this->addSql('ALTER TABLE control DROP FOREIGN KEY FK_EDDB2C4B18E07BF3');
        $this->addSql('ALTER TABLE township DROP FOREIGN KEY FK_DB97BC629D39C865');
        $this->addSql('ALTER TABLE sim_card DROP FOREIGN KEY FK_60AA437DCCFA12B8');
        $this->addSql('ALTER TABLE town DROP FOREIGN KEY FK_4CE6C7A498260155');
        $this->addSql('ALTER TABLE trader DROP FOREIGN KEY FK_C8A621B398260155');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE balance DROP FOREIGN KEY FK_ACF41FFED429DFE8');
        $this->addSql('ALTER TABLE pointofsale DROP FOREIGN KEY FK_B310EDD7D429DFE8');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005D429DFE8');
        $this->addSql('ALTER TABLE trade DROP FOREIGN KEY FK_7E1A4366C4B54A52');
        $this->addSql('ALTER TABLE trade DROP FOREIGN KEY FK_7E1A4366E58A0899');
        $this->addSql('ALTER TABLE trader DROP FOREIGN KEY FK_C8A621B3D429DFE8');
        $this->addSql('ALTER TABLE prefecture DROP FOREIGN KEY FK_ABE6511A75E23604');
        $this->addSql('ALTER TABLE district DROP FOREIGN KEY FK_31C15487B093DF6');
        $this->addSql('ALTER TABLE control DROP FOREIGN KEY FK_EDDB2C4B1273968F');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005C54C8C93');
        $this->addSql('ALTER TABLE trade DROP FOREIGN KEY FK_7E1A4366C54C8C93');
        $this->addSql('ALTER TABLE config DROP FOREIGN KEY FK_D48A2F7CF675F31B');
        $this->addSql('ALTER TABLE file_category DROP FOREIGN KEY FK_B71C965CF675F31B');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F1769F2C3FAB');
        $this->addSql('DROP TABLE balance');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE config_file');
        $this->addSql('DROP TABLE control');
        $this->addSql('DROP TABLE district');
        $this->addSql('DROP TABLE file_category');
        $this->addSql('DROP TABLE master_sim');
        $this->addSql('DROP TABLE pointofsale');
        $this->addSql('DROP TABLE prefecture');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE sim_card');
        $this->addSql('DROP TABLE town');
        $this->addSql('DROP TABLE township');
        $this->addSql('DROP TABLE trade');
        $this->addSql('DROP TABLE trader');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE zone');
    }
}
