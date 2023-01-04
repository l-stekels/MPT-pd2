<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230104214548 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE statistics (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, team_id INTEGER NOT NULL, goals_in_time INTEGER NOT NULL, goals_in_overtime INTEGER NOT NULL, goals_lost_in_time INTEGER NOT NULL, goals_lost_in_overtime INTEGER NOT NULL, points INTEGER NOT NULL, win BOOLEAN NOT NULL, overtime BOOLEAN NOT NULL, CONSTRAINT FK_E2D38B22296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E2D38B22296CD8AE ON statistics (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE statistics');
    }
}
