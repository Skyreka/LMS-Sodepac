<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324175027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_contract_culture DROP FOREIGN KEY FK_6F3C61015ECBF804');
        $this->addSql('ALTER TABLE purchase_contract_culture ADD CONSTRAINT FK_6F3C61015ECBF804 FOREIGN KEY (purchase_contract_id) REFERENCES purchase_contract (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchase_contract_culture DROP FOREIGN KEY FK_6F3C61015ECBF804');
        $this->addSql('ALTER TABLE purchase_contract_culture ADD CONSTRAINT FK_6F3C61015ECBF804 FOREIGN KEY (purchase_contract_id) REFERENCES purchase_contract (id)');
    }
}
