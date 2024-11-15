<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115145939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD editor_id INT NOT NULL, ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6995AC4C FOREIGN KEY (editor_id) REFERENCES `users` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D6995AC4C ON post (editor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `post` DROP FOREIGN KEY FK_5A8A6C8D6995AC4C');
        $this->addSql('DROP INDEX IDX_5A8A6C8D6995AC4C ON `post`');
        $this->addSql('ALTER TABLE `post` DROP editor_id, DROP updated_at');
    }
}
