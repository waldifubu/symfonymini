<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909183126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE episode (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, series_id INTEGER NOT NULL, title VARCHAR(255) DEFAULT NULL, summary CLOB DEFAULT NULL, description CLOB DEFAULT NULL, published DATETIME DEFAULT NULL, duration INTEGER DEFAULT NULL, episode_nr INTEGER DEFAULT NULL, CONSTRAINT FK_DDAA1CDA5278319C FOREIGN KEY (series_id) REFERENCES series (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA5278319C ON episode (series_id)');
        $this->addSql('CREATE TABLE series (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL)');
        /*
         * CREATE TABLE series (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL);
CREATE TEMPORARY TABLE __temp__podcast_series AS SELECT id, title, description, author, locked, explicit, copyright, language, created, cover, type, owner, keywords, blocked, complete, main_category, sub_category, ttl, frequency, published, owner_email, last_build_date, uuid FROM podcast_series;
DROP TABLE podcast_series;
CREATE TABLE podcast_series (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, locked BOOLEAN NOT NULL, explicit BOOLEAN NOT NULL, copyright VARCHAR(255) NOT NULL, language VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL --(DC2Type:datetime_immutable)
, cover VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, owner VARCHAR(255) DEFAULT NULL, keywords VARCHAR(255) DEFAULT NULL, blocked BOOLEAN DEFAULT NULL, complete BOOLEAN DEFAULT NULL, main_category VARCHAR(255) DEFAULT NULL, sub_category VARCHAR(255) DEFAULT NULL, ttl INTEGER DEFAULT NULL, frequency VARCHAR(100) DEFAULT NULL, published VARCHAR(255) DEFAULT NULL, owner_email VARCHAR(255) DEFAULT NULL, last_build_date VARCHAR(255) DEFAULT NULL, uuid BLOB DEFAULT NULL --(DC2Type:uuid)
);
INSERT INTO podcast_series (id, title, description, author, locked, explicit, copyright, language, created, cover, type, owner, keywords, blocked, complete, main_category, sub_category, ttl, frequency, published, owner_email, last_build_date, uuid) SELECT id, title, description, author, locked, explicit, copyright, language, created, cover, type, owner, keywords, blocked, complete, main_category, sub_category, ttl, frequency, published, owner_email, last_build_date, uuid FROM __temp__podcast_series;
DROP TABLE __temp__podcast_series;
         */
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE series');
    }
}
