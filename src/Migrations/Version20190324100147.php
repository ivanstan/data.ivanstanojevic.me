<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190324100147 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, destination VARCHAR(255) NOT NULL, size INT NOT NULL, mime VARCHAR(255) DEFAULT NULL, INDEX IDX_8C9F3610A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD preference_id INT DEFAULT NULL, ADD last_login DATETIME DEFAULT NULL, ADD created DATETIME DEFAULT NULL, ADD updated DATETIME DEFAULT NULL, ADD ip VARCHAR(255) DEFAULT NULL, ADD active TINYINT(1) DEFAULT \'0\' NOT NULL, ADD verified TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D81022C0 FOREIGN KEY (preference_id) REFERENCES user_preference (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D81022C0 ON user (preference_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE file');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D81022C0');
        $this->addSql('DROP INDEX UNIQ_8D93D649D81022C0 ON user');
        $this->addSql('ALTER TABLE user DROP preference_id, DROP last_login, DROP created, DROP updated, DROP ip, DROP active, DROP verified, CHANGE password password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
