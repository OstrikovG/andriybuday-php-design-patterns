<?php

class Food
{
    public readonly string $name;
    public readonly array $ingredients;

    public function __construct(string $name, array $ingredients)
    {
        $this->name = $name;
        $this->ingredients = $ingredients;
    }
}

abstract class  WierdCafeVisitor
{
    public ?WierdCafeVisitor $cafeVisitor;

    public function __construct(?WierdCafeVisitor $cafeVisitor)
    {
        $this->cafeVisitor = $cafeVisitor;
    }

    public function handleFood(Food $food): void
    {
        // Якщо не в змозі подужати їжу, передаємо її ближчому другові
        if ($this->cafeVisitor != null) {
            $this->cafeVisitor->handleFood($food);
        }
    }
}

class BestFriend extends WierdCafeVisitor
{
    public bool $hasCoffeeContainingFood = false;

    public function handleFood(Food $food): void
    {
        if (in_array("Meat", $food->ingredients)) {
            printf("BestFriend: I just ate %s. It was tasty.\n", $food->name);
        }
        if (!$this->hasCoffeeContainingFood && in_array("Coffee", $food->ingredients)) {
            $this->hasCoffeeContainingFood = true;
            printf("BestFriend: I have to take something with coffee. %s looks fine.\n", $food->name);
            return;
        }
        parent::handleFood($food);
    }
}

class Me extends WierdCafeVisitor
{
    public bool $hasSoup = false;

    public function handleFood(Food $food): void
    {
        if (!$this->hasSoup && \str_contains($food->name, "Soup")) {
            $this->hasSoup = true;
            printf("Me: I like Soup. It went well.\n");
            return;
        }
        parent::handleFood($food);
    }
}

class GirlFriend extends WierdCafeVisitor
{
    public bool $hasCappuccino = false;

    public function handleFood(Food $food): void
    {
        if (!$this->hasCappuccino && $food->name == "Cappuccino") {
            $this->hasCappuccino = true;
            printf("GirlFriend: My lovely cappuccino!!!\n");
            return;
        }
        // Базовий виклик parent::handleFood($food); для останнього обробітника-дівчини
        // не має сенсу, тому можна викинути ексепшин або нічого не робити
    }
}

$cappuccino1 = new Food("Cappuccino", ["Coffee", "Milk", "Sugar"]);

$cappuccino2 = new Food("Cappuccino", ["Coffee", "Milk"]);
$soup1 = new Food("Soup with meat", ["Meat", "Water", "Potato"]);

$soup2 = new Food("Soup with potato", ["Water", "Potato"]);
$meat = new Food("Meat", ["Meat"]);

$girlFriend = new GirlFriend(null);
$me = new Me($girlFriend);
$bestFriend = new BestFriend($me);

$bestFriend->handleFood($cappuccino1);
$bestFriend->handleFood($cappuccino2);
$bestFriend->handleFood($soup1);
$bestFriend->handleFood($soup2);
$bestFriend->handleFood($meat);