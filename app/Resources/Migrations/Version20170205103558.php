<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170205103558 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE server_cache (id INT AUTO_INCREMENT NOT NULL, ping DOUBLE PRECISION DEFAULT NULL, version VARCHAR(255) DEFAULT NULL, protocol INT DEFAULT NULL, players INT DEFAULT 0 NOT NULL, maxPlayers INT DEFAULT 0 NOT NULL, description VARCHAR(255) DEFAULT NULL, favicon VARCHAR(255) DEFAULT NULL, modinfo TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE server ADD cache_id INT DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6A45D650B FOREIGN KEY (cache_id) REFERENCES server_cache (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A6DD5F6A45D650B ON server (cache_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6A45D650B');
        $this->addSql('DROP TABLE server_cache');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('DROP INDEX UNIQ_5A6DD5F6A45D650B ON server');
        $this->addSql('ALTER TABLE server DROP cache_id, DROP deleted_at');
    }
}
