<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903130445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE constructions (id VARCHAR(6) NOT NULL, name VARCHAR(200) NOT NULL, location TEXT NOT NULL, stage VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, other_category VARCHAR(255) DEFAULT NULL, start_date DATE NOT NULL, description TEXT NOT NULL, creator_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_name ON constructions (name)');
        $this->addSql('CREATE INDEX idx_location ON constructions (location)');
        $this->addSql('CREATE INDEX idx_stage ON constructions (stage)');
        $this->addSql('CREATE INDEX idx_category ON constructions (category)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE constructions');
    }
}
