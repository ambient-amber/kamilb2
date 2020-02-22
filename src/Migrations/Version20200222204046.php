<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200222204046 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_category CHANGE url url VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE article CHANGE image_name image_name VARCHAR(191) DEFAULT NULL, CHANGE image_hash image_hash VARCHAR(191) DEFAULT NULL, CHANGE url url VARCHAR(191) NOT NULL, CHANGE source source VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE banner CHANGE link link VARCHAR(191) DEFAULT NULL, CHANGE image_name image_name VARCHAR(191) NOT NULL, CHANGE image_hash image_hash VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE content_block CHANGE text_id text_id VARCHAR(191) NOT NULL, CHANGE description description VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE language CHANGE text_id text_id VARCHAR(191) NOT NULL, CHANGE title title VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE meta_data CHANGE url url VARCHAR(191) NOT NULL, CHANGE title title VARCHAR(191) DEFAULT NULL, CHANGE key_words key_words VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE page CHANGE url url VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE popular_shop CHANGE title title VARCHAR(191) NOT NULL, CHANGE url url VARCHAR(191) DEFAULT NULL, CHANGE image_hash image_hash VARCHAR(191) DEFAULT NULL, CHANGE image_name image_name VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE article_category_translation CHANGE title title VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE article_translation CHANGE title title VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE page_translation CHANGE title title VARCHAR(191) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article CHANGE image_name image_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE image_hash image_hash VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE url url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE source source VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE article_category CHANGE url url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE article_category_translation CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE article_translation CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE banner CHANGE link link VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE image_name image_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE image_hash image_hash VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE content_block CHANGE text_id text_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE language CHANGE text_id text_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE meta_data CHANGE url url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE title title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE key_words key_words VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE page CHANGE url url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE page_translation CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE popular_shop CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE url url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE image_hash image_hash VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
