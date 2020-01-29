<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200129071049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE banner ADD on_mobile TINYINT(1) NOT NULL, ADD on_tablet TINYINT(1) NOT NULL, ADD on_desktop TINYINT(1) NOT NULL');
        $this->addSql('CREATE INDEX is_mobile_index ON banner (on_mobile)');
        $this->addSql('CREATE INDEX is_tablet_index ON banner (on_tablet)');
        $this->addSql('CREATE INDEX is_desktop_index ON banner (on_desktop)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX is_mobile_index ON banner');
        $this->addSql('DROP INDEX is_tablet_index ON banner');
        $this->addSql('DROP INDEX is_desktop_index ON banner');
        $this->addSql('ALTER TABLE banner DROP on_mobile, DROP on_tablet, DROP on_desktop');
    }
}
