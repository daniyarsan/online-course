<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124173255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesson_result (id INT AUTO_INCREMENT NOT NULL, lesson_id INT NOT NULL, user_course_id INT NOT NULL, INDEX IDX_60FC3144CDF80196 (lesson_id), INDEX IDX_60FC314459FC4476 (user_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_course (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, course_id INT NOT NULL, current_lesson_id INT DEFAULT NULL, INDEX IDX_73CC7484A76ED395 (user_id), INDEX IDX_73CC7484591CC992 (course_id), INDEX IDX_73CC748416DEB03A (current_lesson_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lesson_result ADD CONSTRAINT FK_60FC3144CDF80196 FOREIGN KEY (lesson_id) REFERENCES lesson (id)');
        $this->addSql('ALTER TABLE lesson_result ADD CONSTRAINT FK_60FC314459FC4476 FOREIGN KEY (user_course_id) REFERENCES user_course (id)');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC7484A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC7484591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC748416DEB03A FOREIGN KEY (current_lesson_id) REFERENCES lesson (id)');
        $this->addSql('ALTER TABLE chapter ADD sorting INT NOT NULL');
        $this->addSql('ALTER TABLE lesson ADD sorting INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesson_result DROP FOREIGN KEY FK_60FC3144CDF80196');
        $this->addSql('ALTER TABLE lesson_result DROP FOREIGN KEY FK_60FC314459FC4476');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC7484A76ED395');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC7484591CC992');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC748416DEB03A');
        $this->addSql('DROP TABLE lesson_result');
        $this->addSql('DROP TABLE user_course');
        $this->addSql('ALTER TABLE chapter DROP sorting');
        $this->addSql('ALTER TABLE lesson DROP sorting');
    }
}
