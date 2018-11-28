<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181107104419 extends AbstractMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE firms CHANGE track track NUMERIC(10, 2) DEFAULT NULL, CHANGE scan scan NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD country VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE firms CHANGE track track NUMERIC(10, 2) DEFAULT NULL COMMENT \'The algorithm produces 1km fire pixels but MODIS
                     *                          pixels get bigger toward the edge of scan. Scan and track reflect actual pixel
                     *                          size.\', CHANGE scan scan NUMERIC(10, 2) DEFAULT NULL COMMENT \'The algorithm produces 1km fire pixels but MODIS
                     *                          pixels get bigger toward the edge of scan. Scan and track reflect actual pixel size.\'');
        $this->addSql('ALTER TABLE location DROP country');
    }
}
