<?php

class MySelf
{
    private IWearingStrategy $wearingStrategy;

    public function __construct()
    {
        $this->wearingStrategy = new DefaultWearingStrategy();
    }

    public function changeStrategy(IWearingStrategy $wearingStrategy): void
    {
        $this->wearingStrategy = $wearingStrategy;
    }

    public function goOutside()
    {
        $clothes = $this->wearingStrategy->getClothes();
        $accessories = $this->wearingStrategy->getAccessories();
        printf("Today I wore %s and took %s", $clothes, $accessories);
    }
}

interface IWearingStrategy
{
    public function getClothes(): string;
    public function getAccessories(): string;
}

class DefaultWearingStrategy implements IWearingStrategy
{
    public function getClothes(): string
    {
        return "Shirt";
    }

    public function getAccessories(): string
    {
        return "nothing";
    }
}

class RainWearingStrategy implements IWearingStrategy
{
    public function getClothes(): string
    {
        return "Coat";
    }

    public function getAccessories(): string
    {
        return "umbrella";
    }
}

class SunshineWearingStrategy implements IWearingStrategy
{
    public function getClothes(): string
    {
        return "T-Shirt";
    }

    public function getAccessories(): string
    {
        return "sunglasses";
    }
}

$me = new MySelf();
$me->changeStrategy(new RainWearingStrategy());
$me->goOutside();