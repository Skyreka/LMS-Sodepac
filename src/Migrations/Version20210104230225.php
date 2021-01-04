<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210104230225 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE purchase_contract (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, customer_id INT NOT NULL, culture_type INT NOT NULL, added_date DATETIME NOT NULL, INDEX IDX_C04D3B0B61220EA6 (creator_id), INDEX IDX_C04D3B0B9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchase_contract ADD CONSTRAINT FK_C04D3B0B61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE purchase_contract ADD CONSTRAINT FK_C04D3B0B9395C3F3 FOREIGN KEY (customer_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE purchase_contract');
    }
}
