<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260112231939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE episode (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, summary CLOB DEFAULT NULL, description CLOB DEFAULT NULL, published DATETIME DEFAULT NULL, duration INTEGER DEFAULT NULL, episode_nr INTEGER DEFAULT NULL, series_id INTEGER NOT NULL, CONSTRAINT FK_DDAA1CDA5278319C FOREIGN KEY (series_id) REFERENCES series (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_DDAA1CDA5278319C ON episode (series_id)');
        $this->addSql('CREATE TABLE series (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__media AS SELECT id, uuid, created, url, type, size, duration, path, episode_id FROM media');
        $this->addSql('DROP TABLE media');
        $this->addSql('CREATE TABLE media (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid VARCHAR(80) NOT NULL, created DATETIME NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, size INTEGER DEFAULT NULL, duration INTEGER DEFAULT NULL, path VARCHAR(255) NOT NULL, episode_id INTEGER DEFAULT NULL, CONSTRAINT FK_6A2CA10C362B62A0 FOREIGN KEY (episode_id) REFERENCES podcast_episode (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO media (id, uuid, created, url, type, size, duration, path, episode_id) SELECT id, uuid, created, url, type, size, duration, path, episode_id FROM __temp__media');
        $this->addSql('DROP TABLE __temp__media');
        $this->addSql('CREATE INDEX IDX_6A2CA10C362B62A0 ON media (episode_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A2CA10CD17F50A6 ON media (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE series');
        $this->addSql('CREATE TEMPORARY TABLE __temp__media AS SELECT id, uuid, created, url, type, size, duration, path, episode_id FROM media');
        $this->addSql('DROP TABLE media');
        $this->addSql('CREATE TABLE media (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid VARCHAR(80) NOT NULL, created DATETIME NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(50) DEFAULT NULL, size INTEGER DEFAULT NULL, duration INTEGER DEFAULT NULL, path VARCHAR(255) NOT NULL, episode_id INTEGER DEFAULT NULL, CONSTRAINT FK_6A2CA10C362B62A0 FOREIGN KEY (episode_id) REFERENCES podcast_episode (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO media (id, uuid, created, url, type, size, duration, path, episode_id) SELECT id, uuid, created, url, type, size, duration, path, episode_id FROM __temp__media');
        $this->addSql('DROP TABLE __temp__media');
        $this->addSql('CREATE INDEX IDX_6A2CA10C362B62A0 ON media (episode_id)');
    }
}
