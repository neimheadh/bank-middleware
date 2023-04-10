<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410131850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Database initialization';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE app_account_account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_account_bank_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_account_transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_import_profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_thirdparty_thirdparty_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE budget_budget_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_account_account (id INT NOT NULL, currency_id CHAR(8) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(256) DEFAULT \'\' NOT NULL, balance DOUBLE PRECISION DEFAULT \'0\' NOT NULL, code CHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EEC7676577153098 ON app_account_account (code)');
        $this->addSql('CREATE INDEX IDX_EEC7676538248176 ON app_account_account (currency_id)');
        $this->addSql('CREATE TABLE app_account_bank (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(256) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE app_account_transaction (id INT NOT NULL, account_id INT NOT NULL, currency_id CHAR(8) NOT NULL, third_party_id INT DEFAULT NULL, budget_id INT DEFAULT NULL, name VARCHAR(256) DEFAULT NULL, process_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, transaction_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, balance DOUBLE PRECISION DEFAULT \'0\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FDCE11179B6B5FBA ON app_account_transaction (account_id)');
        $this->addSql('CREATE INDEX IDX_FDCE111738248176 ON app_account_transaction (currency_id)');
        $this->addSql('CREATE INDEX IDX_FDCE111754C4149C ON app_account_transaction (third_party_id)');
        $this->addSql('CREATE INDEX IDX_FDCE111736ABA6B8 ON app_account_transaction (budget_id)');
        $this->addSql('CREATE TABLE app_currency_currency (code CHAR(8) NOT NULL, is_default BOOLEAN DEFAULT NULL, decimal_digits INT DEFAULT NULL, name VARCHAR(256) DEFAULT NULL, native_symbol VARCHAR(8) DEFAULT NULL, rounded INT DEFAULT NULL, symbol VARCHAR(8) DEFAULT NULL, usd_exchange_rate DOUBLE PRECISION DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, plural_name VARCHAR(256) DEFAULT NULL, PRIMARY KEY(code))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F87B73B3F5628617 ON app_currency_currency (is_default)');
        $this->addSql('CREATE TABLE app_import_profile (id INT NOT NULL, processor VARCHAR(128) NOT NULL, processor_configuration JSON DEFAULT NULL, reader VARCHAR(128) NOT NULL, reader_configuration JSON DEFAULT NULL, writer VARCHAR(128) NOT NULL, writer_configuration JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(256) DEFAULT \'\' NOT NULL, code CHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DBD911D77153098 ON app_import_profile (code)');
        $this->addSql('CREATE TABLE app_thirdparty_thirdparty (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(256) DEFAULT \'\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE budget_budget (id INT NOT NULL, currency_id CHAR(8) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(256) DEFAULT \'\' NOT NULL, balance DOUBLE PRECISION DEFAULT \'0\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2747B1C738248176 ON budget_budget (currency_id)');
        $this->addSql('ALTER TABLE app_account_account ADD CONSTRAINT FK_EEC7676538248176 FOREIGN KEY (currency_id) REFERENCES app_currency_currency (code) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_account_transaction ADD CONSTRAINT FK_FDCE11179B6B5FBA FOREIGN KEY (account_id) REFERENCES app_account_account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_account_transaction ADD CONSTRAINT FK_FDCE111738248176 FOREIGN KEY (currency_id) REFERENCES app_currency_currency (code) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_account_transaction ADD CONSTRAINT FK_FDCE111754C4149C FOREIGN KEY (third_party_id) REFERENCES app_thirdparty_thirdparty (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_account_transaction ADD CONSTRAINT FK_FDCE111736ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget_budget (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE budget_budget ADD CONSTRAINT FK_2747B1C738248176 FOREIGN KEY (currency_id) REFERENCES app_currency_currency (code) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE app_account_account_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_account_bank_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_account_transaction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_import_profile_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_thirdparty_thirdparty_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE budget_budget_id_seq CASCADE');
        $this->addSql('ALTER TABLE app_account_account DROP CONSTRAINT FK_EEC7676538248176');
        $this->addSql('ALTER TABLE app_account_transaction DROP CONSTRAINT FK_FDCE11179B6B5FBA');
        $this->addSql('ALTER TABLE app_account_transaction DROP CONSTRAINT FK_FDCE111738248176');
        $this->addSql('ALTER TABLE app_account_transaction DROP CONSTRAINT FK_FDCE111754C4149C');
        $this->addSql('ALTER TABLE app_account_transaction DROP CONSTRAINT FK_FDCE111736ABA6B8');
        $this->addSql('ALTER TABLE budget_budget DROP CONSTRAINT FK_2747B1C738248176');
        $this->addSql('DROP TABLE app_account_account');
        $this->addSql('DROP TABLE app_account_bank');
        $this->addSql('DROP TABLE app_account_transaction');
        $this->addSql('DROP TABLE app_currency_currency');
        $this->addSql('DROP TABLE app_import_profile');
        $this->addSql('DROP TABLE app_thirdparty_thirdparty');
        $this->addSql('DROP TABLE budget_budget');
    }
}
