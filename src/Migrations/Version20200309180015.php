<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309180015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cultures ADD effluent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D6783F631DB FOREIGN KEY (effluent_id) REFERENCES index_effluents (id)');
        $this->addSql('CREATE INDEX IDX_2C605D6783F631DB ON cultures (effluent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D6783F631DB');
        $this->addSql('DROP INDEX IDX_2C605D6783F631DB ON cultures');
        $this->addSql('ALTER TABLE cultures DROP effluent_id');
    }
}
