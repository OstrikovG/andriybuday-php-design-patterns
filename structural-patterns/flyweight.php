<?php

abstract class Unit
{
    public string $name;
    public int $health;
    public GdImage $picture;
}

interface IParser
{
    public function parseHTML(): ArrayObject;
}

class Dragon extends Unit
{
    public function __construct()
    {
        $this->name = "Dragon";
        $this->health = 50;
        $this->picture = \imagecreatefrompng("../images/Dragon.png");
    }
}

class Goblin extends Unit
{
    public function __construct()
    {
        $this->name = "Goblin";
        $this->health = 8;
        $this->picture = \imagecreatefrompng("../images/Goblin.png");
    }
}

class Parser implements IParser
{
    public function parseHTML(): ArrayObject
    {
        $units = new ArrayObject();
        for ($i = 0; $i < 150; $i++) {
            $units->append(new Dragon());
            for ($i = 0; $i < 500; $i++) {
                $units->append(new Goblin());
            }
        }
        print("Dragons and Goblins are parsed from HTML page.\n");
        return $units;
    }
}

class UnitImagesFactory
{
    private static array $images = [];

    public static function createDragonImage(): GdImage
    {
        if (empty(self::$images[Dragon::class]))
        {
            self::$images[Dragon::class] = \imagecreatefrompng("../images/Dragon.png");
        }
        return self::$images[Dragon::class];
    }

    public static function createGoblinImage(): GdImage
    {
        if (empty(self::$images[Goblin::class]))
        {
            self::$images[Goblin::class] = \imagecreatefrompng("../images/Goblin.png");
        }
        return self::$images[Goblin::class];
    }
}

class FlyweightDragon extends Unit
{
    public function __construct()
    {
        $this->name = "Dragon";
        $this->health = 50;
        // От власне те, що змінилося від попередньої версії
        $this->picture = UnitImagesFactory::createDragonImage();
    }
}

class FlyweightGoblin extends Unit
{
    public function __construct()
    {
        $this->name = "Goblin";
        $this->health = 8;
        $this->picture = UnitImagesFactory::createGoblinImage();
    }
}

class FlyweightParser implements IParser
{
    public function parseHTML(): ArrayObject
    {
        $units = new ArrayObject();
        for ($i = 0; $i < 150; $i++) {
            $units->append(new FlyweightDragon());
            for ($i = 0; $i < 500; $i++) {
                $units->append(new FlyweightGoblin());
            }
        }
        print("Dragons and Goblins are parsed from HTML page.\n");
        return $units;
    }
}
function getUnits(IParser $parser) {
    $before = memory_get_usage();
    $units = $parser->parseHTML();
    $after = memory_get_usage();
    $allocatedSize = ($after - $before);
    printf("Allocated size after parsing with %s: %s.\n", $parser::class, $allocatedSize);

    return $units;
}

$parser = new Parser();
$units = getUnits($parser);

$flyweightParser = new FlyweightParser();
$flyweightUnits = getUnits($flyweightParser);
