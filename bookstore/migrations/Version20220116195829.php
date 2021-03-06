<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220116195829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f5299398a76ed395');
        $this->addSql('DROP INDEX idx_f5299398a76ed395');
        $this->addSql('ALTER TABLE "order" DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f5299398a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f5299398a76ed395 ON "order" (user_id)');
    }
}
