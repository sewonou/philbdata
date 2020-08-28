<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826185937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE balance (id INT AUTO_INCREMENT NOT NULL, msisdn_id INT DEFAULT NULL, author_id INT DEFAULT NULL, execution_at DATETIME DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_ACF41FFED429DFE8 (msisdn_id), INDEX IDX_ACF41FFEF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, msisdn_id INT DEFAULT NULL, type_id INT DEFAULT NULL, author_id INT DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, d_comm DOUBLE PRECISION DEFAULT NULL, pos_comm DOUBLE PRECISION DEFAULT NULL, transaction_at DATETIME DEFAULT NULL, ref_id BIGINT DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_E54BC005D429DFE8 (msisdn_id), INDEX IDX_E54BC005C54C8C93 (type_id), INDEX IDX_E54BC005F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trade (id INT AUTO_INCREMENT NOT NULL, from_msisdn_id INT DEFAULT NULL, to_msisdn_id INT DEFAULT NULL, type_id INT DEFAULT NULL, author_id INT DEFAULT NULL, ref_id BIGINT DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, transaction_at DATETIME DEFAULT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_7E1A4366C4B54A52 (from_msisdn_id), INDEX IDX_7E1A4366E58A0899 (to_msisdn_id), INDEX IDX_7E1A4366C54C8C93 (type_id), INDEX IDX_7E1A4366F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(15) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE balance ADD CONSTRAINT FK_ACF41FFED429DFE8 FOREIGN KEY (msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE balance ADD CONSTRAINT FK_ACF41FFEF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005D429DFE8 FOREIGN KEY (msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366C4B54A52 FOREIGN KEY (from_msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366E58A0899 FOREIGN KEY (to_msisdn_id) REFERENCES sim_card (id)');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A4366F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005C54C8C93');
        $this->addSql('ALTER TABLE trade DROP FOREIGN KEY FK_7E1A4366C54C8C93');
        $this->addSql('DROP TABLE balance');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE trade');
        $this->addSql('DROP TABLE type');
    }
}
