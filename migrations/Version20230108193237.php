<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230108193237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__goal AS SELECT id, player_id, game_id, minutes, seconds, is_penalty_kick FROM goal');
        $this->addSql('DROP TABLE goal');
        $this->addSql('CREATE TABLE goal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, game_id INTEGER NOT NULL, team_id INTEGER NOT NULL, minutes INTEGER NOT NULL, seconds INTEGER NOT NULL, is_penalty_kick BOOLEAN NOT NULL, CONSTRAINT FK_FCDCEB2E99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FCDCEB2EE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FCDCEB2E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO goal (id, player_id, game_id, minutes, seconds, is_penalty_kick) SELECT id, player_id, game_id, minutes, seconds, is_penalty_kick FROM __temp__goal');
        $this->addSql('DROP TABLE __temp__goal');
        $this->addSql('CREATE INDEX IDX_FCDCEB2EE48FD905 ON goal (game_id)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E99E6F5DF ON goal (player_id)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E296CD8AE ON goal (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__goal AS SELECT id, player_id, game_id, minutes, seconds, is_penalty_kick FROM goal');
        $this->addSql('DROP TABLE goal');
        $this->addSql('CREATE TABLE goal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, game_id INTEGER NOT NULL, minutes INTEGER NOT NULL, seconds INTEGER NOT NULL, is_penalty_kick BOOLEAN NOT NULL, CONSTRAINT FK_FCDCEB2E99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FCDCEB2EE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO goal (id, player_id, game_id, minutes, seconds, is_penalty_kick) SELECT id, player_id, game_id, minutes, seconds, is_penalty_kick FROM __temp__goal');
        $this->addSql('DROP TABLE __temp__goal');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E99E6F5DF ON goal (player_id)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2EE48FD905 ON goal (game_id)');
    }
}
