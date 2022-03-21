<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321185948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bsv (id INT AUTO_INCREMENT NOT NULL, text LONGTEXT DEFAULT NULL, first_file VARCHAR(255) DEFAULT NULL, second_file VARCHAR(255) DEFAULT NULL, sent SMALLINT DEFAULT 0 NOT NULL, creation_date DATETIME NOT NULL, send_date DATETIME DEFAULT NULL, third_file VARCHAR(255) DEFAULT NULL, archive TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bsv_users (id INT AUTO_INCREMENT NOT NULL, bsv_id INT DEFAULT NULL, customers_id INT DEFAULT NULL, checked TINYINT(1) DEFAULT 0 NOT NULL, display_at DATETIME DEFAULT NULL, INDEX IDX_168226389DD8768F (bsv_id), INDEX IDX_16822638C3568B40 (customers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cultures (id INT AUTO_INCREMENT NOT NULL, ilot_id INT NOT NULL, name_id INT NOT NULL, precedent_id INT DEFAULT NULL, effluent_id INT DEFAULT NULL, size DOUBLE PRECISION NOT NULL, comments VARCHAR(255) DEFAULT NULL, residue TINYINT(1) DEFAULT NULL, bio TINYINT(1) NOT NULL, production TINYINT(1) NOT NULL, znt DOUBLE PRECISION DEFAULT NULL, status TINYINT(1) DEFAULT NULL, permanent TINYINT(1) DEFAULT NULL, added_at DATETIME DEFAULT NULL, update_at DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, INDEX IDX_2C605D679A4BD21C (ilot_id), INDEX IDX_2C605D6771179CD6 (name_id), INDEX IDX_2C605D674F6564F9 (precedent_id), INDEX IDX_2C605D6783F631DB (effluent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doses (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, index_culture_id INT DEFAULT NULL, dose DOUBLE PRECISION DEFAULT NULL, application VARCHAR(255) NOT NULL, unit VARCHAR(255) DEFAULT NULL, dar VARCHAR(10) DEFAULT NULL, dre VARCHAR(10) DEFAULT NULL, znt VARCHAR(10) DEFAULT NULL, danger_mention VARCHAR(255) DEFAULT NULL, risk_mention VARCHAR(255) DEFAULT NULL, security_mention VARCHAR(255) DEFAULT NULL, INDEX IDX_4EE4A8A84584665A (product_id), INDEX IDX_4EE4A8A8BAE36EE6 (index_culture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exploitation (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, size INT DEFAULT NULL, UNIQUE INDEX UNIQ_BEBCFB167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ilots (id INT AUTO_INCREMENT NOT NULL, exploitation_id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(50) NOT NULL, size DOUBLE PRECISION NOT NULL, number DOUBLE PRECISION DEFAULT NULL, added_at DATETIME DEFAULT NULL, update_at DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, INDEX IDX_E224BAF3D967A16D (exploitation_id), INDEX IDX_E224BAF3C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE index_canevas (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, is_active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE index_cultures (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, permanent TINYINT(1) DEFAULT NULL, id_lex INT DEFAULT NULL, is_display TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE index_effluents (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(30) NOT NULL, name VARCHAR(30) NOT NULL, nitrogen_content DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE index_grounds (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(75) NOT NULL, name VARCHAR(75) NOT NULL, humus_mineralization DOUBLE PRECISION DEFAULT NULL, nitrogen DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interventions (id INT AUTO_INCREMENT NOT NULL, culture_id INT NOT NULL, product_id INT DEFAULT NULL, effluent_id INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, intervention_at DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, is_multiple INT DEFAULT NULL, discr VARCHAR(255) NOT NULL, rendement DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, n DOUBLE PRECISION DEFAULT NULL, p DOUBLE PRECISION DEFAULT NULL, size_multiple DOUBLE PRECISION DEFAULT NULL, k DOUBLE PRECISION DEFAULT NULL, reliquat INT DEFAULT NULL, dose DOUBLE PRECISION DEFAULT NULL, dose_unit VARCHAR(10) DEFAULT NULL, dose_hectare DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(11) DEFAULT NULL, objective INT DEFAULT NULL, INDEX IDX_5ADBAD7FB108249D (culture_id), INDEX IDX_5ADBAD7F4584665A (product_id), INDEX IDX_5ADBAD7F83F631DB (effluent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interventions_products (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, intervention_id INT NOT NULL, dose DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, dose_hectare DOUBLE PRECISION DEFAULT NULL, INDEX IDX_87F99D4D4584665A (product_id), INDEX IDX_87F99D4D8EAE3863 (intervention_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mix (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL, INDEX IDX_55AFA881A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mix_products (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, mix_id INT NOT NULL, INDEX IDX_785CCDCC4584665A (product_id), INDEX IDX_785CCDCCA6013C4A (mix_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, customer_id INT NOT NULL, signature_id INT DEFAULT NULL, id_number VARCHAR(150) NOT NULL, create_date DATETIME NOT NULL, status INT NOT NULL, delivery LONGTEXT DEFAULT NULL, conditions LONGTEXT DEFAULT NULL, INDEX IDX_E52FFDEE61220EA6 (creator_id), INDEX IDX_E52FFDEE9395C3F3 (customer_id), UNIQUE INDEX UNIQ_E52FFDEEED61183A (signature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_product (id INT AUTO_INCREMENT NOT NULL, orders_id INT NOT NULL, product_id INT DEFAULT NULL, conditioning VARCHAR(255) DEFAULT NULL, total_quantity DOUBLE PRECISION DEFAULT NULL, unit_price DOUBLE PRECISION DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, taxe DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, product_name VARCHAR(255) DEFAULT NULL, INDEX IDX_223F76D6CFFE9AD6 (orders_id), INDEX IDX_223F76D64584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panorama (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, first_file VARCHAR(255) DEFAULT NULL, second_file VARCHAR(255) DEFAULT NULL, third_file VARCHAR(255) DEFAULT NULL, validate TINYINT(1) NOT NULL, sent TINYINT(1) NOT NULL, creation_date DATETIME NOT NULL, send_date DATETIME DEFAULT NULL, archive TINYINT(1) NOT NULL, INDEX IDX_AFEA0A3A7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panorama_send (id INT AUTO_INCREMENT NOT NULL, customers_id INT NOT NULL, panorama_id INT NOT NULL, sender_id INT NOT NULL, display_at DATETIME DEFAULT NULL, checked TINYINT(1) NOT NULL, INDEX IDX_F390FFC5C3568B40 (customers_id), INDEX IDX_F390FFC5F58D4635 (panorama_id), INDEX IDX_F390FFC5F624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ppf (id INT AUTO_INCREMENT NOT NULL, culture_id INT NOT NULL, effluent_id INT DEFAULT NULL, effiency_prev DOUBLE PRECISION DEFAULT NULL, qty_azote_add_prev DOUBLE PRECISION DEFAULT NULL, date_implantation_planned DATE DEFAULT NULL, intermediate_culture TINYINT(1) DEFAULT NULL, push_back TINYINT(1) DEFAULT NULL, date_sow DATE DEFAULT NULL, type_destruction VARCHAR(255) DEFAULT NULL, qty_water_prev DOUBLE PRECISION DEFAULT NULL, type_effluent INT DEFAULT NULL, qty_ependu DOUBLE PRECISION DEFAULT NULL, date_spreading DATE DEFAULT NULL, date_destruction DATE DEFAULT NULL, remainder_soil_sow DOUBLE PRECISION DEFAULT NULL, resource_nitrate_content DOUBLE PRECISION DEFAULT NULL, coefficient_equivalence DOUBLE PRECISION DEFAULT NULL, qty_azote_add DOUBLE PRECISION DEFAULT NULL, status INT DEFAULT NULL, added_date DATETIME DEFAULT NULL, type INT NOT NULL, need_plant DOUBLE PRECISION DEFAULT NULL, nitrogen_requirement DOUBLE PRECISION DEFAULT NULL, effect_meadow DOUBLE PRECISION DEFAULT NULL, effect_residual_collected DOUBLE PRECISION DEFAULT NULL, coefficient_multiple DOUBLE PRECISION DEFAULT NULL, coefficient_use DOUBLE PRECISION DEFAULT NULL, nutrigen_organic DOUBLE PRECISION DEFAULT NULL, INDEX IDX_205E0CD9B108249D (culture_id), INDEX IDX_205E0CD983F631DB (effluent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ppf_input (id INT AUTO_INCREMENT NOT NULL, ppf_id INT NOT NULL, product_id INT DEFAULT NULL, date_added DATE NOT NULL, n DOUBLE PRECISION DEFAULT NULL, p DOUBLE PRECISION DEFAULT NULL, k DOUBLE PRECISION DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, INDEX IDX_B14E40DCC47FF850 (ppf_id), INDEX IDX_B14E40DC4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, parent_product_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, private TINYINT(1) NOT NULL, id_lex VARCHAR(255) DEFAULT NULL, substance VARCHAR(255) DEFAULT NULL, tox VARCHAR(255) DEFAULT NULL, risk_phase VARCHAR(255) DEFAULT NULL, bio TINYINT(1) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, dar VARCHAR(255) DEFAULT NULL, znt VARCHAR(255) DEFAULT NULL, dre VARCHAR(255) DEFAULT NULL, security_mention VARCHAR(255) DEFAULT NULL, danger_mention VARCHAR(255) DEFAULT NULL, warning_mention VARCHAR(255) DEFAULT NULL, rpd DOUBLE PRECISION DEFAULT NULL, n DOUBLE PRECISION DEFAULT NULL, p DOUBLE PRECISION DEFAULT NULL, k DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, is_active TINYINT(1) DEFAULT NULL, INDEX IDX_B3BA5A5A12469DE2 (category_id), INDEX IDX_B3BA5A5A2C7E20A (parent_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_contract (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, customer_id INT NOT NULL, culture_type INT NOT NULL, added_date DATETIME NOT NULL, status INT NOT NULL, INDEX IDX_C04D3B0B61220EA6 (creator_id), INDEX IDX_C04D3B0B9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase_contract_culture (id INT AUTO_INCREMENT NOT NULL, purchase_contract_id INT NOT NULL, culture VARCHAR(255) DEFAULT NULL, volume DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, transport INT DEFAULT NULL, depot INT DEFAULT NULL, recovery INT DEFAULT NULL, divers VARCHAR(255) DEFAULT NULL, INDEX IDX_6F3C61015ECBF804 (purchase_contract_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recommendation_products (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, recommendation_id INT NOT NULL, dose DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(255) DEFAULT NULL, quantity DOUBLE PRECISION DEFAULT NULL, quantity_unit INT DEFAULT NULL, dose_edit DOUBLE PRECISION DEFAULT NULL, c_id VARCHAR(255) DEFAULT NULL, INDEX IDX_6C87D6904584665A (product_id), INDEX IDX_6C87D690D173940B (recommendation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recommendations (id INT AUTO_INCREMENT NOT NULL, exploitation_id INT NOT NULL, culture_id INT NOT NULL, status INT NOT NULL, create_at DATETIME NOT NULL, mention VARCHAR(10) DEFAULT NULL, mention_txt LONGTEXT DEFAULT NULL, pdf VARCHAR(255) DEFAULT NULL, culture_size DOUBLE PRECISION NOT NULL, checked TINYINT(1) NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_73904ED7D967A16D (exploitation_id), INDEX IDX_73904ED7B108249D (culture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE risk_phase (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, long_wording VARCHAR(255) NOT NULL, short_wording VARCHAR(255) NOT NULL, INDEX IDX_C0DF1FB44584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales (id INT AUTO_INCREMENT NOT NULL, culture VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, column1_txt VARCHAR(255) DEFAULT NULL, column2_txt VARCHAR(255) DEFAULT NULL, added_date DATETIME DEFAULT NULL, update_date DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, l1_title VARCHAR(255) DEFAULT NULL, l1_c1_value DOUBLE PRECISION DEFAULT NULL, l1_c2_value DOUBLE PRECISION DEFAULT NULL, l1_variation DOUBLE PRECISION DEFAULT NULL, l2_title VARCHAR(255) DEFAULT NULL, l2_c1_value DOUBLE PRECISION DEFAULT NULL, l2_c2_value DOUBLE PRECISION DEFAULT NULL, l2_variation DOUBLE PRECISION DEFAULT NULL, l3_title VARCHAR(255) DEFAULT NULL, l3_c1_value DOUBLE PRECISION DEFAULT NULL, l3_c2_value DOUBLE PRECISION DEFAULT NULL, l3_variation DOUBLE PRECISION DEFAULT NULL, l4_title VARCHAR(255) DEFAULT NULL, l4_c1_value DOUBLE PRECISION DEFAULT NULL, l4_c2_value DOUBLE PRECISION DEFAULT NULL, l4_variation DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales_information (id INT AUTO_INCREMENT NOT NULL, top_message LONGTEXT DEFAULT NULL, footer_message LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signature (id INT AUTO_INCREMENT NOT NULL, code_otp_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, identity VARCHAR(255) DEFAULT NULL, sign_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_AE880141C98BBE3F (code_otp_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE signature_otp (id INT AUTO_INCREMENT NOT NULL, signature_id INT NOT NULL, code VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, expired_at DATETIME NOT NULL, INDEX IDX_AA948F8ED61183A (signature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, exploitation_id INT NOT NULL, product_id INT NOT NULL, quantity DOUBLE PRECISION DEFAULT NULL, unit INT DEFAULT NULL, used_quantity DOUBLE PRECISION NOT NULL, INDEX IDX_56F79805D967A16D (exploitation_id), INDEX IDX_56F798054584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, technician_id INT NOT NULL, user_id INT NOT NULL, status INT DEFAULT 1 NOT NULL, title VARCHAR(255) NOT NULL, closed_at DATETIME DEFAULT NULL, INDEX IDX_54469DF4E6C5D496 (technician_id), INDEX IDX_54469DF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets_messages (id INT AUTO_INCREMENT NOT NULL, from_id INT NOT NULL, ticket_id INT NOT NULL, content LONGTEXT DEFAULT NULL, send_at DATETIME NOT NULL, file VARCHAR(255) DEFAULT NULL, INDEX IDX_3A9962E278CED90B (from_id), INDEX IDX_3A9962E2700047D2 (ticket_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, technician_id INT DEFAULT NULL, warehouse_id INT DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, lastname VARCHAR(30) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, city VARCHAR(30) DEFAULT NULL, password VARCHAR(255) NOT NULL, status VARCHAR(30) NOT NULL, last_activity DATETIME DEFAULT NULL, is_active TINYINT(1) DEFAULT 0, certification_phyto VARCHAR(30) DEFAULT NULL, pack VARCHAR(30) DEFAULT NULL, reset TINYINT(1) NOT NULL, company VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9E6C5D496 (technician_id), INDEX IDX_1483A5E95080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bsv_users ADD CONSTRAINT FK_168226389DD8768F FOREIGN KEY (bsv_id) REFERENCES bsv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bsv_users ADD CONSTRAINT FK_16822638C3568B40 FOREIGN KEY (customers_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D679A4BD21C FOREIGN KEY (ilot_id) REFERENCES ilots (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D6771179CD6 FOREIGN KEY (name_id) REFERENCES index_cultures (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D674F6564F9 FOREIGN KEY (precedent_id) REFERENCES index_cultures (id)');
        $this->addSql('ALTER TABLE cultures ADD CONSTRAINT FK_2C605D6783F631DB FOREIGN KEY (effluent_id) REFERENCES index_effluents (id)');
        $this->addSql('ALTER TABLE doses ADD CONSTRAINT FK_4EE4A8A84584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE doses ADD CONSTRAINT FK_4EE4A8A8BAE36EE6 FOREIGN KEY (index_culture_id) REFERENCES index_cultures (id)');
        $this->addSql('ALTER TABLE exploitation ADD CONSTRAINT FK_BEBCFB167B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ilots ADD CONSTRAINT FK_E224BAF3D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE ilots ADD CONSTRAINT FK_E224BAF3C54C8C93 FOREIGN KEY (type_id) REFERENCES index_grounds (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7FB108249D FOREIGN KEY (culture_id) REFERENCES cultures (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE interventions ADD CONSTRAINT FK_5ADBAD7F83F631DB FOREIGN KEY (effluent_id) REFERENCES index_effluents (id)');
        $this->addSql('ALTER TABLE interventions_products ADD CONSTRAINT FK_87F99D4D4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE interventions_products ADD CONSTRAINT FK_87F99D4D8EAE3863 FOREIGN KEY (intervention_id) REFERENCES interventions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mix ADD CONSTRAINT FK_55AFA881A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE mix_products ADD CONSTRAINT FK_785CCDCC4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE mix_products ADD CONSTRAINT FK_785CCDCCA6013C4A FOREIGN KEY (mix_id) REFERENCES mix (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEED61183A FOREIGN KEY (signature_id) REFERENCES signature (id)');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D6CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders_product ADD CONSTRAINT FK_223F76D64584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE panorama ADD CONSTRAINT FK_AFEA0A3A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE panorama_send ADD CONSTRAINT FK_F390FFC5C3568B40 FOREIGN KEY (customers_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE panorama_send ADD CONSTRAINT FK_F390FFC5F58D4635 FOREIGN KEY (panorama_id) REFERENCES panorama (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panorama_send ADD CONSTRAINT FK_F390FFC5F624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ppf ADD CONSTRAINT FK_205E0CD9B108249D FOREIGN KEY (culture_id) REFERENCES cultures (id)');
        $this->addSql('ALTER TABLE ppf ADD CONSTRAINT FK_205E0CD983F631DB FOREIGN KEY (effluent_id) REFERENCES index_effluents (id)');
        $this->addSql('ALTER TABLE ppf_input ADD CONSTRAINT FK_B14E40DCC47FF850 FOREIGN KEY (ppf_id) REFERENCES ppf (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ppf_input ADD CONSTRAINT FK_B14E40DC4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A2C7E20A FOREIGN KEY (parent_product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE purchase_contract ADD CONSTRAINT FK_C04D3B0B61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE purchase_contract ADD CONSTRAINT FK_C04D3B0B9395C3F3 FOREIGN KEY (customer_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE purchase_contract_culture ADD CONSTRAINT FK_6F3C61015ECBF804 FOREIGN KEY (purchase_contract_id) REFERENCES purchase_contract (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recommendation_products ADD CONSTRAINT FK_6C87D6904584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE recommendation_products ADD CONSTRAINT FK_6C87D690D173940B FOREIGN KEY (recommendation_id) REFERENCES recommendations (id)');
        $this->addSql('ALTER TABLE recommendations ADD CONSTRAINT FK_73904ED7D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE recommendations ADD CONSTRAINT FK_73904ED7B108249D FOREIGN KEY (culture_id) REFERENCES index_canevas (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE risk_phase ADD CONSTRAINT FK_C0DF1FB44584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE signature ADD CONSTRAINT FK_AE880141C98BBE3F FOREIGN KEY (code_otp_id) REFERENCES signature_otp (id)');
        $this->addSql('ALTER TABLE signature_otp ADD CONSTRAINT FK_AA948F8ED61183A FOREIGN KEY (signature_id) REFERENCES signature (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F79805D967A16D FOREIGN KEY (exploitation_id) REFERENCES exploitation (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F798054584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4E6C5D496 FOREIGN KEY (technician_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tickets_messages ADD CONSTRAINT FK_3A9962E278CED90B FOREIGN KEY (from_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tickets_messages ADD CONSTRAINT FK_3A9962E2700047D2 FOREIGN KEY (ticket_id) REFERENCES tickets (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9E6C5D496 FOREIGN KEY (technician_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E95080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bsv_users DROP FOREIGN KEY FK_168226389DD8768F');
        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7FB108249D');
        $this->addSql('ALTER TABLE ppf DROP FOREIGN KEY FK_205E0CD9B108249D');
        $this->addSql('ALTER TABLE ilots DROP FOREIGN KEY FK_E224BAF3D967A16D');
        $this->addSql('ALTER TABLE recommendations DROP FOREIGN KEY FK_73904ED7D967A16D');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F79805D967A16D');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D679A4BD21C');
        $this->addSql('ALTER TABLE recommendations DROP FOREIGN KEY FK_73904ED7B108249D');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D6771179CD6');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D674F6564F9');
        $this->addSql('ALTER TABLE doses DROP FOREIGN KEY FK_4EE4A8A8BAE36EE6');
        $this->addSql('ALTER TABLE cultures DROP FOREIGN KEY FK_2C605D6783F631DB');
        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7F83F631DB');
        $this->addSql('ALTER TABLE ppf DROP FOREIGN KEY FK_205E0CD983F631DB');
        $this->addSql('ALTER TABLE ilots DROP FOREIGN KEY FK_E224BAF3C54C8C93');
        $this->addSql('ALTER TABLE interventions_products DROP FOREIGN KEY FK_87F99D4D8EAE3863');
        $this->addSql('ALTER TABLE mix_products DROP FOREIGN KEY FK_785CCDCCA6013C4A');
        $this->addSql('ALTER TABLE orders_product DROP FOREIGN KEY FK_223F76D6CFFE9AD6');
        $this->addSql('ALTER TABLE panorama_send DROP FOREIGN KEY FK_F390FFC5F58D4635');
        $this->addSql('ALTER TABLE ppf_input DROP FOREIGN KEY FK_B14E40DCC47FF850');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE doses DROP FOREIGN KEY FK_4EE4A8A84584665A');
        $this->addSql('ALTER TABLE interventions DROP FOREIGN KEY FK_5ADBAD7F4584665A');
        $this->addSql('ALTER TABLE interventions_products DROP FOREIGN KEY FK_87F99D4D4584665A');
        $this->addSql('ALTER TABLE mix_products DROP FOREIGN KEY FK_785CCDCC4584665A');
        $this->addSql('ALTER TABLE orders_product DROP FOREIGN KEY FK_223F76D64584665A');
        $this->addSql('ALTER TABLE ppf_input DROP FOREIGN KEY FK_B14E40DC4584665A');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A2C7E20A');
        $this->addSql('ALTER TABLE recommendation_products DROP FOREIGN KEY FK_6C87D6904584665A');
        $this->addSql('ALTER TABLE risk_phase DROP FOREIGN KEY FK_C0DF1FB44584665A');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F798054584665A');
        $this->addSql('ALTER TABLE purchase_contract_culture DROP FOREIGN KEY FK_6F3C61015ECBF804');
        $this->addSql('ALTER TABLE recommendation_products DROP FOREIGN KEY FK_6C87D690D173940B');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEED61183A');
        $this->addSql('ALTER TABLE signature_otp DROP FOREIGN KEY FK_AA948F8ED61183A');
        $this->addSql('ALTER TABLE signature DROP FOREIGN KEY FK_AE880141C98BBE3F');
        $this->addSql('ALTER TABLE tickets_messages DROP FOREIGN KEY FK_3A9962E2700047D2');
        $this->addSql('ALTER TABLE bsv_users DROP FOREIGN KEY FK_16822638C3568B40');
        $this->addSql('ALTER TABLE exploitation DROP FOREIGN KEY FK_BEBCFB167B3B43D');
        $this->addSql('ALTER TABLE mix DROP FOREIGN KEY FK_55AFA881A76ED395');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE61220EA6');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE9395C3F3');
        $this->addSql('ALTER TABLE panorama DROP FOREIGN KEY FK_AFEA0A3A7E3C61F9');
        $this->addSql('ALTER TABLE panorama_send DROP FOREIGN KEY FK_F390FFC5C3568B40');
        $this->addSql('ALTER TABLE panorama_send DROP FOREIGN KEY FK_F390FFC5F624B39D');
        $this->addSql('ALTER TABLE purchase_contract DROP FOREIGN KEY FK_C04D3B0B61220EA6');
        $this->addSql('ALTER TABLE purchase_contract DROP FOREIGN KEY FK_C04D3B0B9395C3F3');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4E6C5D496');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4A76ED395');
        $this->addSql('ALTER TABLE tickets_messages DROP FOREIGN KEY FK_3A9962E278CED90B');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9E6C5D496');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E95080ECDE');
        $this->addSql('DROP TABLE bsv');
        $this->addSql('DROP TABLE bsv_users');
        $this->addSql('DROP TABLE cultures');
        $this->addSql('DROP TABLE doses');
        $this->addSql('DROP TABLE exploitation');
        $this->addSql('DROP TABLE ilots');
        $this->addSql('DROP TABLE index_canevas');
        $this->addSql('DROP TABLE index_cultures');
        $this->addSql('DROP TABLE index_effluents');
        $this->addSql('DROP TABLE index_grounds');
        $this->addSql('DROP TABLE interventions');
        $this->addSql('DROP TABLE interventions_products');
        $this->addSql('DROP TABLE mix');
        $this->addSql('DROP TABLE mix_products');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_product');
        $this->addSql('DROP TABLE panorama');
        $this->addSql('DROP TABLE panorama_send');
        $this->addSql('DROP TABLE ppf');
        $this->addSql('DROP TABLE ppf_input');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE purchase_contract');
        $this->addSql('DROP TABLE purchase_contract_culture');
        $this->addSql('DROP TABLE recommendation_products');
        $this->addSql('DROP TABLE recommendations');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE risk_phase');
        $this->addSql('DROP TABLE sales');
        $this->addSql('DROP TABLE sales_information');
        $this->addSql('DROP TABLE signature');
        $this->addSql('DROP TABLE signature_otp');
        $this->addSql('DROP TABLE stocks');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('DROP TABLE tickets_messages');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE warehouse');
    }
}
