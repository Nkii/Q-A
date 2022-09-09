<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908095817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25F675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_DADD4A25F675F31B ON answer (author_id)');
        $this->addSql('ALTER TABLE question CHANGE author_id author_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25F675F31B');
        $this->addSql('DROP INDEX IDX_DADD4A25F675F31B ON answer');
        $this->addSql('ALTER TABLE answer DROP author_id');
        $this->addSql('ALTER TABLE question CHANGE author_id author_id INT DEFAULT NULL');
    }
}
