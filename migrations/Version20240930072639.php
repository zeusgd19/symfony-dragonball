<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930072639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poderes ADD personaje_id INT NOT NULL');
        $this->addSql('ALTER TABLE poderes ADD CONSTRAINT FK_6E1F673C121EFAFB FOREIGN KEY (personaje_id) REFERENCES personaje (id)');
        $this->addSql('CREATE INDEX IDX_6E1F673C121EFAFB ON poderes (personaje_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poderes DROP FOREIGN KEY FK_6E1F673C121EFAFB');
        $this->addSql('DROP INDEX IDX_6E1F673C121EFAFB ON poderes');
        $this->addSql('ALTER TABLE poderes DROP personaje_id');
    }
}
