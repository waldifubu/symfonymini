<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112235038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE series');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE episode (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL COLLATE "BINARY", summary CLOB DEFAULT NULL COLLATE "BINARY", description CLOB DEFAULT NULL COLLATE "BINARY", published DATETIME DEFAULT NULL, duration INTEGER DEFAULT NULL, episode_nr INTEGER DEFAULT NULL, series_id INTEGER NOT NULL, CONSTRAINT FK_DDAA1CDA5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA5278319C ON episode (series_id)');
        $this->addSql('CREATE TABLE series (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE "BINARY", author VARCHAR(255) DEFAULT NULL COLLATE "BINARY")');
    }
}
