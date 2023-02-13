<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213114645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE canevas_product (id INT AUTO_INCREMENT NOT NULL, canevas_id INT NOT NULL, step_id INT NOT NULL, disease_id INT NOT NULL, product_id INT NOT NULL, dose DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(10) DEFAULT NULL, INDEX IDX_F21ABD83170E3058 (canevas_id), INDEX IDX_F21ABD8373B21E9C (step_id), INDEX IDX_F21ABD83D8355341 (disease_id), INDEX IDX_F21ABD834584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE canevas_product ADD CONSTRAINT FK_F21ABD83170E3058 FOREIGN KEY (canevas_id) REFERENCES canevas_index (id)');
        $this->addSql('ALTER TABLE canevas_product ADD CONSTRAINT FK_F21ABD8373B21E9C FOREIGN KEY (step_id) REFERENCES canevas_step (id)');
        $this->addSql('ALTER TABLE canevas_product ADD CONSTRAINT FK_F21ABD83D8355341 FOREIGN KEY (disease_id) REFERENCES canevas_disease (id)');
        $this->addSql('ALTER TABLE canevas_product ADD CONSTRAINT FK_F21ABD834584665A FOREIGN KEY (product_id) REFERENCES products (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE canevas_product DROP FOREIGN KEY FK_F21ABD83170E3058');
        $this->addSql('ALTER TABLE canevas_product DROP FOREIGN KEY FK_F21ABD8373B21E9C');
        $this->addSql('ALTER TABLE canevas_product DROP FOREIGN KEY FK_F21ABD83D8355341');
        $this->addSql('ALTER TABLE canevas_product DROP FOREIGN KEY FK_F21ABD834584665A');
        $this->addSql('DROP TABLE canevas_product');
    }
}
