<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015163855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD username VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, DROP authorization');
        $this->addSql('ALTER TABLE product ADD vat NUMERIC(5, 2) NOT NULL, ADD reference VARCHAR(255) NOT NULL, CHANGE price price NUMERIC(11, 2) NOT NULL');
        $this->addSql('ALTER TABLE user ADD creation_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD authorization TINYINT(1) NOT NULL, DROP username, DROP password');
        $this->addSql('ALTER TABLE product DROP vat, DROP reference, CHANGE price price INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP creation_date');
    }
}
