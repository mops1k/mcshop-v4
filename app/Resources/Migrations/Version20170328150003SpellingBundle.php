<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170328150003SpellingBundle extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spelling (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, corrector_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, prefix LONGTEXT DEFAULT NULL, error LONGTEXT NOT NULL, suffix LONGTEXT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, corrected TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_B130975261220EA6 (creator_id), INDEX IDX_B13097523A6E8746 (corrector_id), INDEX corrected_idx (corrected), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spelling_security (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(45) NOT NULL, last_query DATETIME NOT NULL, count INT NOT NULL, error_time DATETIME DEFAULT NULL, banned_until DATETIME DEFAULT NULL, INDEX last_query_idx (last_query), INDEX banned_until_idx (banned_until), UNIQUE INDEX ip_idx (ip), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spelling ADD CONSTRAINT FK_B130975261220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE spelling ADD CONSTRAINT FK_B13097523A6E8746 FOREIGN KEY (corrector_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE spelling');
        $this->addSql('DROP TABLE spelling_security');
    }
}
