<?php

interface IObserver
{
    public function update(ISubject $subject): void;
}

class RiskyPlayer implements  IObserver
{
    public string $boxerToPutMoneyOn;

    public function update(ISubject $subject): void
    {
        $boxFight = $subject;
        if ($boxFight->boxerAScore > $boxFight->boxerBScore) {
            $this->boxerToPutMoneyOn = "I put on boxer B, if he win I get more!";
        } else {
            $this->boxerToPutMoneyOn = "I put on boxer A, if he win I get more!";
        }
        printf("RISKYPLAYER:%s\n", $this->boxerToPutMoneyOn);
    }
}

class ConservativePlayer implements  IObserver
{
    public string $boxerToPutMoneyOn;

    public function update(ISubject $subject): void
    {
        $boxFight = $subject;
        if ($boxFight->boxerAScore < $boxFight->boxerBScore) {
            $this->boxerToPutMoneyOn = "I put on boxer B, better be safe!";
        } else {
            $this->boxerToPutMoneyOn = "I put on boxer A, better be safe!";
        }
        printf("CONSERVATIVEPLAYER:%s\n", $this->boxerToPutMoneyOn);
    }
}

interface ISubject
{
    public function attachObserver(IObserver $observer):void;
    public function detachObserver(IObserver $observer):void;
    public function notify():void;
}

class BoxFight implements ISubject
{
    public array $observers = [];
    public int $roundNumber = 0;

    public int $boxerAScore = 0;
    public int $boxerBScore = 0;

    public function attachObserver(IObserver $observer): void
    {
        $this->observers[] = $observer;
    }

    public function detachObserver(IObserver $observer): void
    {
        if(($key = array_search($observer, $this->observers)) !== false) {
            unset($this->observers[$key]);
        }
    }

    public function nextRound(): void
    {
        $this->roundNumber++;

        $this->boxerAScore += rand(0, 5);
        $this->boxerBScore += rand(0, 5);

        $this->notify();
    }

    public function notify(): void
    {
        /**
         * @var IObserver $observer
         */
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}

$boxFight = new BoxFight();

$riskyPlayer = new RiskyPlayer();
$conservativePlayer = new ConservativePlayer();

$boxFight->attachObserver($riskyPlayer);
$boxFight->attachObserver($conservativePlayer);

$boxFight->nextRound();
$boxFight->nextRound();
$boxFight->nextRound();
$boxFight->nextRound();
