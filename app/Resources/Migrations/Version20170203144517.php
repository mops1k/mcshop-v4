<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170203144517 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE purse (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, real_cash DOUBLE PRECISION NOT NULL, game_cash DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_DAE44A0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, purse_id INT DEFAULT NULL, status SMALLINT NOT NULL, cash DOUBLE PRECISION NOT NULL, INDEX IDX_EAA81A4C1A429CB3 (purse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purse ADD CONSTRAINT FK_DAE44A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transactions ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C1A429CB3 FOREIGN KEY (purse_id) REFERENCES purse (id)');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C1A429CB3');
        $this->addSql('DROP TABLE purse');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
    }
}
