<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601215349 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE airport (id INT AUTO_INCREMENT NOT NULL, altitude NUMERIC(10, 2) NOT NULL, utc_offset NUMERIC(10, 2) NOT NULL, source VARCHAR(255) NOT NULL, timezone VARCHAR(255) NOT NULL, dst VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, type VARCHAR(255) NOT NULL, iata VARCHAR(3) NOT NULL, icao VARCHAR(4) NOT NULL, INDEX designator (icao, iata), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frequency (id INT AUTO_INCREMENT NOT NULL, airport_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, frequency NUMERIC(10, 2) NOT NULL, INDEX IDX_267FB813289F53C8 (airport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metar (id INT AUTO_INCREMENT NOT NULL, icao VARCHAR(255) NOT NULL, metar LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tle (id INT AUTO_INCREMENT NOT NULL, updated_at DATETIME NOT NULL, satellite_id INT NOT NULL, PRN INT DEFAULT NULL, name VARCHAR(255) NOT NULL, line1 VARCHAR(255) NOT NULL, line2 VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5829A8E22D0EAD71 (satellite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, `interval` VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', created DATETIME NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_5F37A13BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE import_source (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, uri LONGTEXT NOT NULL, sha1 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_preference (id INT AUTO_INCREMENT NOT NULL, timezone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `lock` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value INT NOT NULL, data VARCHAR(255) NOT NULL, expire DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id VARCHAR(128) NOT NULL, user_id INT DEFAULT NULL, data LONGBLOB DEFAULT NULL, date DATETIME NOT NULL, lifetime VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', INDEX IDX_D044D5D4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, destination VARCHAR(255) NOT NULL, size INT DEFAULT NULL, md5 VARCHAR(255) DEFAULT NULL, mime VARCHAR(255) DEFAULT NULL, INDEX IDX_8C9F3610A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, preference_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', last_login DATETIME DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, verified TINYINT(1) DEFAULT \'0\' NOT NULL, avatar VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649D81022C0 (preference_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question_result (id INT AUTO_INCREMENT NOT NULL, result_id INT DEFAULT NULL, question_id INT NOT NULL, answer_id INT DEFAULT NULL, INDEX IDX_2F33544A7A7B643 (result_id), INDEX IDX_2F33544A1E27F6BF (question_id), INDEX IDX_2F33544AAA334807 (answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_answer (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, answer_id INT DEFAULT NULL, correct TINYINT(1) NOT NULL, INDEX IDX_E684DF7C1E27F6BF (question_id), INDEX IDX_E684DF7CAA334807 (answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, type VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_result (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, INDEX IDX_FE2E314A853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE firms (id INT AUTO_INCREMENT NOT NULL, brightness NUMERIC(10, 2) DEFAULT NULL COMMENT \'Channel I-4 for VIIRS and Channel 21/22 for MODIS.\', brightness31 NUMERIC(10, 2) DEFAULT NULL COMMENT \'Channel I-5 for VIIRS and Channel 31 for MODIS\', power NUMERIC(10, 2) DEFAULT NULL COMMENT \'Depicts the pixel-integrated fire radiative power in megawatts.\', daytime TINYINT(1) DEFAULT NULL, satellite VARCHAR(255) DEFAULT NULL, track NUMERIC(10, 2) DEFAULT NULL, scan NUMERIC(10, 2) DEFAULT NULL, confidence VARCHAR(255) DEFAULT NULL, instrument VARCHAR(255) DEFAULT NULL, version VARCHAR(255) DEFAULT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frequency ADD CONSTRAINT FK_267FB813289F53C8 FOREIGN KEY (airport_id) REFERENCES airport (id)');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D81022C0 FOREIGN KEY (preference_id) REFERENCES user_preference (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_question_result ADD CONSTRAINT FK_2F33544A7A7B643 FOREIGN KEY (result_id) REFERENCES quiz_result (id)');
        $this->addSql('ALTER TABLE quiz_question_result ADD CONSTRAINT FK_2F33544A1E27F6BF FOREIGN KEY (question_id) REFERENCES quiz_question (id)');
        $this->addSql('ALTER TABLE quiz_question_result ADD CONSTRAINT FK_2F33544AAA334807 FOREIGN KEY (answer_id) REFERENCES quiz_answer (id)');
        $this->addSql('ALTER TABLE quiz_question_answer ADD CONSTRAINT FK_E684DF7C1E27F6BF FOREIGN KEY (question_id) REFERENCES quiz_question (id)');
        $this->addSql('ALTER TABLE quiz_question_answer ADD CONSTRAINT FK_E684DF7CAA334807 FOREIGN KEY (answer_id) REFERENCES quiz_answer (id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_result ADD CONSTRAINT FK_FE2E314A853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE frequency DROP FOREIGN KEY FK_267FB813289F53C8');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D81022C0');
        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4A76ED395');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610A76ED395');
        $this->addSql('ALTER TABLE quiz_question_result DROP FOREIGN KEY FK_2F33544AAA334807');
        $this->addSql('ALTER TABLE quiz_question_answer DROP FOREIGN KEY FK_E684DF7CAA334807');
        $this->addSql('ALTER TABLE quiz_question_result DROP FOREIGN KEY FK_2F33544A1E27F6BF');
        $this->addSql('ALTER TABLE quiz_question_answer DROP FOREIGN KEY FK_E684DF7C1E27F6BF');
        $this->addSql('ALTER TABLE quiz_question_result DROP FOREIGN KEY FK_2F33544A7A7B643');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_result DROP FOREIGN KEY FK_FE2E314A853CD175');
        $this->addSql('DROP TABLE airport');
        $this->addSql('DROP TABLE frequency');
        $this->addSql('DROP TABLE metar');
        $this->addSql('DROP TABLE tle');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE import_source');
        $this->addSql('DROP TABLE user_preference');
        $this->addSql('DROP TABLE `lock`');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE quiz_question_result');
        $this->addSql('DROP TABLE quiz_answer');
        $this->addSql('DROP TABLE quiz_question_answer');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE quiz_result');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE firms');
    }
}
