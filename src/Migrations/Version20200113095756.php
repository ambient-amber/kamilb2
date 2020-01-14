<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200113095756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_category_translation ADD language_id INT NOT NULL');
        $this->addSql('ALTER TABLE article_category_translation ADD CONSTRAINT FK_7FD8925082F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_7FD8925082F1BAF4 ON article_category_translation (language_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_category_translation DROP FOREIGN KEY FK_7FD8925082F1BAF4');
        $this->addSql('DROP INDEX IDX_7FD8925082F1BAF4 ON article_category_translation');
        $this->addSql('ALTER TABLE article_category_translation DROP language_id');
    }
}
