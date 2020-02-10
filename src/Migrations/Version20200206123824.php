<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200206123824 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meta_data ADD language_id INT NOT NULL, ADD sort INT DEFAULT 0 NOT NULL, ADD is_template TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE meta_data ADD CONSTRAINT FK_3E55802082F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_3E55802082F1BAF4 ON meta_data (language_id)');
        $this->addSql('CREATE INDEX sort_index ON meta_data (sort)');
        $this->addSql('CREATE INDEX url_language_uniq ON meta_data (url, language_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meta_data DROP FOREIGN KEY FK_3E55802082F1BAF4');
        $this->addSql('DROP INDEX IDX_3E55802082F1BAF4 ON meta_data');
        $this->addSql('DROP INDEX sort_index ON meta_data');
        $this->addSql('DROP INDEX url_language_uniq ON meta_data');
        $this->addSql('ALTER TABLE meta_data DROP language_id, DROP sort, DROP is_template');
    }
}
