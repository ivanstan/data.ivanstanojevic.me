<?php /** @noinspection PhpIllegalPsrClassPathInspection */
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181103184058 extends AbstractMigration
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE metar ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE firms CHANGE track track NUMERIC(10, 2) DEFAULT NULL COMMENT \'The algorithm produces 1km fire pixels but MODIS
             *                          pixels get bigger toward the edge of scan. Scan and track reflect actual pixel
             *                          size.\', CHANGE scan scan NUMERIC(10, 2) DEFAULT NULL COMMENT \'The algorithm produces 1km fire pixels but MODIS
             *                          pixels get bigger toward the edge of scan. Scan and track reflect actual pixel size.\'');
    }

    /**
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
        $this->addSql('ALTER TABLE metar DROP type');
    }
}
