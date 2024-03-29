<?php

declare(strict_types=1);

namespace BLRLive\Models;

/*

create table Games (
    id int primary key AUTO_INCREMENT,
    match_id int not null,
    status enum('team1', 'team2', 'draw') not null,
    finish_time datetime not null default CURRENT_TIMESTAMP,

    foreign key (match_id) references Matches(id) on delete cascade
);

*/

class Game extends BaseModel
{
    public string $time;

    public function __construct(
        public /*readonly*/ int $id,
        public int $match,
        public string $status,
    ) {
        $this->time = Database::execute_query(
            'select finish_time from Games where id = ?',
            [$id]
        )->fetch_assoc()['finish_time'];
    }

    public static function create(int $match, string $status): Game
    {
        $db = Database::connect();
        Database::execute_query(
            'insert into Games(match_id, status) values (?, ?)',
            [$match, $status],
            $db
        );
        $id = $db->insert_id;
        $db->commit();

        return new Game(
            id: $id,
            match: $match,
            status: $status
        );
    }

    public static function fromRow(array $row): Game
    {
        return new Game(
            id: $row['id'],
            match: $row['match_id'],
            status: $row['status']
        );
    }

    public static function get(string $id): ?Game
    {
        if (!is_numeric($id)) {
            return null;
        }

        $r = Database::execute_query('select * from Games where id = ?', [$id])->fetch_assoc();
        if (!$r) {
            return null;
        }

        return Game::fromRow($r);
    }

    public static function getForMatch(int $match): array
    {
        $r = Database::execute_query('select * from Games where match_id = ?', [$match]);

        $games = [];
        foreach ($r as $row) {
            $games[] = Game::fromRow($row);
        }

        return $games;
    }

    public function delete(): void
    {
        $db = Database::connect();
        Database::execute_query('delete from Games where id = ?', [$this->id], $db);
        $db->commit();
    }

    public function jsonSerialize(): \BLRLive\Schemas\Game
    {
        return new \BLRLive\Schemas\Game(
            id: $this->id,
            match: $this->match,
            status: $this->status,
            time: $this->time
        );
    }
}
