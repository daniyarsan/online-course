<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114180222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        // Add your category data here
        $this->addSql("INSERT INTO category (name) VALUES ('Тесты по книгам')");
        $this->addSql("INSERT INTO category (name) VALUES ('Финансы')");
        $this->addSql("INSERT INTO category (name) VALUES ('Личные навыки')");
        $this->addSql("INSERT INTO category (name) VALUES ('Проф.навыки')");
        $this->addSql("INSERT INTO category (name) VALUES ('Тесты для кандидатур')");
        $this->addSql("INSERT INTO category (name) VALUES ('Другое')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE category');
    }
}