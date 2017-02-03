<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170203120736 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE role_role (role_source INT NOT NULL, role_target INT NOT NULL, INDEX IDX_E9D6F8FEF4AC9EC2 (role_source), INDEX IDX_E9D6F8FEED49CE4D (role_target), PRIMARY KEY(role_source, role_target)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role_role ADD CONSTRAINT FK_E9D6F8FEF4AC9EC2 FOREIGN KEY (role_source) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_role ADD CONSTRAINT FK_E9D6F8FEED49CE4D FOREIGN KEY (role_target) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6A727ACA70');
        $this->addSql('DROP INDEX IDX_57698A6A727ACA70 ON role');
        $this->addSql('ALTER TABLE role DROP parent_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE role_role');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
        $this->addSql('ALTER TABLE role ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6A727ACA70 FOREIGN KEY (parent_id) REFERENCES role (id)');
        $this->addSql('CREATE INDEX IDX_57698A6A727ACA70 ON role (parent_id)');
    }
}
