<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210622125556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE analyse');
        $this->addSql('DROP TABLE irrigation');
        $this->addSql('ALTER TABLE bsv_users DROP FOREIGN KEY FK_168226389DD8768F');
        $this->addSql('ALTER TABLE bsv_users ADD CONSTRAINT FK_168226389DD8768F FOREIGN KEY (bsv_id) REFERENCES bsv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panorama_user DROP FOREIGN KEY FK_D9701341F58D4635');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341F58D4635 FOREIGN KEY (panorama_id) REFERENCES panoramas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products CHANGE price price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE purchase_contract_culture DROP FOREIGN KEY FK_6F3C61015ECBF804');
        $this->addSql('ALTER TABLE purchase_contract_culture ADD CONSTRAINT FK_6F3C61015ECBF804 FOREIGN KEY (purchase_contract_id) REFERENCES purchase_contract (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE analyse (id INT AUTO_INCREMENT NOT NULL, ilot_id INT NOT NULL, exploitation_id INT DEFAULT NULL, measure INT NOT NULL, intervention_at DATETIME NOT NULL, INDEX IDX_351B0C7E9A4BD21C (ilot_id), INDEX IDX_351B0C7ED967A16D (exploitation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE irrigation (id INT AUTO_INCREMENT NOT NULL, ilot_id INT NOT NULL, exploitation_id INT NOT NULL, type VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT NOT NULL, comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, intervention_at DATETIME DEFAULT NULL, INDEX IDX_BAE1AE089A4BD21C (ilot_id), INDEX IDX_BAE1AE08D967A16D (exploitation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE analyse ADD CONSTRAINT FK_351B0C7E9A4BD21C FOREIGN KEY (ilot_id) REFERENCES ilots (id)');
        $this->addSql('ALTER TABLE analyse ADD CONSTRAINT FK_351B0C7ED967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE irrigation ADD CONSTRAINT FK_BAE1AE089A4BD21C FOREIGN KEY (ilot_id) REFERENCES ilots (id)');
        $this->addSql('ALTER TABLE irrigation ADD CONSTRAINT FK_BAE1AE08D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE bsv_users DROP FOREIGN KEY FK_168226389DD8768F');
        $this->addSql('ALTER TABLE bsv_users ADD CONSTRAINT FK_168226389DD8768F FOREIGN KEY (bsv_id) REFERENCES bsv (id)');
        $this->addSql('ALTER TABLE panorama_user DROP FOREIGN KEY FK_D9701341F58D4635');
        $this->addSql('ALTER TABLE panorama_user ADD CONSTRAINT FK_D9701341F58D4635 FOREIGN KEY (panorama_id) REFERENCES panoramas (id)');
        $this->addSql('ALTER TABLE products CHANGE price price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE purchase_contract_culture DROP FOREIGN KEY FK_6F3C61015ECBF804');
        $this->addSql('ALTER TABLE purchase_contract_culture ADD CONSTRAINT FK_6F3C61015ECBF804 FOREIGN KEY (purchase_contract_id) REFERENCES purchase_contract (id)');
    }
}
