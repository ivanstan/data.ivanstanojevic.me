<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181017150911 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE metar (id INT AUTO_INCREMENT NOT NULL, icao VARCHAR(255) NOT NULL, date DATETIME NOT NULL, metar LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE airports');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE airports (id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, city VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, country VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, iata VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, icao VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, latitude VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, longitude VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, altitude INT DEFAULT NULL, utf_offset VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, dst VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, timezone VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, source VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE metar');
    }
}
