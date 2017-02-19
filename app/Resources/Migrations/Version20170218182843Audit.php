<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170218182843Audit extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post_audit (id INT NOT NULL, rev INT NOT NULL, user_id INT DEFAULT NULL, subject VARCHAR(255) DEFAULT NULL, less_content LONGTEXT DEFAULT NULL, full_content LONGTEXT DEFAULT NULL, createdAt DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_7d2ab6760afca296cbe1bbe3d5f25777_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_audit (id INT NOT NULL, rev INT NOT NULL, uuid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', access_token CHAR(32) DEFAULT NULL, server_id VARCHAR(41) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, salt VARCHAR(255) DEFAULT NULL, locked TINYINT(1) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, skin_as_avatar TINYINT(1) DEFAULT NULL, hd_buy_date DATETIME DEFAULT NULL, hd_days INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_e06395edc291d0719bee26fd39a32e8a_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_audit (id INT NOT NULL, rev INT NOT NULL, role_id INT DEFAULT NULL, user_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, show_in_menu TINYINT(1) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_cb0a2a12940e3e1281c35c60e8ceadcd_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE server_audit (id INT NOT NULL, rev INT NOT NULL, cache_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, host VARCHAR(255) DEFAULT NULL, port INT DEFAULT 25565, rcon_port INT DEFAULT NULL, rcon_password VARCHAR(255) DEFAULT NULL, shopping_cart_id VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_6e8bc1b8d49bea944537ac1bb4d9e320_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupon_audit (id INT NOT NULL, rev INT NOT NULL, activated_by_id INT DEFAULT NULL, code VARCHAR(16) DEFAULT NULL, amount INT DEFAULT NULL, active TINYINT(1) DEFAULT NULL, dueDate DATETIME DEFAULT NULL, activated_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_97626bbb0e9217c199d80d8e3d0e70af_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purse_audit (id INT NOT NULL, rev INT NOT NULL, user_id INT DEFAULT NULL, real_cash DOUBLE PRECISION DEFAULT NULL, game_cash DOUBLE PRECISION DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_532f4ddde925441e5b95d3e2ec5eb5bc_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopcart_audit (id INT NOT NULL, rev INT NOT NULL, type VARCHAR(255) DEFAULT NULL, item VARCHAR(255) DEFAULT NULL, player VARCHAR(255) DEFAULT NULL, amount INT DEFAULT NULL, extra VARCHAR(255) DEFAULT NULL, server VARCHAR(255) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_fcc492c4ecdbbbe7cdc503570645805d_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopcart_category_audit (id INT NOT NULL, rev INT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(64) DEFAULT NULL, root INT DEFAULT NULL, lvl INT DEFAULT NULL, lft INT DEFAULT NULL, rgt INT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_4606f4f8eba266371315b820e6967f69_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopcart_item_audit (id INT NOT NULL, rev INT NOT NULL, server_id INT DEFAULT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, item VARCHAR(255) DEFAULT NULL, amount INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, sale INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, extra VARCHAR(255) DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_95c0dc7790ede03d5a03e08209062383_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revisions (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE post_audit');
        $this->addSql('DROP TABLE user_audit');
        $this->addSql('DROP TABLE page_audit');
        $this->addSql('DROP TABLE server_audit');
        $this->addSql('DROP TABLE coupon_audit');
        $this->addSql('DROP TABLE purse_audit');
        $this->addSql('DROP TABLE shopcart_audit');
        $this->addSql('DROP TABLE shopcart_category_audit');
        $this->addSql('DROP TABLE shopcart_item_audit');
        $this->addSql('DROP TABLE revisions');
        $this->addSql('ALTER TABLE post CHANGE createdAt createdAt DATETIME NOT NULL');
    }
}
