<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170328123512BuyHistory extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE buy_history (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, user_id INT DEFAULT NULL, amount INT NOT NULL, buy_at DATETIME NOT NULL, INDEX IDX_198FEB18126F525E (item_id), INDEX IDX_198FEB18A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buy_history ADD CONSTRAINT FK_198FEB18126F525E FOREIGN KEY (item_id) REFERENCES shopcart_item (id)');
        $this->addSql('ALTER TABLE buy_history ADD CONSTRAINT FK_198FEB18A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE buy_history');
    }
}
