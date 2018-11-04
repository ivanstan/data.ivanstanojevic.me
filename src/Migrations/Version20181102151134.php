<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181102151134 extends AbstractMigration
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE import_source (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, uri LONGTEXT NOT NULL, sha1 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE firms (id INT AUTO_INCREMENT NOT NULL, brightness NUMERIC(10, 2) DEFAULT NULL COMMENT \'Channel I-4 for VIIRS and Channel 21/22 for MODIS.\', brightness31 NUMERIC(10, 2) DEFAULT NULL COMMENT \'Channel I-5 for VIIRS and Channel 31 for MODIS\', power NUMERIC(10, 2) DEFAULT NULL COMMENT \'Depicts the pixel-integrated fire radiative power in megawatts.\', daytime TINYINT(1) DEFAULT NULL, satellite VARCHAR(255) DEFAULT NULL, track NUMERIC(10, 2) DEFAULT NULL COMMENT \'The algorithm produces 1km fire pixels but MODIS
             *                          pixels get bigger toward the edge of scan. Scan and track reflect actual pixel
             *                          size.\', scan NUMERIC(10, 2) DEFAULT NULL COMMENT \'The algorithm produces 1km fire pixels but MODIS
             *                          pixels get bigger toward the edge of scan. Scan and track reflect actual pixel size.\', date DATETIME NOT NULL, confidence VARCHAR(255) DEFAULT NULL, instrument VARCHAR(255) DEFAULT NULL, version VARCHAR(255) DEFAULT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(10, 8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE import_source');
        $this->addSql('DROP TABLE firms');
    }
}
