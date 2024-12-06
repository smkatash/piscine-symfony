<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201153011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `address-ex09` (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, street LONGTEXT NOT NULL, city LONGTEXT NOT NULL, country LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_9433C7A217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `bank-account-ex09` (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, account_number VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E2AC45EFB1A4D127 (account_number), INDEX IDX_E2AC45EF217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `person-ex09` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, birthdate DATETIME DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, marital_status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FEE968BAF85E0677 (username), UNIQUE INDEX UNIQ_FEE968BAE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `address-ex09` ADD CONSTRAINT FK_9433C7A217BBB47 FOREIGN KEY (person_id) REFERENCES `person-ex09` (id)');
        $this->addSql('ALTER TABLE `bank-account-ex09` ADD CONSTRAINT FK_E2AC45EF217BBB47 FOREIGN KEY (person_id) REFERENCES `person-ex09` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `address-ex09` DROP FOREIGN KEY FK_9433C7A217BBB47');
        $this->addSql('ALTER TABLE `bank-account-ex09` DROP FOREIGN KEY FK_E2AC45EF217BBB47');
        $this->addSql('DROP TABLE `address-ex09`');
        $this->addSql('DROP TABLE `bank-account-ex09`');
        $this->addSql('DROP TABLE `person-ex09`');
    }
}
