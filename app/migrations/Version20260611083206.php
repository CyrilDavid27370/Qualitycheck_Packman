<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260611083206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE certificate (id INT AUTO_INCREMENT NOT NULL, delivery_site VARCHAR(50) NOT NULL, gps_ref VARCHAR(50) NOT NULL, round VARCHAR(50) NOT NULL, project VARCHAR(50) NOT NULL, delivery_date DATE NOT NULL, has_derogation TINYINT NOT NULL, is_hare TINYINT NOT NULL, association_derogation VARCHAR(100) DEFAULT NULL, restrictive VARCHAR(50) DEFAULT NULL, comodif VARCHAR(100) DEFAULT NULL, comments LONGTEXT DEFAULT NULL, ready_to_deliver TINYINT NOT NULL, created_at DATETIME NOT NULL, user_id INT NOT NULL, category_type_id INT NOT NULL, INDEX IDX_219CDA4AA76ED395 (user_id), INDEX IDX_219CDA4A294CCED (category_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE criterion (id INT AUTO_INCREMENT NOT NULL, item_number VARCHAR(50) NOT NULL, label VARCHAR(1000) NOT NULL, category_type_id INT NOT NULL, INDEX IDX_7C822271294CCED (category_type_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, rating VARCHAR(10) NOT NULL, criterion_id INT NOT NULL, certificate_id INT NOT NULL, INDEX IDX_1323A57597766307 (criterion_id), INDEX IDX_1323A57599223FFD (certificate_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE certificate ADD CONSTRAINT FK_219CDA4A294CCED FOREIGN KEY (category_type_id) REFERENCES category_type (id)');
        $this->addSql('ALTER TABLE criterion ADD CONSTRAINT FK_7C822271294CCED FOREIGN KEY (category_type_id) REFERENCES category_type (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57597766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A57599223FFD FOREIGN KEY (certificate_id) REFERENCES certificate (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4AA76ED395');
        $this->addSql('ALTER TABLE certificate DROP FOREIGN KEY FK_219CDA4A294CCED');
        $this->addSql('ALTER TABLE criterion DROP FOREIGN KEY FK_7C822271294CCED');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57597766307');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A57599223FFD');
        $this->addSql('DROP TABLE certificate');
        $this->addSql('DROP TABLE criterion');
        $this->addSql('DROP TABLE evaluation');
    }
}
