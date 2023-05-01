<?php

class Soldier
{
    public string $name;
    private int $health;
    private const SOLDIER_HEALTH_POINTS = 100;
    protected int $maxHealthPoints = self::SOLDIER_HEALTH_POINTS;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function treat(): void
    {
        $this->health = $this->maxHealthPoints;
        printf("$this->name\n");
    }
}

class Hero extends Soldier
{
    private const HERO_HEALTH_POINTS = 500;
    protected int $maxHealthPoints = self::HERO_HEALTH_POINTS;
}

class Group
{
    /**
     * @var Soldier[] $soldiers
     */
    private array $soldiers;

    public function addNewSoldier(Soldier $soldier)
    {
        $this->soldiers[] = $soldier;
    }

    public function getSoldiers(): array
    {
        return $this->soldiers;
    }
}

class Army
{
    /**
     * @var Group[] $armyGroups
     */
    public array $armyGroups;
    public Hero $armyHero;

    public function  __construct(Hero $armyHero)
    {
        $this->armyHero = $armyHero;
    }

    public function addArmyGroup(Group $group): void
    {
        $this->armyGroups[] = $group;
    }
}

class SoldiersIterator
{
    private readonly Army $army;
    private bool $heroIsIterated;
    private int $currentGroup;
    private int $currentGroupSoldier;

    public function __construct(Army $army)
    {
        $this->army = $army;
        $this->heroIsIterated = false;
        $this->currentGroup = 0;
        $this->currentGroupSoldier = 0;
    }

    public function hasNext(): bool
    {
        if (!$this->heroIsIterated) return true;
        if ($this->currentGroup < count($this->army->armyGroups) - 1) {
            if ($this->currentGroupSoldier < count($this->army->armyGroups[$this->currentGroup]->getSoldiers())) {
                return true;
            }
        }
        return false;
    }

    public function next(): Soldier
    {
        if ($this->currentGroup < count($this->army->armyGroups)) {
            // В кожній групі ітеруємо по кожному солдату
            if ($this->currentGroupSoldier < count($this->army->armyGroups[$this->currentGroup]->getSoldiers())) {
                $nextSoldier = $this->army->armyGroups[$this->currentGroup]->getSoldiers()[$this->currentGroupSoldier];
                $this->currentGroupSoldier++;
            } else {
                $this->currentGroup++;
                $this->currentGroupSoldier = 0;
                return $this->next();
            }
        }
        // Герой останнім покидає поле бою
        elseif (!$this->heroIsIterated) {
            $this->heroIsIterated = true;
            return $this->army->armyHero;
        }
        else {
            // Викидуємо виняток
            throw new Exception("End of collection");
        }
        return $nextSoldier;
    }
}

$andriybuday = new Hero("Andriy Buday");
$earthArmy = new Army($andriybuday);
$groupA = new Group();
for ($i = 1; $i < 4; ++$i) $groupA->addNewSoldier(new Soldier("Alpha:" . $i));
$groupB = new Group();
for ($i = 1; $i < 3; ++$i) $groupB->addNewSoldier(new Soldier("Beta:" . $i));
$groupC = new Group();
for ($i = 1; $i < 2; ++$i) $groupC->addNewSoldier(new Soldier("Gamma:" . $i));

$earthArmy->addArmyGroup($groupB);
$earthArmy->addArmyGroup($groupA);
$earthArmy->addArmyGroup($groupC);

$iterator = new SoldiersIterator($earthArmy);
while ($iterator->hasNext()) {
    $currSoldier = $iterator->next();
    $currSoldier->treat();
}