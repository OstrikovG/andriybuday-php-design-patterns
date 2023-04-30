<?php

interface ICommand
{
    // Кожна Команда має метод для її запуску
    public function execute(): void;
}

class Team
{
    public function completeProject(array $requirements)
    {
        foreach ($requirements as $requirement) {
            printf("User Story (%s) has been completed.\n", $requirement);
        }
    }
}

class HeroDeveloper
{
    public function doAllHardWork(string $projectName)
    {
        printf("Hero developer completed project (%s) without requirements in manner of couple hours!\n", $projectName);
    }
}

// Приклад однієї із Команд до виконання
class YouAsProjectManagerCommand implements ICommand
{
    protected Team $team;
    protected array $requirements;

    public function __construct(Team $team, array $requirements)
    {
        $this->team = $team;
        $this->requirements = $requirements;
    }

    public function execute(): void
    {
        // Реалізація делегує роботу до потрібного отримувача
        $this->team->completeProject($this->requirements);
    }
}

// І ще один приклад
class HeroDeveloperCommand implements ICommand
{
    protected HeroDeveloper $heroDeveloper;
    protected string $projectName;

    public function __construct(HeroDeveloper $heroDeveloper, string $projectName)
    {
        $this->heroDeveloper = $heroDeveloper;
        $this->projectName = $projectName;
    }

    public function execute(): void
    {
        // Реалізація делегує роботу до потрібного отримувача
        $this->heroDeveloper->doAllHardWork($this->projectName);
    }
}

class Customer
{
    protected array $commands = [];

    public function addCommand(ICommand $command): void
    {
        $this->commands[] = $command;
    }

    public function signContractWithBoss()
    {
        /**s
         * @var ICommand $command
         */
        foreach ($this->commands as $command) {
            $command->execute();
        }
    }
}
$customer = new Customer();

$team = new Team();
$requirements = ["Cool web site", "Ability to book beer on site"];
$teamCommand = new YouAsProjectManagerCommand($team, $requirements);
$customer->addCommand($teamCommand);

$heroDeveloper = new HeroDeveloper();
$heroCommand = new HeroDeveloperCommand($heroDeveloper, "A");
$customer->addCommand($heroCommand);

$customer->signContractWithBoss();

