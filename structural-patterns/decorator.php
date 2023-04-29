<?php

abstract class Car
{
    protected string $brandName;

    public function go()
    {
        print("I'm " . $this->brandName . " and I'm on my way...\n");
    }
}

// Конкретна реалізація класу Car
class Mercedes extends Car
{
    public function __construct()
    {
        $this->brandName = "Mercedes";
    }
}

class DecoratorCar extends Car
{
    protected Car $decoratedCar;

    public function __construct(Car $decoratedCar)
    {
        $this->decoratedCar = $decoratedCar;
    }

    public function go()
    {
        $this->decoratedCar->go();
    }
}

class AmbulanceCar extends DecoratorCar
{
    public function go()
    {
        parent::go();
        print("... beep-beep-beeeeeep ...\n");
    }
}

$doctorsDream = new AmbulanceCar(new Mercedes());
$doctorsDream->go();