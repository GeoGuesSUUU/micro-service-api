<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321180759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slot DROP FOREIGN KEY FK_AC0E2067A76ED395');
        $this->addSql('DROP INDEX IDX_AC0E2067A76ED395 ON slot');
        $this->addSql('ALTER TABLE slot DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE slot ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slot ADD CONSTRAINT FK_AC0E2067A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC0E2067A76ED395 ON slot (user_id)');
    }
}
