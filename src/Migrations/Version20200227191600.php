<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200227191600 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meta_data DROP INDEX url_language_uniq, ADD UNIQUE INDEX url_language_unique (url, language_id)');
        $this->addSql('ALTER TABLE meta_data DROP FOREIGN KEY FK_3E55802082F1BAF4');
        $this->addSql('DROP INDEX idx_3e55802082f1baf4 ON meta_data');
        $this->addSql('CREATE INDEX language_index ON meta_data (language_id)');
        $this->addSql('ALTER TABLE meta_data ADD CONSTRAINT FK_3E55802082F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meta_data DROP INDEX url_language_unique, ADD INDEX url_language_uniq (url, language_id)');
        $this->addSql('ALTER TABLE meta_data DROP FOREIGN KEY FK_3E55802082F1BAF4');
        $this->addSql('DROP INDEX language_index ON meta_data');
        $this->addSql('CREATE INDEX IDX_3E55802082F1BAF4 ON meta_data (language_id)');
        $this->addSql('ALTER TABLE meta_data ADD CONSTRAINT FK_3E55802082F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }
}
