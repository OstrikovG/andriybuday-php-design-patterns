<?php

class RobotBombDefuser
{
    private int $robotConfiguredWaveLength = 41;
    private bool $isConnected = false;

    public function connectWireless(int $communicationWaveLength)
    {
        if ($communicationWaveLength == $this->robotConfiguredWaveLength) {
            $this->isConnected = $this->isConnectedImmitatingConnectivityIssues();
        }
    }

    public function isConnected(): bool
    {
        $this->isConnected = $this->isConnectedImmitatingConnectivityIssues();
        return $this->isConnected;
    }

    private function isConnectedImmitatingConnectivityIssues(): bool
    {
        // Імітуємо погане з’єднання (працює в 4 із 10 спробах)
        return \random_int(0, 10) < 4;
    }

    public function walkStraightForward(int $steps): void
    {
        printf("Did %s steps forward...\n", $steps);
    }

    public function turnRight(): void
    {
        printf("Turned right...\n");
    }

    public function turnLeft(): void
    {
        printf("Turned left...\n");
    }

    public function defuseBomb(): void
    {
        printf("Cut red or green or blue wire...\n");
    }
}

class RobotBombDefuserProxy extends RobotBombDefuser
{
    private RobotBombDefuser $robotBombDefuser;
    private int $communicationWaveLength;
    private int $connectionAttempts = 3;

    public function __construct(int $communicationWaveLength)
    {
        $this->robotBombDefuser = new RobotBombDefuser();
        $this->communicationWaveLength = $communicationWaveLength;
    }

    public function walkStraightForward(int $steps): void
    {
        $this->ensureConnectedWithRobot();
        $this->robotBombDefuser->walkStraightForward($steps);
    }

    public function turnRight(): void
    {
        $this->ensureConnectedWithRobot();
        $this->robotBombDefuser->turnRight();
    }

    public function turnLeft(): void
    {
        $this->ensureConnectedWithRobot();
        $this->robotBombDefuser->turnLeft();
    }

    public function defuseBomb(): void
    {
        $this->ensureConnectedWithRobot();
        $this->robotBombDefuser->defuseBomb();
    }

    private function ensureConnectedWithRobot(): void
    {
        if ($this->robotBombDefuser == null) {
            $this->robotBombDefuser = new RobotBombDefuser();
            $this->robotBombDefuser->connectWireless($this->communicationWaveLength);
        }

        for ($i = 0; $i < $this->connectionAttempts; $i++) {
            if ($this->robotBombDefuser->isConnected() != true) {
                $this->robotBombDefuser->connectWireless($this->communicationWaveLength);
            } else {
                break;
            }
        }

        if ($this->robotBombDefuser->isConnected() != true) {
            throw new \Exception("No connection with remote bomb diffuser robot could be made after few attempts.");
        }
    }
}

class ProxyDemo
{
    public static function run()
    {
        $opNum = 0;
        try {
            $proxy = new RobotBombDefuserProxy(41);
            $proxy->walkStraightForward(100);
            $opNum++;
            $proxy->turnRight();
            $opNum++;
            $proxy->walkStraightForward(5);
            $opNum++;
            $proxy->defuseBomb();
            $opNum++;
        } catch (\Exception $e) {
            printf("Exception has been caught with message: (%s). Decided to have human operate robot there.\n", $e->getMessage());
            static::planB($opNum);
        }
    }

    private static function planB(int $nextOperationNum): void
    {
        $humanOperatingRobotDirectly = new RobotBombDefuser();

        if($nextOperationNum == 0)
        {
            $humanOperatingRobotDirectly->walkStraightForward(100);
            $nextOperationNum++;
        }
        if ($nextOperationNum == 1)
        {
            $humanOperatingRobotDirectly->turnRight();
            $nextOperationNum++;
        }
        if ($nextOperationNum == 2)
        {
            $humanOperatingRobotDirectly->walkStraightForward(5);
            $nextOperationNum++;
        }
        if ($nextOperationNum == 3)
        {
            $humanOperatingRobotDirectly->defuseBomb();
        }
    }
}

ProxyDemo::run();