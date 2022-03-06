<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303172925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE Numcli numcli VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande CHANGE clients_id clients_id VARCHAR(255) NOT NULL, CHANGE produits_id produits_id VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DCD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (num_pro)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE numcli Numcli VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom Nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DCD11A2CF');
        $this->addSql('ALTER TABLE commande CHANGE clients_id clients_id VARCHAR(16) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE produits_id produits_id VARCHAR(16) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE produit CHANGE num_pro num_pro VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE design design VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
