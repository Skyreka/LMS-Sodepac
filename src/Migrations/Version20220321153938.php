<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321153938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users CHANGE firstname firstname VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(30) DEFAULT NULL, CHANGE password password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bsv CHANGE text text LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_file first_file VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE second_file second_file VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE third_file third_file VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cultures CHANGE comments comments VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE doses CHANGE application application VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE unit unit VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE dar dar VARCHAR(10) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE dre dre VARCHAR(10) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE znt znt VARCHAR(10) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE danger_mention danger_mention VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE risk_mention risk_mention VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE security_mention security_mention VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ilots CHANGE name name VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE index_canevas CHANGE slug slug VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE index_cultures CHANGE slug slug VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE index_effluents CHANGE slug slug VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE index_grounds CHANGE slug slug VARCHAR(75) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(75) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE interventions CHANGE comment comment VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE discr discr VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE dose_unit dose_unit VARCHAR(10) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE unit unit VARCHAR(11) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE mix CHANGE title title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE orders CHANGE id_number id_number VARCHAR(150) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE delivery delivery LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE conditions conditions LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE orders_product CHANGE conditioning conditioning VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE product_name product_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE panorama CHANGE text text LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_file first_file VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE second_file second_file VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE third_file third_file VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ppf CHANGE type_destruction type_destruction VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product_category CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE products CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE id_lex id_lex VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE substance substance VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tox tox VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE risk_phase risk_phase VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE dar dar VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE znt znt VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE dre dre VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE security_mention security_mention VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE danger_mention danger_mention VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE warning_mention warning_mention VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE purchase_contract_culture CHANGE culture culture VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE divers divers VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE recommendation_products CHANGE unit unit VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE c_id c_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE recommendations CHANGE mention mention VARCHAR(10) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE mention_txt mention_txt LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pdf pdf VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE comment comment VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE risk_phase CHANGE long_wording long_wording VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE short_wording short_wording VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sales CHANGE culture culture VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE title title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE column1_txt column1_txt VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE column2_txt column2_txt VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE l1_title l1_title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE l2_title l2_title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE l3_title l3_title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE l4_title l4_title VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sales_information CHANGE top_message top_message LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE footer_message footer_message LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE signature CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE identity identity VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE signature_otp CHANGE code code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tickets CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tickets_messages CHANGE content content LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE file file VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE users CHANGE firstname firstname VARCHAR(80) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE status status VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE certification_phyto certification_phyto VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pack pack VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE company company VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(10) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE warehouse CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
