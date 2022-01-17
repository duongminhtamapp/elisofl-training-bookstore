<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220116125538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE book_order');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE book_order (book_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(book_id, order_id))');
        $this->addSql('CREATE INDEX idx_fbeb86e116a2b381 ON book_order (book_id)');
        $this->addSql('CREATE INDEX idx_fbeb86e18d9f6d38 ON book_order (order_id)');
        $this->addSql('ALTER TABLE book_order ADD CONSTRAINT fk_fbeb86e116a2b381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_order ADD CONSTRAINT fk_fbeb86e18d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
