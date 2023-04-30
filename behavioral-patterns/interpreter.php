<?php

class CurrentPricesContext
{
    private array $prices = [];

    public function getPrice(string $goods): int
    {
        return $this->prices[$goods] ?? 0;
    }

    public function setPrice(string $goods, int $price): void
    {
        $this->prices[$goods] = $price;
    }
}

// Абстрактний вираз
abstract class Goods
{
    public abstract function interpret(CurrentPricesContext $context): int;
}

// Нетермінальний вираз (необхідна логіка для визначення значення)
class GoodsPackage extends Goods
{
    public array $goodsInside = [];

    public function __construct(array $goodsInside)
    {
        $this->goodsInside = $goodsInside;
    }
    public function interpret(CurrentPricesContext $context): int
    {
        $totalSum = 0;
        /**s
         * @var Goods $goods
         */
        foreach ($this->goodsInside as $goods) {
            $totalSum += $goods->interpret($context);
        }
        return $totalSum;
    }
}

class TV extends Goods
{
    public array $goodsInside = [];

    public function interpret(CurrentPricesContext $context): int
    {
        $price = $context->getPrice("TV");
        printf("TV: %s.\n", $price);
        return  $price;
    }
}

// Інші термінальні вирази (Laptop, Bed)
class Laptop extends Goods
{
    public array $goodsInside = [];

    public function interpret(CurrentPricesContext $context): int
    {
        $price = $context->getPrice("Laptop");
        printf("Laptop: %s.\n", $price);
        return  $price;
    }
}

class Bed extends Goods
{
    public array $goodsInside = [];

    public function interpret(CurrentPricesContext $context): int
    {
        $price = $context->getPrice("Bed");
        printf("Bed: %s.\n", $price);
        return  $price;
    }
}

class InterpreterDemo
{
    public static function run(): void
    {
        (new InterpreterDemo())->runInterpreterDemo();
    }

    public function runInterpreterDemo(): void
    {
        // Дістаємо синтаксичне дерево, що представляє речення
        $truckWithGoods = $this->prepareTruckWithGoods();
        // Отримуємо останній контекст цін
        $pricesContext = $this->getRecentPricesContext();
        // Інтерпретуємо
        $totalPriceForGoods = $truckWithGoods->interpret($pricesContext);
        printf("Total: %s.\n", $totalPriceForGoods);
    }

    private function getRecentPricesContext(): CurrentPricesContext
    {
        $pricesContext = new CurrentPricesContext();
        $pricesContext->setPrice("Bed", 400);
        $pricesContext->setPrice("TV", 100);
        $pricesContext->setPrice("Laptop", 500);
        return $pricesContext;
    }

    private function prepareTruckWithGoods(): GoodsPackage
    {
        $truck = new GoodsPackage([]);
        $bed = new Bed();
        $doubleTriplePackedBed = new GoodsPackage(
            [new GoodsPackage(
                [$bed])
            ]
        );

        $truck->goodsInside[] = $doubleTriplePackedBed;
        $truck->goodsInside[] = new TV();
        $truck->goodsInside[] = new TV();
        $truck->goodsInside[] = new GoodsPackage([new Laptop(), new Laptop(), new Laptop()]);

        return $truck;
    }
}

InterpreterDemo::run();