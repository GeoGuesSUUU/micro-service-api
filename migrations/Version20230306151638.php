<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230306151638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command_product ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product DROP price');
        $this->addSql('ALTER TABLE store CHANGE zip zip VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE store_product ADD price DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command_product DROP price');
        $this->addSql('ALTER TABLE product ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE store CHANGE zip zip INT NOT NULL');
        $this->addSql('ALTER TABLE store_product DROP price');
    }
}
