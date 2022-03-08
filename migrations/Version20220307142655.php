<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220307142655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE factures (id INT AUTO_INCREMENT NOT NULL, client_id VARCHAR(10) NOT NULL, date_facture DATE NOT NULL, INDEX IDX_647590B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590B19EB6921 FOREIGN KEY (client_id) REFERENCES client (numcli)');
        $this->addSql('ALTER TABLE commande CHANGE clients_id clients_id VARCHAR(10) NOT NULL, CHANGE produits_id produits_id VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DCD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (num_pro)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE factures');
        $this->addSql('ALTER TABLE client CHANGE numcli Numcli VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nom Nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DCD11A2CF');
        $this->addSql('ALTER TABLE commande CHANGE clients_id clients_id VARCHAR(16) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE produits_id produits_id VARCHAR(16) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE produit CHANGE num_pro num_pro VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE design design VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
