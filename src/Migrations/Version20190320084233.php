<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190320084233 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quiz_question_result (id INT AUTO_INCREMENT NOT NULL, result_id INT DEFAULT NULL, question_id INT NOT NULL, answer_id INT DEFAULT NULL, INDEX IDX_2F33544A7A7B643 (result_id), INDEX IDX_2F33544A1E27F6BF (question_id), INDEX IDX_2F33544AAA334807 (answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_answer (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, answer_id INT DEFAULT NULL, correct TINYINT(1) NOT NULL, INDEX IDX_E684DF7C1E27F6BF (question_id), INDEX IDX_E684DF7CAA334807 (answer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, type VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_result (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quiz_question_result ADD CONSTRAINT FK_2F33544A7A7B643 FOREIGN KEY (result_id) REFERENCES quiz_result (id)');
        $this->addSql('ALTER TABLE quiz_question_result ADD CONSTRAINT FK_2F33544A1E27F6BF FOREIGN KEY (question_id) REFERENCES quiz_question (id)');
        $this->addSql('ALTER TABLE quiz_question_result ADD CONSTRAINT FK_2F33544AAA334807 FOREIGN KEY (answer_id) REFERENCES quiz_answer (id)');
        $this->addSql('ALTER TABLE quiz_question_answer ADD CONSTRAINT FK_E684DF7C1E27F6BF FOREIGN KEY (question_id) REFERENCES quiz_question (id)');
        $this->addSql('ALTER TABLE quiz_question_answer ADD CONSTRAINT FK_E684DF7CAA334807 FOREIGN KEY (answer_id) REFERENCES quiz_answer (id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz_question_result DROP FOREIGN KEY FK_2F33544AAA334807');
        $this->addSql('ALTER TABLE quiz_question_answer DROP FOREIGN KEY FK_E684DF7CAA334807');
        $this->addSql('ALTER TABLE quiz_question_result DROP FOREIGN KEY FK_2F33544A1E27F6BF');
        $this->addSql('ALTER TABLE quiz_question_answer DROP FOREIGN KEY FK_E684DF7C1E27F6BF');
        $this->addSql('ALTER TABLE quiz_question_result DROP FOREIGN KEY FK_2F33544A7A7B643');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('DROP TABLE quiz_question_result');
        $this->addSql('DROP TABLE quiz_answer');
        $this->addSql('DROP TABLE quiz_question_answer');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE quiz_result');
        $this->addSql('DROP TABLE quiz');
    }
}
