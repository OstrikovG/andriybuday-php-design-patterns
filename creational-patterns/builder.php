<?php

class Laptop
{
    public $monitorResolution;
    public $processor;
    public $memory;
    public $HDD;
    public $battery;

    public function __toString(): string
    {
        return "[Laptop: $this->monitorResolution, $this->processor, $this->memory, $this->HDD, $this->battery]";
    }
}

abstract  class LaptopBuilder
{
    protected Laptop $laptop;

    public function createNewLaptop(): void
    {
        $this->laptop = new Laptop();
    }

    // Метод, який повертає готовий ноутбук назовні
    public function getMyLaptop()
    {
        return $this->laptop;
    }

    // Кроки, необхідні щоб створити ноутбук
    public abstract function setMonitorResolution(): void;
    public abstract function setProcessor(): void;
    public abstract function setMemory(): void;
    public abstract function setHDD(): void;
    public abstract function setBattery(): void;
}

// Таким будівельником може бути працівник, що
// спеціалізується у складанні «геймерських» ноутів
class GamingLaptopBuilder extends LaptopBuilder
{

    public function setMonitorResolution(): void
    {
        $this->laptop->monitorResolution = "1900X1200";
    }

    public function setProcessor(): void
    {
        $this->laptop->processor = "Core 2 Duo, 3.2 GHz";
    }

    public function setMemory(): void
    {
        $this->laptop->memory = "6144 Mb";
    }

    public function setHDD(): void
    {
        $this->laptop->HDD = "500 Gb";
    }

    public function setBattery(): void
    {
        $this->laptop->battery = "6 lbs";
    }
}

// А ось інший «збирач» ноутів
class TripLaptopBuilder extends LaptopBuilder
{

    public function setMonitorResolution(): void
    {
        $this->laptop->monitorResolution = "1200X800";
    }

    public function setProcessor(): void
    {
        $this->laptop->processor = "Atom, 2.0 GHz";
    }

    public function setMemory(): void
    {
        $this->laptop->memory = "2048 Mb";
    }

    public function setHDD(): void
    {
        $this->laptop->HDD = "80 Gb";
    }

    public function setBattery(): void
    {
        $this->laptop->battery = "6 lbs";
    }
}

class BuyLaptop
{
    private LaptopBuilder $laptopBuilder;

    public function setLaptopBuilder(LaptopBuilder $laptopBuilder): void
    {
        $this->laptopBuilder = $laptopBuilder;
    }

    // Змушує будівельника повернути цілий ноутбук
    public function getLaptop(): Laptop
    {
        return $this->laptopBuilder->getMyLaptop();
    }

    // Змушує будівельника додавати деталі
    public function constructLaptop(): void
    {
        $this->laptopBuilder->createNewLaptop();
        $this->laptopBuilder->setMonitorResolution();
        $this->laptopBuilder->setProcessor();
        $this->laptopBuilder->setMemory();
        $this->laptopBuilder->setHDD();
        $this->laptopBuilder->setBattery();
    }
}

// Ваша система може мати багато конкретних будівельників
$tripBuilder = new TripLaptopBuilder();
$gamingBuilder = new GamingLaptopBuilder();
$shopForYou = new BuyLaptop();//Директор
// Покупець каже, що хоче грати ігри
$shopForYou->SetLaptopBuilder($gamingBuilder);
$shopForYou->constructLaptop();
// Ну то нехай бере що хоче!
$laptop = $shopForYou->getLaptop();
printf("%s \n", $laptop);

// Вивід: [Laptop: 1900X1200, Core 2 Duo, 3.2 GHz, 6144 Mb, 500 Gb, 6 lbs]