<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324155207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interventions_products DROP FOREIGN KEY FK_87F99D4D8EAE3863');
        $this->addSql('ALTER TABLE interventions_products ADD CONSTRAINT FK_87F99D4D8EAE3863 FOREIGN KEY (intervention_id) REFERENCES interventions (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interventions_products DROP FOREIGN KEY FK_87F99D4D8EAE3863');
        $this->addSql('ALTER TABLE interventions_products ADD CONSTRAINT FK_87F99D4D8EAE3863 FOREIGN KEY (intervention_id) REFERENCES interventions (id)');
    }
}
