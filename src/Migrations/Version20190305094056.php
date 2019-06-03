<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190305094056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer CHANGE contractor_id contractor_id INT NOT NULL');
        $this->addSql('ALTER TABLE offer RENAME INDEX idx_29d6873e2fb9e3a1 TO IDX_29D6873EB0265DC7');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer CHANGE contractor_id contractor_id INT DEFAULT 164 NOT NULL');
        $this->addSql('ALTER TABLE offer RENAME INDEX idx_29d6873eb0265dc7 TO IDX_29D6873E2FB9E3A1');
    }
}
