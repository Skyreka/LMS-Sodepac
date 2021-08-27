<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318102241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ppf_input (id INT AUTO_INCREMENT NOT NULL, ppf_id INT NOT NULL, product_id INT DEFAULT NULL, date_added DATE NOT NULL, n DOUBLE PRECISION DEFAULT NULL, p DOUBLE PRECISION DEFAULT NULL, k DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, INDEX IDX_E5BDB75FC47FF850 (ppf_id), INDEX IDX_E5BDB75F4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ppf_input ADD CONSTRAINT FK_E5BDB75FC47FF850 FOREIGN KEY (ppf_id) REFERENCES ppf (id)');
        $this->addSql('ALTER TABLE ppf_input ADD CONSTRAINT FK_E5BDB75F4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ppf_input');
    }
}
