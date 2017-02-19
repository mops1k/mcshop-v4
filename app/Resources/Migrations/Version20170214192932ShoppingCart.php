<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170214192932ShoppingCart extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shopcart (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, item VARCHAR(255) NOT NULL, player VARCHAR(255) NOT NULL, amount INT NOT NULL, extra VARCHAR(255) DEFAULT NULL, server VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopcart_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(64) NOT NULL, root INT DEFAULT NULL, lvl INT NOT NULL, lft INT NOT NULL, rgt INT NOT NULL, INDEX IDX_DFED5391727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopcart_item (id INT AUTO_INCREMENT NOT NULL, server_id INT DEFAULT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, item VARCHAR(255) NOT NULL, amount INT NOT NULL, price DOUBLE PRECISION NOT NULL, sale INT NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_5869AD11844E6B7 (server_id), INDEX IDX_5869AD112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopcart_category ADD CONSTRAINT FK_DFED5391727ACA70 FOREIGN KEY (parent_id) REFERENCES shopcart_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shopcart_item ADD CONSTRAINT FK_5869AD11844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE shopcart_item ADD CONSTRAINT FK_5869AD112469DE2 FOREIGN KEY (category_id) REFERENCES shopcart_category (id)');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE shopcart_item ADD extra VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE TABLE basket (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, user_id INT DEFAULT NULL, amount INT NOT NULL, INDEX IDX_2246507B126F525E (item_id), INDEX IDX_2246507BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507B126F525E FOREIGN KEY (item_id) REFERENCES shopcart_item (id)');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shopcart_category DROP FOREIGN KEY FK_DFED5391727ACA70');
        $this->addSql('ALTER TABLE shopcart_item DROP FOREIGN KEY FK_5869AD112469DE2');
        $this->addSql('DROP TABLE shopcart');
        $this->addSql('DROP TABLE shopcart_category');
        $this->addSql('DROP TABLE shopcart_item');
        $this->addSql('DROP TABLE basket');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
    }
}
