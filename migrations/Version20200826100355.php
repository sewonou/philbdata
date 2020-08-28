<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826100355 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pointofsale ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pointofsale ADD CONSTRAINT FK_B310EDD7F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B310EDD7F675F31B ON pointofsale (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pointofsale DROP FOREIGN KEY FK_B310EDD7F675F31B');
        $this->addSql('DROP INDEX IDX_B310EDD7F675F31B ON pointofsale');
        $this->addSql('ALTER TABLE pointofsale DROP author_id');
    }
}
