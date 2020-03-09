<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309173009 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cultures ADD ilot_id INT NOT NULL, ADD name_id INT NOT NULL, ADD precedent_id INT DEFAULT NULL, ADD size INT NOT NULL, ADD comments VARCHAR(255) DEFAULT NULL, ADD residue TINYINT(1) DEFAULT NULL, DROP name');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D679A4BD21C FOREIGN KEY (ilot_id) REFERENCES ilots (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D6771179CD6 FOREIGN KEY (name_id) REFERENCES index_cultures (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D674F6564F9 FOREIGN KEY (precedent_id) REFERENCES index_cultures (id)');
        $this->addSql('CREATE INDEX IDX_2C605D679A4BD21C ON cultures (ilot_id)');
        $this->addSql('CREATE INDEX IDX_2C605D6771179CD6 ON cultures (name_id)');
        $this->addSql('CREATE INDEX IDX_2C605D674F6564F9 ON cultures (precedent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D679A4BD21C');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D6771179CD6');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D674F6564F9');
        $this->addSql('DROP INDEX IDX_2C605D679A4BD21C ON cultures');
        $this->addSql('DROP INDEX IDX_2C605D6771179CD6 ON cultures');
        $this->addSql('DROP INDEX IDX_2C605D674F6564F9 ON cultures');
        $this->addSql('ALTER TABLE cultures ADD name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP ilot_id, DROP name_id, DROP precedent_id, DROP size, DROP comments, DROP residue');
    }
}
