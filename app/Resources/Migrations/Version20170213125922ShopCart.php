<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170213125922ShopCart extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shopcart (id BIGINT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT \'item\' NOT NULL COLLATE latin1_swedish_ci, item VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, player VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, amount INT NOT NULL, extra VARCHAR(255) DEFAULT NULL COLLATE latin1_swedish_ci, server VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, INDEX shopcart_server_idx (server), INDEX shopcart_player_idx (player), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE shopcart');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
    }
}
