<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190228090712 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer ADD contractor_id INT NOT NULL DEFAULT 164');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E2FB9E3A1 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E2FB9E3A1 ON offer (contractor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E2FB9E3A1');
        $this->addSql('DROP INDEX IDX_29D6873E2FB9E3A1 ON offer');
        $this->addSql('ALTER TABLE offer DROP contractor_id');
    }
}
