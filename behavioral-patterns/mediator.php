<?php

class BodyPart
{
    private readonly Brain $brain;

    public function __construct(Brain $brain)
    {
        $this->brain = $brain;
    }

    public function changed(): void
    {
        $this->brain->somethingHappenedToBodyPart($this);
    }
}

class Ear extends BodyPart
{
    public function hearSomething(): string
    {
        $sounds = (string) \readline("Enter what you hear:\n");
        if ($sounds === "") die;
        return $sounds;
    }
}

class Eye extends BodyPart
{

}

class Face extends BodyPart
{
    public function smile(): void
    {
        printf("FACE: Smiling...");
    }
}

class Hand extends BodyPart
{
    public function doesItHurt(): bool
    {
        $answers = ['yes' => true, 'y' => true, 'no' => false, 'n' => false];
        $doesItHurt = \readline("What you feel is hurting? (Yes/No)\n");
        if (!array_key_exists($doesItHurt, $answers)) die;
        return $answers[strtolower($doesItHurt)];
    }

    public function hitPersonNearYou()
    {
        printf("HAND: hit person near you...");
    }

    public function itIsNice(): bool
    {
        $answers = ['yes' => true, 'y' => true, 'no' => false, 'n' => false];
        $itIsNice = \readline("What you feel is soft? (Yes/No)\n");
        if (!array_key_exists($itIsNice, $answers)) die;
        return $answers[strtolower($itIsNice)];
    }


    public function embrace()
    {
        printf("HAND: Embracing what is in front of you...\n");
    }
}

class Leg extends BodyPart
{

    public function stepForward()
    {
        printf("LEG: Stepping forward...\n");
    }

    public function kick()
    {
        printf("LEG: Just kicked offender in front of you...\n");
    }

    public function stepBack()
    {
        printf("LEG: Stepping back...\n");
    }
}

// Медіатор
class Brain
{
    public Ear $ear;
    public Eye $eye;
    public Face $face;
    public Hand $hand;
    public Leg $leg;

    public function __construct()
    {
        $this->createBodyParts();
    }

    private function createBodyParts()
    {
        $this->ear = new Ear($this);
        $this->eye = new Eye($this);
        $this->face = new Face($this);
        $this->hand = new Hand($this);
        $this->leg = new Leg($this);
    }

    public function somethingHappenedToBodyPart(BodyPart $bodyPart)
    {
        if ($bodyPart instanceof Ear) {
            $heardSounds = $bodyPart->hearSomething();

            if (\str_contains($heardSounds, "stupid")) {
                // Атакуємо образника
                $this->leg->stepForward();
                $this->hand->hitPersonNearYou();
                $this->leg->kick();
            } elseif (\str_contains($heardSounds, "cool")) {
                $this->face->smile();
            }
        } elseif ($bodyPart instanceof Eye) {
            // Мозок може проаналізувати, що ви бачите і
            // прореагувати відповідно, використовуючи різні частини тіла
        } elseif ($bodyPart instanceof Hand) {
            $hand = $bodyPart;

            $itIsNice = $hand->itIsNice();
            if ($itIsNice) {
                $this->leg->stepForward();
                $this->hand->embrace();
            }

            $hurtingFeeling = $hand->doesItHurt();
            if ($hurtingFeeling) {
                $this->leg->stepBack();
            }

        } elseif ($bodyPart instanceof Leg) {
            // Якщо на ногу впаде цегла, змінюємо вираз обличчя
        }
    }
}

$bodyPartClassName = \readline("Enter body part (‘Ear’, ‘Eye’, ‘Hand’ or empty to exit): ");
$bodyPartClassNameMapper = ['Ear' => 'ear', 'Eye' => 'eye', 'Hand' => 'hand'];
if ($bodyPartClassName === false) die;
$brain = new Brain();
/**
 * @var BodyPart $bodyPart
 */
$bodyPart = $brain->{$bodyPartClassNameMapper[$bodyPartClassName]};
$bodyPart->changed();