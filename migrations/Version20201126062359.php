<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126062359 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INTEGER NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ebanx_transaction (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, origin_id INTEGER DEFAULT NULL, destination_id INTEGER DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FB6E78D056A273CC ON ebanx_transaction (origin_id)');
        $this->addSql('CREATE INDEX IDX_FB6E78D0816C6140 ON ebanx_transaction (destination_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE ebanx_transaction');
    }
}
