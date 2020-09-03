<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200831142906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE control (id INT AUTO_INCREMENT NOT NULL, trader_id INT DEFAULT NULL, pointofsale_id INT DEFAULT NULL, start_at DATETIME DEFAULT NULL, end_at DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, INDEX IDX_EDDB2C4B1273968F (trader_id), INDEX IDX_EDDB2C4B18E07BF3 (pointofsale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE control ADD CONSTRAINT FK_EDDB2C4B1273968F FOREIGN KEY (trader_id) REFERENCES trader (id)');
        $this->addSql('ALTER TABLE control ADD CONSTRAINT FK_EDDB2C4B18E07BF3 FOREIGN KEY (pointofsale_id) REFERENCES pointofsale (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE control');
    }
}
