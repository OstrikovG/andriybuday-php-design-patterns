<?php

// 1. Система орендування черевиків
class SkiRent
{
    public function rentBoots(int $feetSize, int $skierLevel): int
    {
        return 20;
    }
    public function rentSki(int $weight, int $skierLevel): int
    {
        return 40;
    }
    public function rentPole(int $height): int
    {
        return 5;
    }
}

// 2. Система придбання квитків
class SkiResortTicketSystem
{
    public function buyOneDayTicket(): int
    {
        return 115;
    }
    public function buyHalfDayTicket(): int
    {
        return 60;
    }
}

// 3. Система бронювання місць в готелі
class HotelBookingSystem
{
    public function bookRoom(int $roomQuality): int
    {
        switch ($roomQuality)
        {
            case 3:
                return 250;
            case 4:
                return 500;
            case 5:
                return 900;
            default:
                throw new InvalidArgumentException("roomQuality should be in range [3;5]");
        }
    }
}

// Фасад, що надає єдиний доступ до всіх систем згаданих вище
class SkiResortFacade
{
    private SkiRent $skiRent;
    private SkiResortTicketSystem $skiResortTicketSystem;
    private HotelBookingSystem $hotelBookingSystem;

    public function __construct()
    {
        $this->skiRent = new SkiRent();
        $this->skiResortTicketSystem = new SkiResortTicketSystem();
        $this->hotelBookingSystem = new HotelBookingSystem();
    }

    // Беручи до уваги вхідні параметри бронює номер, підбирає лижі і т.д
    // Повертає загальну ціну за все
    public function haveGoodRest(int $height, int $weight, int $feetSize, int $skierLevel, int $roomQuality)
    {
        $skiPrice = $this->skiRent->rentSki($weight, $skierLevel);
        $skiBootsPrice = $this->skiRent->rentBoots($feetSize, $skierLevel);
        $polePrice = $this->skiRent->rentPole($height);
        $oneDayTicketPrice = $this->skiResortTicketSystem->buyOneDayTicket();
        $hotelPrice = $this->hotelBookingSystem->bookRoom($roomQuality);

        return $skiPrice + $skiBootsPrice + $polePrice + $oneDayTicketPrice + $hotelPrice;
    }

    // Інші методи можуть поєднувати виклики до інших систем
    public function haveRestWithOwnSkis()
    {
        $oneDayTicketPrice = $this->skiResortTicketSystem->buyOneDayTicket();
        return $oneDayTicketPrice;
    }
    // Може бути що наш фасад-термінал просто огортає методи із усіх систем
}

$skiResortFacade = new SkiResortFacade();
$overAllPrice = $skiResortFacade->haveGoodRest(180, 90, 45, 2, 3);
printf("Please, pay your invoice. Overall price: %s$.\n", $overAllPrice);