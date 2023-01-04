<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230101201823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, main_referee_id INTEGER NOT NULL, date DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , viewers INTEGER NOT NULL, stadium VARCHAR(255) NOT NULL, CONSTRAINT FK_232B318C72F008C1 FOREIGN KEY (main_referee_id) REFERENCES referee (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_232B318C72F008C1 ON game (main_referee_id)');
        $this->addSql('CREATE TABLE game_referee (game_id INTEGER NOT NULL, referee_id INTEGER NOT NULL, PRIMARY KEY(game_id, referee_id), CONSTRAINT FK_43CDCC6DE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_43CDCC6D4A087CA2 FOREIGN KEY (referee_id) REFERENCES referee (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_43CDCC6DE48FD905 ON game_referee (game_id)');
        $this->addSql('CREATE INDEX IDX_43CDCC6D4A087CA2 ON game_referee (referee_id)');
        $this->addSql('CREATE TABLE game_team (game_id INTEGER NOT NULL, team_id INTEGER NOT NULL, PRIMARY KEY(game_id, team_id), CONSTRAINT FK_2FF5CA33E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2FF5CA33296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2FF5CA33E48FD905 ON game_team (game_id)');
        $this->addSql('CREATE INDEX IDX_2FF5CA33296CD8AE ON game_team (team_id)');
        $this->addSql('CREATE TABLE game_player (game_id INTEGER NOT NULL, player_id INTEGER NOT NULL, PRIMARY KEY(game_id, player_id), CONSTRAINT FK_E52CD7ADE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E52CD7AD99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E52CD7ADE48FD905 ON game_player (game_id)');
        $this->addSql('CREATE INDEX IDX_E52CD7AD99E6F5DF ON game_player (player_id)');
        $this->addSql('CREATE TABLE goal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, game_id INTEGER NOT NULL, minutes INTEGER NOT NULL, seconds INTEGER NOT NULL, is_penalty_kick BOOLEAN NOT NULL, CONSTRAINT FK_FCDCEB2E99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FCDCEB2EE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2E99E6F5DF ON goal (player_id)');
        $this->addSql('CREATE INDEX IDX_FCDCEB2EE48FD905 ON goal (game_id)');
        $this->addSql('CREATE TABLE goal_player (goal_id INTEGER NOT NULL, player_id INTEGER NOT NULL, PRIMARY KEY(goal_id, player_id), CONSTRAINT FK_29E0F21667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_29E0F2199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_29E0F21667D1AFE ON goal_player (goal_id)');
        $this->addSql('CREATE INDEX IDX_29E0F2199E6F5DF ON goal_player (player_id)');
        $this->addSql('CREATE TABLE penalty (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, game_id INTEGER NOT NULL, minutes INTEGER NOT NULL, seconds INTEGER NOT NULL, CONSTRAINT FK_AFE28FD899E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AFE28FD8E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AFE28FD899E6F5DF ON penalty (player_id)');
        $this->addSql('CREATE INDEX IDX_AFE28FD8E48FD905 ON penalty (game_id)');
        $this->addSql('CREATE TABLE player (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, team_id INTEGER NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, number INTEGER NOT NULL, CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_98197A65296CD8AE ON player (team_id)');
        $this->addSql('CREATE INDEX IDX_98197A6596901F54 ON player (number)');
        $this->addSql('CREATE TABLE referee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE substitution (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_substituted_id INTEGER NOT NULL, new_player_id INTEGER NOT NULL, game_id INTEGER NOT NULL, minutes INTEGER NOT NULL, seconds INTEGER NOT NULL, CONSTRAINT FK_C7C90AE0FA13C825 FOREIGN KEY (player_substituted_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C7C90AE0AB79F0B0 FOREIGN KEY (new_player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C7C90AE0E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C7C90AE0FA13C825 ON substitution (player_substituted_id)');
        $this->addSql('CREATE INDEX IDX_C7C90AE0AB79F0B0 ON substitution (new_player_id)');
        $this->addSql('CREATE INDEX IDX_C7C90AE0E48FD905 ON substitution (game_id)');
        $this->addSql('CREATE TABLE team (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F5E237E06 ON team (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_referee');
        $this->addSql('DROP TABLE game_team');
        $this->addSql('DROP TABLE game_player');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE goal_player');
        $this->addSql('DROP TABLE penalty');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE referee');
        $this->addSql('DROP TABLE substitution');
        $this->addSql('DROP TABLE team');
    }
}
