<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125045756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC748416DEB03A');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC748416DEB03A FOREIGN KEY (current_lesson_id) REFERENCES lesson (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC748416DEB03A');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC748416DEB03A FOREIGN KEY (current_lesson_id) REFERENCES lesson (id)');
    }
}
