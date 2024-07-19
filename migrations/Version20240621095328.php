<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240621095328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D79F37AE5');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE post RENAME INDEX idx_5a8a6c8d79f37ae5 TO IDX_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B79F37AE5');
        $this->addSql('ALTER TABLE topic CHANGE date_creation_topic date_creation_topic DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE topic RENAME INDEX idx_9d40de1b79f37ae5 TO IDX_9D40DE1BA76ED395');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1BA76ED395');
        $this->addSql('ALTER TABLE topic CHANGE date_creation_topic date_creation_topic DATE NOT NULL');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B79F37AE5 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE topic RENAME INDEX idx_9d40de1ba76ed395 TO IDX_9D40DE1B79F37AE5');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D79F37AE5 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE post RENAME INDEX idx_5a8a6c8da76ed395 TO IDX_5A8A6C8D79F37AE5');
    }
}
