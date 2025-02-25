<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Override;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225045815 extends AbstractMigration
{
    #[Override]
    public function getDescription(): string
    {
        return '';
    }

    #[Override]
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE finance_categories (id UUID NOT NULL, name VARCHAR(255) NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unique_user_id_category_name ON finance_categories (user_id, name)');
        $this->addSql('CREATE TABLE finance_transactions (id UUID NOT NULL, amount INT NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, wallet_id UUID NOT NULL, category_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E6E13CA5712520F3 ON finance_transactions (wallet_id)');
        $this->addSql('CREATE INDEX IDX_E6E13CA512469DE2 ON finance_transactions (category_id)');
        $this->addSql('ALTER TABLE finance_transactions ADD CONSTRAINT FK_E6E13CA5712520F3 FOREIGN KEY (wallet_id) REFERENCES finance_wallets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE finance_transactions ADD CONSTRAINT FK_E6E13CA512469DE2 FOREIGN KEY (category_id) REFERENCES finance_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    #[Override]
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE finance_transactions DROP CONSTRAINT FK_E6E13CA5712520F3');
        $this->addSql('ALTER TABLE finance_transactions DROP CONSTRAINT FK_E6E13CA512469DE2');
        $this->addSql('DROP TABLE finance_categories');
        $this->addSql('DROP TABLE finance_transactions');
    }
}
