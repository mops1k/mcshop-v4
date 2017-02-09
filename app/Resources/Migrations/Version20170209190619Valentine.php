<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170209190619Valentine extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE commentary (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, news_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_1CAC12CAA76ED395 (user_id), INDEX IDX_1CAC12CAB5A459A0 (news_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, less_content LONGTEXT DEFAULT NULL, full_content LONGTEXT NOT NULL, createdAt DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_57698A6A5E237E06 (name), UNIQUE INDEX UNIQ_57698A6A57698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_role (role_source INT NOT NULL, role_target INT NOT NULL, INDEX IDX_E9D6F8FEF4AC9EC2 (role_source), INDEX IDX_E9D6F8FEED49CE4D (role_target), PRIMARY KEY(role_source, role_target)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, value VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, kind INT NOT NULL, UNIQUE INDEX UNIQ_5F37A13B1D775834 (value), INDEX IDX_5F37A13BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', access_token CHAR(32) DEFAULT NULL, server_id VARCHAR(41) DEFAULT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) DEFAULT NULL, locked TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, skin_as_avatar TINYINT(1) NOT NULL, hd_buy_date DATETIME DEFAULT NULL, hd_days INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6498FFBE0F7 (salt), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, user_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, show_in_menu TINYINT(1) NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_140AB620989D9B62 (slug), INDEX IDX_140AB620D60322AC (role_id), INDEX IDX_140AB620A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, cache_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, host VARCHAR(255) NOT NULL, port INT DEFAULT 25565, rcon_port INT DEFAULT NULL, rcon_password VARCHAR(255) DEFAULT NULL, shopping_cart_id VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_5A6DD5F645F80CD (shopping_cart_id), UNIQUE INDEX UNIQ_5A6DD5F6A45D650B (cache_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_cache (id INT AUTO_INCREMENT NOT NULL, ping DOUBLE PRECISION DEFAULT NULL, version VARCHAR(255) DEFAULT NULL, protocol INT DEFAULT NULL, players INT DEFAULT 0, maxPlayers INT DEFAULT 0, description VARCHAR(255) DEFAULT NULL, favicon VARCHAR(255) DEFAULT NULL, modinfo TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon (id INT AUTO_INCREMENT NOT NULL, activated_by_id INT DEFAULT NULL, code VARCHAR(16) NOT NULL, amount INT NOT NULL, active TINYINT(1) NOT NULL, dueDate DATETIME DEFAULT NULL, activated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_64BF3F0277153098 (code), INDEX IDX_64BF3F02E00EB9A0 (activated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purse (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, real_cash DOUBLE PRECISION NOT NULL, game_cash DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_DAE44A0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, purse_id INT DEFAULT NULL, status SMALLINT NOT NULL, cash DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EAA81A4C1A429CB3 (purse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentary ADD CONSTRAINT FK_1CAC12CAB5A459A0 FOREIGN KEY (news_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE role_role ADD CONSTRAINT FK_E9D6F8FEF4AC9EC2 FOREIGN KEY (role_source) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_role ADD CONSTRAINT FK_E9D6F8FEED49CE4D FOREIGN KEY (role_target) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F6A45D650B FOREIGN KEY (cache_id) REFERENCES server_cache (id)');
        $this->addSql('ALTER TABLE coupon ADD CONSTRAINT FK_64BF3F02E00EB9A0 FOREIGN KEY (activated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE purse ADD CONSTRAINT FK_DAE44A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C1A429CB3 FOREIGN KEY (purse_id) REFERENCES purse (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAB5A459A0');
        $this->addSql('ALTER TABLE role_role DROP FOREIGN KEY FK_E9D6F8FEF4AC9EC2');
        $this->addSql('ALTER TABLE role_role DROP FOREIGN KEY FK_E9D6F8FEED49CE4D');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620D60322AC');
        $this->addSql('ALTER TABLE commentary DROP FOREIGN KEY FK_1CAC12CAA76ED395');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620A76ED395');
        $this->addSql('ALTER TABLE coupon DROP FOREIGN KEY FK_64BF3F02E00EB9A0');
        $this->addSql('ALTER TABLE purse DROP FOREIGN KEY FK_DAE44A0A76ED395');
        $this->addSql('ALTER TABLE server DROP FOREIGN KEY FK_5A6DD5F6A45D650B');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C1A429CB3');
        $this->addSql('DROP TABLE commentary');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_role');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE server');
        $this->addSql('DROP TABLE server_cache');
        $this->addSql('DROP TABLE coupon');
        $this->addSql('DROP TABLE purse');
        $this->addSql('DROP TABLE transactions');
    }
}
