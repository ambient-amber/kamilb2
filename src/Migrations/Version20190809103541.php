<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190809103541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_translation DROP FOREIGN KEY FK_2EEA2F0869E94F17');
        $this->addSql('ALTER TABLE article_translation DROP FOREIGN KEY FK_2EEA2F088F3EC46');
        $this->addSql('DROP INDEX IDX_2EEA2F0869E94F17 ON article_translation');
        $this->addSql('DROP INDEX IDX_2EEA2F088F3EC46 ON article_translation');
        $this->addSql('ALTER TABLE article_translation DROP language_text_id_id, DROP article_id_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_translation ADD language_text_id_id INT NOT NULL, ADD article_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE article_translation ADD CONSTRAINT FK_2EEA2F0869E94F17 FOREIGN KEY (language_text_id_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE article_translation ADD CONSTRAINT FK_2EEA2F088F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_2EEA2F0869E94F17 ON article_translation (language_text_id_id)');
        $this->addSql('CREATE INDEX IDX_2EEA2F088F3EC46 ON article_translation (article_id_id)');
    }
}
