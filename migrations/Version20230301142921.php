<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301142921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE command (id INT AUTO_INCREMENT NOT NULL, slot_id INT DEFAULT NULL, store_id INT DEFAULT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8ECAEAD459E5119C (slot_id), INDEX IDX_8ECAEAD4B092A811 (store_id), INDEX IDX_8ECAEAD4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command_product (id INT AUTO_INCREMENT NOT NULL, command_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_3C20574E33E1689A (command_id), INDEX IDX_3C20574E4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, seller_id INT DEFAULT NULL, content VARCHAR(2048) NOT NULL, INDEX IDX_B6BD307FA76ED395 (user_id), INDEX IDX_B6BD307F8DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slot (id INT AUTO_INCREMENT NOT NULL, store_id INT DEFAULT NULL, user_id INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, INDEX IDX_AC0E2067B092A811 (store_id), UNIQUE INDEX UNIQ_AC0E2067A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, zip INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_product (id INT AUTO_INCREMENT NOT NULL, store_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_CA42254AB092A811 (store_id), INDEX IDX_CA42254A4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, store_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649B092A811 (store_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD459E5119C FOREIGN KEY (slot_id) REFERENCES slot (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE command_product ADD CONSTRAINT FK_3C20574E33E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('ALTER TABLE command_product ADD CONSTRAINT FK_3C20574E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F8DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE store_product ADD CONSTRAINT FK_CA42254AB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE store_product ADD CONSTRAINT FK_CA42254A4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD459E5119C');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4B092A811');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4A76ED395');
        $this->addSql('ALTER TABLE command_product DROP FOREIGN KEY FK_3C20574E33E1689A');
        $this->addSql('ALTER TABLE command_product DROP FOREIGN KEY FK_3C20574E4584665A');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F8DE820D9');
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E2067B092A811');
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E2067A76ED395');
        $this->addSql('ALTER TABLE store_product DROP FOREIGN KEY FK_CA42254AB092A811');
        $this->addSql('ALTER TABLE store_product DROP FOREIGN KEY FK_CA42254A4584665A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B092A811');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE command_product');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE slot');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE store_product');
        $this->addSql('DROP TABLE user');
    }
}
