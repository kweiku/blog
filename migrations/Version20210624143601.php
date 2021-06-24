<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624143601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories ADD author_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_3AF34668F675F31B ON categories (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668F675F31B');
        $this->addSql('DROP INDEX IDX_3AF34668F675F31B ON categories');
        $this->addSql('ALTER TABLE categories DROP author_id');
    }
}
