<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170328124109ItemAsObjectInHistory extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buy_history DROP FOREIGN KEY FK_198FEB18126F525E');
        $this->addSql('DROP INDEX IDX_198FEB18126F525E ON buy_history');
        $this->addSql('ALTER TABLE buy_history ADD item LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', DROP item_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buy_history ADD item_id INT DEFAULT NULL, DROP item');
        $this->addSql('ALTER TABLE buy_history ADD CONSTRAINT FK_198FEB18126F525E FOREIGN KEY (item_id) REFERENCES shopcart_item (id)');
        $this->addSql('CREATE INDEX IDX_198FEB18126F525E ON buy_history (item_id)');
    }
}
