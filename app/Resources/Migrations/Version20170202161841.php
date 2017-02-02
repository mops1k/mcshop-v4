<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170202161841 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, user_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, show_in_menu TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_140AB620989D9B62 (slug), INDEX IDX_140AB620D60322AC (role_id), INDEX IDX_140AB620A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE page ADD deleted_at DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE page');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
    }
}
