<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261014122022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create notes table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE notes (
              id INT UNSIGNED AUTO_INCREMENT NOT NULL,
              title VARCHAR(255) NOT NULL,
              content TEXT NOT NULL,
              priority SMALLINT NOT NULL,
              created_at DATETIME NOT NULL,
              updated_at DATETIME NOT NULL,
              INDEX idx_notes_priority (priority),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE notes');
    }
}
