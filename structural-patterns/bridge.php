<?php

interface IWallCreator
{
    public function buildWallWithDoor(): void;
    public function buildWall(): void;
    public function buildWallWithWindow(): void;
}

class BrickWallCreator implements IWallCreator
{

    public function buildWallWithDoor(): void
    {
        printf("Brick wall with door.\n");
    }

    public function buildWall(): void
    {
        printf("Brick wall.\n");
    }

    public function buildWallWithWindow(): void
    {
        printf("Brick wall with window.\n");
    }
}

class ConcreteSlabWallCreator implements IWallCreator
{

    public function buildWallWithDoor(): void
    {
        printf("Concrete slab wall with door.\n");
    }

    public function buildWall(): void
    {
        printf("Concrete slab wall.\n");
    }

    public function buildWallWithWindow(): void
    {
        printf("Concrete slab wall with window.\n");
    }
}

interface IBuildingCompany
{
    public function BuildFoundation();
    public function BuildRoom();
    public function BuildRoof();
    public function getWallCreator(): IWallCreator;
    public function setWallCreator(IWallCreator $wallCreator): void;
}

class BuildingCompany implements  IBuildingCompany
{
    private IWallCreator $wallCreator;

    public function buildFoundation(): void
    {
        printf("Foundation is built.\n\n");
    }

    public function buildRoom(): void
    {
        $this->getWallCreator()->buildWallWithDoor();
        $this->getWallCreator()->buildWall();
        $this->getWallCreator()->buildWallWithWindow();
        $this->getWallCreator()->buildWall();
        printf("Room finished.\n\n");
    }

    public function buildRoof()
    {
        printf("Roof is done.\n\n");
    }

    public function getWallCreator(): IWallCreator
    {
        return $this->wallCreator;
    }

    public function setWallCreator(IWallCreator $wallCreator): void
    {
        $this->wallCreator = $wallCreator;
    }
}

// Ми маємо дві бригади – одна працює із цеглою, інша із бетоном
$brickWallCreator = new BrickWallCreator();
$conctreteSlabWallCreator = new ConcreteSlabWallCreator();
$buildingCompany = new BuildingCompany();
$buildingCompany->buildFoundation();
$buildingCompany->setWallCreator($conctreteSlabWallCreator);
$buildingCompany->buildRoom();
// Компанія може легко переключитися на іншу команду, яка буде будувати
// стіни із інших матеріалів
$buildingCompany->setWallCreator($brickWallCreator);
$buildingCompany->buildRoom();
$buildingCompany->buildRoom();
$buildingCompany->buildRoof();