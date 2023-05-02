<?php

class GameState
{
    public int $health;
    public int $killedMonsters;

    public function __construct(int $health, int $killedMonsters)
    {
        $this->health = $health;
        $this->killedMonsters = $killedMonsters;
    }

    public function __toString(): string
    {
        return sprintf("Health: %s\nKilled Monsters: %s\n", $this->health, $this->killedMonsters);
    }
}

class GameOriginator
{
    // Стан містить здоров’я та к-ть вбитих монстрів
    private GameState $state;

    public function __construct()
    {
        $this->state = new GameState(100, 0);
    }

    public function play(): void
    {
        // Імітуємо процес гри – здоров’я повільно погіршується, а монстрів стає все менше
        printf($this->state);
        $this->state = new GameState(intval($this->state->health * 0.9), $this->state->killedMonsters + 2);
    }

    public function gameSave(): GameMemento
    {
        return new GameMemento($this->state);
    }

    public function loadGame(GameMemento $memento): void
    {
        $this->state = $memento->getState();
    }
}

class GameMemento
{
    private readonly GameState $state;

    public function __construct(GameState $state)
    {
        $this->state = $state;
    }

    public function getState(): GameState
    {
        return $this->state;
    }
}

class Caretaker
{
    private readonly GameOriginator $game;
    private readonly SplStack $quickSaves;

    public function __construct()
    {
        $this->game = new GameOriginator();
        $this->quickSaves = new SplStack();
    }

    public function shootThatDumbAss(): void
    {
        $this->game->play();
    }

    public function f5(): void
    {
        $this->quickSaves->push($this->game->gameSave());
    }

    public function f9(): void
    {
        $this->game->loadGame($this->quickSaves->pop());
    }
}

$careTaker = new Caretaker();
$careTaker->f5();
$careTaker->shootThatDumbAss();
$careTaker->f5();
$careTaker->shootThatDumbAss();
$careTaker->shootThatDumbAss();
$careTaker->shootThatDumbAss();
$careTaker->shootThatDumbAss();
$careTaker->f9();
$careTaker->shootThatDumbAss();
