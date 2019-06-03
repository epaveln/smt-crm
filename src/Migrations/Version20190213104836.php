<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190213104836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contractor (id INT AUTO_INCREMENT NOT NULL, contractor_type_id INT NOT NULL, country_id INT NOT NULL, name VARCHAR(45) NOT NULL, email JSON NOT NULL, phone JSON NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_437BD2EFA91E97D4 (contractor_type_id), INDEX IDX_437BD2EFF92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contractor ADD CONSTRAINT FK_437BD2EFA91E97D4 FOREIGN KEY (contractor_type_id) REFERENCES contractor_type (id)');
        $this->addSql('ALTER TABLE contractor ADD CONSTRAINT FK_437BD2EFF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contractor');
    }
}
