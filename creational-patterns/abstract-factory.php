<?php

abstract class AnimalToy
{
    public readonly string $name;

    protected function __construct(string $name)
    {
        $this->name = $name;
    }
}

// Базовий клас для усіх котиків, базовий клас AnimalToy містить Name
abstract class Cat extends AnimalToy
{
}

// Базовий клас для усіх ведмедиків
abstract class Bear extends AnimalToy
{
}

// Конкретні реалізації
class WoodenCat extends Cat {
    public function __construct()
    {
        parent::__construct("Wooden Cat");
    }
}

class TeddyCat extends Cat {
    public function __construct()
    {
        parent::__construct("Teddy Cat");
    }
}

class WoodenBear extends Bear {
    public function __construct()
    {
        parent::__construct("Wooden Bear");
    }
}

class TeddyBear extends Bear {
    public function __construct()
    {
        parent::__construct("Teddy Bear");
    }
}

// абстрактна фабрика (abstract factory)
interface IToyFactory
{
public function getBear(): Bear;
public function getCat(): Cat;
}

// конкретна фабрика (concrete factory)
class TeddyToysFactory implements IToyFactory
{

    public function getBear(): Bear
    {
        return new TeddyBear();
    }

    public function getCat(): Cat
    {
        return new TeddyCat();
    }
}

// і ще одна конкретна фабрика
class WoodenToysFactory implements IToyFactory
{

    public function getBear(): Bear
    {
        return new WoodenBear();
    }

    public function getCat(): Cat
    {
        return new WoodenCat();
    }
}

// Спочатку створимо «дерев'яну» фабрику
$toyFactory = new WoodenToysFactory();
$bear = $toyFactory->getBear();
$cat = $toyFactory->getCat();
printf("I've got %s and %s \n", $bear->name, $cat->name);
// Вивід на консоль буде: [I've got Wooden Bear and Wooden Cat]

// А тепер створимо «плюшеву» фабрику, наступна лінійка є єдиною різницею в коді
$toyFactory = new TeddyToysFactory();
// Як бачимо код нижче не відрізняється від наведеного вище
$bear = $toyFactory->getBear();
$cat = $toyFactory->getCat();
printf("I've got %s and %s \n", $bear->name, $cat->name);
// А вивід на консоль буде інший: [I've got Teddy Bear and Teddy Cat]