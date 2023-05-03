<?php

interface IVisitor
{
    public function visit(IElement $visitor): void;
}

interface IElement
{
    public function accept(IVisitor $visitor);
}

class ElectricitySystemValidator implements IVisitor
{
    public function visit(IElement $visitor): void
    {
        if ($visitor instanceof OfficeBuilding) {
            $building = $visitor;
            $electricityState = ($building->electricitySystemId > 1000) ? "Good" : "Bad";

            printf("Main electric shield in building %s is in %s state.\n", $building->buildingName, $electricityState);
        } elseif ($visitor instanceof Floor) {
            $floor = $visitor;
            printf("Diagnosting electricity on floor %s.\n", $floor->floorNumber);
        } elseif ($visitor instanceof Room) {
            $room = $visitor;
            printf("Diagnosting electricity in room  %s.\n", $room->roomNumber);
        }
    }
}

class PlumbingSystemValidator implements IVisitor
{
    public function visit(IElement $visitor): void
    {
        if ($visitor instanceof OfficeBuilding) {
            $building = $visitor;
            if ($building->buildingAge < 25) {
                $plumbingState = "Good";
                $buildingState = "New";
            } else {
                $plumbingState = "Bad";
                $buildingState = "Old";
            }

            printf("Plumbing state of building [Design Patterns Center] probably is in %s condition,
since builing is %s. \n", $plumbingState, $buildingState);
        } elseif ($visitor instanceof Floor) {
            $floor = $visitor;
            printf("Diagnosting plumbing on floor %s.\n", $floor->floorNumber);
        }
    }
}

class OfficeBuilding implements IElement
{
    public readonly int $electricitySystemId;
    public readonly string $buildingName;
    public readonly int $buildingAge;
    private array $floors = [];

    public function __construct(string $buildingName, int $buildingAge, int $electricitySystemId)
    {
        $this->buildingName = $buildingName;
        $this->buildingAge = $buildingAge;
        $this->electricitySystemId = $electricitySystemId;
    }

    public function addFloor(Floor $floor): void
    {
        $this->floors[] = $floor;
    }

    public function accept(IVisitor $visitor)
    {
        $visitor->visit($this);
        foreach ($this->floors as $floor) {
            $floor->accept($visitor);
        }
    }
}

class Floor implements IElement
{
    private array $rooms = [];
    public int $floorNumber;

    public function __construct(int $floorNumber)
    {
        $this->floorNumber = $floorNumber;
    }

    public function addRoom(Room $room): void
    {
        $this->rooms[] = $room;

    }

    public function accept(IVisitor $visitor)
    {
        $visitor->visit($this);
        foreach ($this->rooms as $room) {
            $room->accept($visitor);
        }
    }
}

class Room implements IElement
{
    public readonly string $roomNumber;

    public function __construct(int $roomNumber)
    {
        $this->roomNumber = $roomNumber;
    }

    public function accept(IVisitor $visitor)
    {
        $visitor->visit($this);
    }
}

$floor1 = new Floor(1);
$floor1->addRoom(new Room(100));
$floor1->addRoom(new Room(101));
$floor1->addRoom(new Room(102));
$floor2 = new Floor(2);
$floor2->addRoom(new Room(200));
$floor2->addRoom(new Room(201));
$floor2->addRoom(new Room(202));
$myFirmOffice = new OfficeBuilding("[Design Patterns Center]", 25, 990);
$myFirmOffice->addFloor($floor1);
$myFirmOffice->addFloor($floor2);

$electrician = new ElectricitySystemValidator();
$myFirmOffice->accept($electrician);

$plumber = new PlumbingSystemValidator();
$myFirmOffice->accept($plumber);