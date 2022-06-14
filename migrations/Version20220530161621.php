<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530161621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enchere_fournisseur DROP FOREIGN KEY enchere_fournisseur_ibfk_1');
        $this->addSql('DROP INDEX id_enchere_id ON enchere_fournisseur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE enchere_fournisseur CHANGE fournisseur fournisseur VARCHAR(60) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE produit produit VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE enchere_fournisseur ADD CONSTRAINT enchere_fournisseur_ibfk_1 FOREIGN KEY (id_enchere_id) REFERENCES enchere (id)');
        $this->addSql('CREATE INDEX id_enchere_id ON enchere_fournisseur (id_enchere_id)');
    }
}
