<?php

// Система яку будемо адаптовувати
class OldElectricitySystem
{
    public function MatchThinSocket(): string
    {
        return "220V";
    }
}

// Широковикористовуваний інтерфейс нової системи (специфікація до квартири)
interface INewElectricitySystem
{
    public function matchWideSocket(): string;
}

// Ну і власне сама розетка у новій квартирі
class NewElectricitySystem implements INewElectricitySystem
{
    public function matchWideSocket(): string
    {
        return "220V";
    }
}
// Адаптер назовні виглядає як нові євроразетки, шляхом наслідування прийнятного у
// системі інтерфейсу
class Adapter implements INewElectricitySystem
{
    // Але всередині він таки знає, що коїлося в СРСР
    private readonly OldElectricitySystem $adaptee;

    public function __construct(OldElectricitySystem $adaptee)
    {
        $this->adaptee = $adaptee;
    }

    public function matchWideSocket(): string
    {
        // Якщо б була різниця в напрузі (не 220V)
        // то тут ми б помістили трансформатор
        return $this->adaptee->MatchThinSocket();
    }
}

class ElectricityConsumer
{
    // Зарядний пристрій розуміє тільки нову систему
    public static function chargeNoteBook(INewElectricitySystem $electricitySystem): void
    {
        printf($electricitySystem->matchWideSocket() . "\n");
    }
}

class AdapterDemo
{
    public static function run()
    {
        // 1)
        // Ми можемо користуватися новою системою без проблем
        $newElectricitySystem = new NewElectricitySystem();
        ElectricityConsumer::chargeNoteBook($newElectricitySystem);

        // 2)
        // Ми повинні адаптуватися до старої системи, використовуючи адаптер
        $oldElectricitySystem = new OldElectricitySystem();
        $adapter = new Adapter($oldElectricitySystem);
        ElectricityConsumer::chargeNoteBook($adapter);
    }
}

AdapterDemo::run();