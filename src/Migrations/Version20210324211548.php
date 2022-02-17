<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324211548 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ppf ADD effluent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ppf ADD CONSTRAINT FK_205E0CD983F631DB FOREIGN KEY (effluent_id) REFERENCES index_effluents (id)');
        $this->addSql('CREATE INDEX IDX_205E0CD983F631DB ON ppf (effluent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ppf DROP FOREIGN KEY FK_205E0CD983F631DB');
        $this->addSql('DROP INDEX IDX_205E0CD983F631DB ON ppf');
        $this->addSql('ALTER TABLE ppf DROP effluent_id');
    }
}
