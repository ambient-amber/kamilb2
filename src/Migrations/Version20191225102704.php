<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191225102704 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX url_index ON page (url)');
        $this->addSql('CREATE INDEX pub_index ON article (pub)');
        $this->addSql('CREATE INDEX url_index ON article (url)');
        $this->addSql('CREATE INDEX text_id_index ON content_block (text_id)');
        $this->addSql('CREATE INDEX url_index ON popular_shop (url)');
        $this->addSql('CREATE INDEX pub_index ON popular_shop (pub)');
        $this->addSql('CREATE INDEX email_index ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX pub_index ON article');
        $this->addSql('DROP INDEX url_index ON article');
        $this->addSql('DROP INDEX text_id_index ON content_block');
        $this->addSql('DROP INDEX url_index ON page');
        $this->addSql('DROP INDEX url_index ON popular_shop');
        $this->addSql('DROP INDEX pub_index ON popular_shop');
        $this->addSql('DROP INDEX email_index ON user');
    }
}
