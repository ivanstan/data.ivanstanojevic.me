<?php /** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181105125626 extends AbstractMigration
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pollen (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, pollen_type_id INT DEFAULT NULL, tendency INT DEFAULT NULL, concentration INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_233FE5BC64D218E (location_id), INDEX IDX_233FE5BC1288C71A (pollen_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pollen_type (id INT AUTO_INCREMENT NOT NULL, `group` VARCHAR(255) NOT NULL, potential INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pollen ADD CONSTRAINT FK_233FE5BC64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE pollen ADD CONSTRAINT FK_233FE5BC1288C71A FOREIGN KEY (pollen_type_id) REFERENCES pollen_type (id)');
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pollen DROP FOREIGN KEY FK_233FE5BC1288C71A');
        $this->addSql('ALTER TABLE pollen DROP FOREIGN KEY FK_233FE5BC64D218E');
        $this->addSql('DROP TABLE pollen');
        $this->addSql('DROP TABLE pollen_type');
        $this->addSql('DROP TABLE location');
    }
}
