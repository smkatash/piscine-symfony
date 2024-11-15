<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115150250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `post` (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, editor_id INT NOT NULL, title LONGTEXT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A8A6C8DF675F31B (author_id), INDEX IDX_5A8A6C8D6995AC4C (editor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `users` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_liked_posts (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_3FC37DA9A76ED395 (user_id), INDEX IDX_3FC37DA94B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_disliked_posts (user_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_8A220CEA76ED395 (user_id), INDEX IDX_8A220CE4B89032C (post_id), PRIMARY KEY(user_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `post` ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES `users` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `post` ADD CONSTRAINT FK_5A8A6C8D6995AC4C FOREIGN KEY (editor_id) REFERENCES `users` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_liked_posts ADD CONSTRAINT FK_3FC37DA9A76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_liked_posts ADD CONSTRAINT FK_3FC37DA94B89032C FOREIGN KEY (post_id) REFERENCES `post` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_disliked_posts ADD CONSTRAINT FK_8A220CEA76ED395 FOREIGN KEY (user_id) REFERENCES `users` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_disliked_posts ADD CONSTRAINT FK_8A220CE4B89032C FOREIGN KEY (post_id) REFERENCES `post` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `post` DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE `post` DROP FOREIGN KEY FK_5A8A6C8D6995AC4C');
        $this->addSql('ALTER TABLE user_liked_posts DROP FOREIGN KEY FK_3FC37DA9A76ED395');
        $this->addSql('ALTER TABLE user_liked_posts DROP FOREIGN KEY FK_3FC37DA94B89032C');
        $this->addSql('ALTER TABLE user_disliked_posts DROP FOREIGN KEY FK_8A220CEA76ED395');
        $this->addSql('ALTER TABLE user_disliked_posts DROP FOREIGN KEY FK_8A220CE4B89032C');
        $this->addSql('DROP TABLE `post`');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE `users`');
        $this->addSql('DROP TABLE user_liked_posts');
        $this->addSql('DROP TABLE user_disliked_posts');
    }
}
