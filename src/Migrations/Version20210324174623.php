<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324174623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bsv_users DROP FOREIGN KEY FK_168226389DD8768F');
        $this->addSql('ALTER TABLE bsv_users ADD CONSTRAINT FK_168226389DD8768F FOREIGN KEY (bsv_id) REFERENCES bsv (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bsv_users DROP FOREIGN KEY FK_168226389DD8768F');
        $this->addSql('ALTER TABLE bsv_users ADD CONSTRAINT FK_168226389DD8768F FOREIGN KEY (bsv_id) REFERENCES bsv (id)');
    }
}
