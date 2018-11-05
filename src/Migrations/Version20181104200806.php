<?php /** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181104200806 extends AbstractMigration
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE frequency (id INT AUTO_INCREMENT NOT NULL, icao VARCHAR(4) DEFAULT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, frequency NUMERIC(10, 2) NOT NULL, INDEX IDX_267FB8138C1034C3 (icao), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE INDEX designator ON airport (icao, iata)');
        $this->addSql('ALTER TABLE frequency ADD CONSTRAINT FK_267FB8138C1034C3 FOREIGN KEY (icao) REFERENCES airport (icao)');
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE frequency');
        $this->addSql('DROP INDEX designator ON airport');
    }
}
