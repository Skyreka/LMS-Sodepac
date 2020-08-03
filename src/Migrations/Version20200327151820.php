<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200327151820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE analyse (id INT AUTO_INCREMENT NOT NULL, ilot_id INT NOT NULL, exploitation_id INT DEFAULT NULL, measure INT NOT NULL, intervention_at DATETIME NOT NULL, INDEX IDX_351B0C7E9A4BD21C (ilot_id), INDEX IDX_351B0C7ED967A16D (exploitation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bsv (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT DEFAULT NULL, first_file VARCHAR(255) DEFAULT NULL, second_file VARCHAR(255) DEFAULT NULL, sent SMALLINT DEFAULT 0 NOT NULL, creation_date DATETIME NOT NULL, send_date DATETIME DEFAULT NULL, third_file VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bsv_users (id INT AUTO_INCREMENT NOT NULL, bsv_id INT DEFAULT NULL, customers_id INT DEFAULT NULL, checked TINYINT(1) DEFAULT \'0\' NOT NULL, display_at DATETIME DEFAULT NULL, INDEX IDX_168226389DD8768F (bsv_id), INDEX IDX_16822638C3568B40 (customers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cultures (id INT AUTO_INCREMENT NOT NULL, ilot_id INT NOT NULL, name_id INT NOT NULL, precedent_id INT DEFAULT NULL, effluent_id INT DEFAULT NULL, size DOUBLE PRECISION NOT NULL, comments VARCHAR(255) DEFAULT NULL, residue TINYINT(1) DEFAULT NULL, bio TINYINT(1) NOT NULL, production TINYINT(1) NOT NULL, znt DOUBLE PRECISION DEFAULT NULL, INDEX IDX_2C605D679A4BD21C (ilot_id), INDEX IDX_2C605D6771179CD6 (name_id), INDEX IDX_2C605D674F6564F9 (precedent_id), INDEX IDX_2C605D6783F631DB (effluent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doses (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, dose DOUBLE PRECISION DEFAULT NULL, application VARCHAR(255) NOT NULL, unit VARCHAR(255) DEFAULT NULL, INDEX IDX_4EE4A8A84584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exploitation (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, size INT DEFAULT NULL, UNIQUE INDEX UNIQ_BEBCFB167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ilots (id INT AUTO_INCREMENT NOT NULL, exploitation_id INT NOT NULL, name VARCHAR(30) NOT NULL, size INT NOT NULL, type VARCHAR(30) DEFAULT NULL, INDEX IDX_E224BAF3D967A16D (exploitation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE index_cultures (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(30) NOT NULL, name VARCHAR(30) NOT NULL, permanent TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE index_effluents (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(30) NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interventions (id INT AUTO_INCREMENT NOT NULL, culture_id INT NOT NULL, product_id INT DEFAULT NULL, adjuvant_id INT DEFAULT NULL, effluent_id INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, intervention_at DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, reliquat INT DEFAULT NULL, adjuvant_quantity DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(11) DEFAULT NULL, objective INT DEFAULT NULL, INDEX IDX_5ADBAD7FB108249D (culture_id), INDEX IDX_5ADBAD7F4584665A (product_id), INDEX IDX_5ADBAD7F186313FB (adjuvant_id), INDEX IDX_5ADBAD7F83F631DB (effluent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE irrigation (id INT AUTO_INCREMENT NOT NULL, ilot_id INT NOT NULL, exploitation_id INT NOT NULL, type VARCHAR(30) NOT NULL, quantity INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, intervention_at DATETIME DEFAULT NULL, INDEX IDX_BAE1AE089A4BD21C (ilot_id), INDEX IDX_BAE1AE08D967A16D (exploitation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panoramas (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT DEFAULT NULL, first_file VARCHAR(255) DEFAULT NULL, second_file VARCHAR(255) DEFAULT NULL, third_file VARCHAR(255) DEFAULT NULL, validate TINYINT(1) NOT NULL, sent TINYINT(1) NOT NULL, creation_date DATETIME NOT NULL, send_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panorama_user (id INT AUTO_INCREMENT NOT NULL, customers_id INT NOT NULL, panorama_id INT NOT NULL, display_at DATE NOT NULL, checked TINYINT(1) NOT NULL, INDEX IDX_D9701341C3568B40 (customers_id), INDEX IDX_D9701341F58D4635 (panorama_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, exploitation_id INT NOT NULL, product_id INT NOT NULL, quantity DOUBLE PRECISION DEFAULT NULL, unit INT DEFAULT NULL, used_quantity DOUBLE PRECISION NOT NULL, INDEX IDX_56F79805D967A16D (exploitation_id), INDEX IDX_56F798054584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(30) NOT NULL, email VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, phone VARCHAR(30) NOT NULL, city VARCHAR(30) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, status VARCHAR(30) NOT NULL, last_activity DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\', certification_phyto VARCHAR(30) DEFAULT NULL, technician VARCHAR(11) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE analyse ADD CONSTRAINT FK_351B0C7E9A4BD21C FOREIGN KEY (ilot_id) REFERENCES ilots (id)');
        $this->addSql('ALTER TABLE analyse ADD CONSTRAINT FK_351B0C7ED967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE bsv_users ADD CONSTRAINT FK_168226389DD8768F FOREIGN KEY (bsv_id) REFERENCES bsv (id)');
        $this->addSql('ALTER TABLE bsv_users ADD CONSTRAINT FK_16822638C3568B40 FOREIGN KEY (customers_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D679A4BD21C FOREIGN KEY (ilot_id) REFERENCES ilots (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D6771179CD6 FOREIGN KEY (name_id) REFERENCES index_cultures (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D674F6564F9 FOREIGN KEY (precedent_id) REFERENCES index_cultures (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D6783F631DB FOREIGN KEY (effluent_id) REFERENCES index_effluents (id)');
        $this->addSql('ALTER TABLE doses ADD CONSTRAINT FK_4EE4A8A84584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE exploitation ADD CONSTRAINT FK_BEBCFB167B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ilots ADD CONSTRAINT FK_E224BAF3D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7FB108249D FOREIGN KEY (culture_id) REFERENCES cultures (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F186313FB FOREIGN KEY (adjuvant_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F83F631DB FOREIGN KEY (effluent_id) REFERENCES index_effluents (id)');
        $this->addSql('ALTER TABLE irrigation ADD CONSTRAINT FK_BAE1AE089A4BD21C FOREIGN KEY (ilot_id) REFERENCES ilots (id)');
        $this->addSql('ALTER TABLE irrigation ADD CONSTRAINT FK_BAE1AE08D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341C3568B40 FOREIGN KEY (customers_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341F58D4635 FOREIGN KEY (panorama_id) REFERENCES panoramas (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F79805D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F798054584665A FOREIGN KEY (product_id) REFERENCES products (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bsv_users DROP FOREIGN KEY FK_168226389DD8768F');
        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7FB108249D');
        $this->addSql('ALTER TABLE analyse DROP FOREIGN KEY FK_351B0C7ED967A16D');
        $this->addSql('ALTER TABLE ilots DROP FOREIGN KEY FK_E224BAF3D967A16D');
        $this->addSql('ALTER TABLE irrigation DROP FOREIGN KEY FK_BAE1AE08D967A16D');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F79805D967A16D');
        $this->addSql('ALTER TABLE analyse DROP FOREIGN KEY FK_351B0C7E9A4BD21C');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D679A4BD21C');
        $this->addSql('ALTER TABLE irrigation DROP FOREIGN KEY FK_BAE1AE089A4BD21C');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D6771179CD6');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D674F6564F9');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D6783F631DB');
        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7F83F631DB');
        $this->addSql('ALTER TABLE panorama_user DROP FOREIGN KEY FK_D9701341F58D4635');
        $this->addSql('ALTER TABLE doses DROP FOREIGN KEY FK_4EE4A8A84584665A');
        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7F4584665A');
        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7F186313FB');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F798054584665A');
        $this->addSql('ALTER TABLE bsv_users DROP FOREIGN KEY FK_16822638C3568B40');
        $this->addSql('ALTER TABLE exploitation DROP FOREIGN KEY FK_BEBCFB167B3B43D');
        $this->addSql('ALTER TABLE panorama_user DROP FOREIGN KEY FK_D9701341C3568B40');
        $this->addSql('DROP TABLE analyse');
        $this->addSql('DROP TABLE bsv');
        $this->addSql('DROP TABLE bsv_users');
        $this->addSql('DROP TABLE cultures');
        $this->addSql('DROP TABLE doses');
        $this->addSql('DROP TABLE exploitation');
        $this->addSql('DROP TABLE ilots');
        $this->addSql('DROP TABLE index_cultures');
        $this->addSql('DROP TABLE index_effluents');
        $this->addSql('DROP TABLE interventions');
        $this->addSql('DROP TABLE irrigation');
        $this->addSql('DROP TABLE panoramas');
        $this->addSql('DROP TABLE panorama_user');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE stocks');
        $this->addSql('DROP TABLE users');
    }
}
