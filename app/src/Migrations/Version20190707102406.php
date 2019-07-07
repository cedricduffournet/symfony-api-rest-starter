<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190707102406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A647923D6A298');
        $this->addSql('ALTER TABLE fos_user CHANGE civility_id civility_id INT NOT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A647923D6A298 FOREIGN KEY (civility_id) REFERENCES civility (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A647923D6A298');
        $this->addSql('ALTER TABLE fos_user CHANGE civility_id civility_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A647923D6A298 FOREIGN KEY (civility_id) REFERENCES civility (id) ON DELETE CASCADE');
    }
}
