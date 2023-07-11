<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707061431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unicorn ADD user_id INT DEFAULT NULL, CHANGE status status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE unicorn ADD CONSTRAINT FK_58FBD83FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_58FBD83FA76ED395 ON unicorn (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unicorn DROP FOREIGN KEY FK_58FBD83FA76ED395');
        $this->addSql('DROP INDEX IDX_58FBD83FA76ED395 ON unicorn');
        $this->addSql('ALTER TABLE unicorn DROP user_id, CHANGE status status INT NOT NULL');
    }
}
