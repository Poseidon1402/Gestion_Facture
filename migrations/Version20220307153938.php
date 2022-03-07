<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220307153938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (numcli VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(numcli)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, clients_id VARCHAR(255) NOT NULL, produits_id VARCHAR(10) NOT NULL, qte INT NOT NULL, date_commande DATE NOT NULL, INDEX IDX_6EEAA67DAB014612 (clients_id), INDEX IDX_6EEAA67DCD11A2CF (produits_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factures (id INT AUTO_INCREMENT NOT NULL, client_id VARCHAR(255) NOT NULL, date_facture DATE NOT NULL, INDEX IDX_647590B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (num_pro VARCHAR(10) NOT NULL, design VARCHAR(255) NOT NULL, pu DOUBLE PRECISION NOT NULL, stock INT NOT NULL, PRIMARY KEY(num_pro)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DAB014612 FOREIGN KEY (clients_id) REFERENCES client (numcli)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DCD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (num_pro)');
        $this->addSql('ALTER TABLE factures ADD CONSTRAINT FK_647590B19EB6921 FOREIGN KEY (client_id) REFERENCES client (numcli)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DAB014612');
        $this->addSql('ALTER TABLE factures DROP FOREIGN KEY FK_647590B19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DCD11A2CF');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE factures');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
