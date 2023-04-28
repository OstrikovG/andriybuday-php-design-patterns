<?php

class CalendarPrototype
{
}

class Priority
{
    public const LOW = 0;
    public const MEDIUM = 1;
    public const HIGH = 2;

    private int $priority;

    private function __construct(int $priority)
    {
        $this->priority = $priority;
    }

    public function isLow(): bool
    {
        return $this->priority === self::LOW;
    }

    public function isMedium(): bool
    {
        return $this->priority === self::MEDIUM;
    }

    public function isHigh(): bool
    {
        return $this->priority === self::HIGH;
    }

    public static function createLow(): self
    {
        return new self(self::LOW);
    }

    public static function createMedium(): self
    {
        return new self(self::MEDIUM);
    }

    public static function createHigh(): self
    {
        return new self(self::HIGH);
    }

    public function getPriorityValue(): int
    {
        return $this->priority;
    }

    public function setPriorityValue(int $priority): void
    {
        $this->priority = $priority;
    }
}

class Attendee
{
    private string $firstName;
    private string $lastName;
    private ?string $emailAddress;

    public function __construct(string $firstName, string $lastName, string $emailAddress)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->emailAddress = $emailAddress;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }
}

class CalendarEvent extends CalendarPrototype
{
    private ArrayObject $attendees;
    private Priority $priority;
    private DateTime $startDateAndTime;

    public function __clone()
    {
        $copy = $this;

        // Це дозволить нам мати інший список із посиланнями на тих же відвідувачів
        $copiedAttendees = clone $this->attendees;
        $copy->setAttendees($copiedAttendees);
        // Також скопіюємо приорітет
        $copy->setPriority(clone $this->priority);
        // День і час не варто копіювати – їх заповнять
        // Повертаємо копію події
        return $copy;
    }

    public function getAttendees(): ArrayObject
    {
        return $this->attendees;
    }

    public function setAttendees(ArrayObject $attendees): void
    {
        $this->attendees = $attendees;
    }

    /**
     * @return Priority
     */
    public function getPriority(): Priority
    {
        return $this->priority;
    }

    /**
     * @param Priority $priority
     */
    public function setPriority(Priority $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return DateTime
     */
    public function getStartDateAndTime(): DateTime
    {
        return $this->startDateAndTime;
    }

    /**
     * @param DateTime $startDateAndTime
     */
    public function setStartDateAndTime(DateTime $startDateAndTime): void
    {
        $this->startDateAndTime = $startDateAndTime;
    }
}

class PrototypeDemo
{
    public static function getExistingEvent(): CalendarEvent
    {
        $beerParty = new CalendarEvent();
        $andriy = new Attendee("Andriy", "Buday", "andriybuday@yahoo.com");
        $friends = (new ArrayObject());
        $friends[0] = $andriy;
        $beerParty->setAttendees($friends);
        $beerParty->setStartDateAndTime(new DateTime('2010-07-23 19:00:00'));
        $beerParty->setPriority(Priority::createHigh());
        return $beerParty;
    }

    public static function run(): void
    {
        $beerParty = self::getExistingEvent();
        $nextFridayEvent = clone $beerParty;
        $nextFridayEvent->getAttendees()[0]->setEmailAddress("andriybuday@liamg.com");
        $nextFridayEvent->setPriority(Priority::createLow());
        if ($beerParty->getAttendees() !== $nextFridayEvent->getAttendees()) {
            echo "GOOD: Each event has own list of attendees.\n";
        }
        if ($beerParty->getAttendees()[0]->getEmailAddress() === $nextFridayEvent->getAttendees()[0]->getEmailAddress()) {
            // В цьому випадку добре мати поверхневу копію кожного з учасників,
            // таким чином моя адреса, ім'я і персональні дані залишаються тими ж
            echo "GOOD: Update to my e-mail address will be reflected in all events.\n";
        }
        if ($beerParty->getPriority()->isHigh() !== $nextFridayEvent->getPriority()->isHigh()) {
            echo "GOOD: Each event should have own priority object, fully-copied.\n";
        }
    }
}

PrototypeDemo::run();