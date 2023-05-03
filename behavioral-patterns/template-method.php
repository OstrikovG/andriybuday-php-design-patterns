<?php

class MessagesSearcher
{
    protected DateTime $dateSent;
    protected string $personName;
    protected int $importanceLevel;

    public function __construct(DateTime $dateSent, string $personName, int $importanceLevel)
    {
        $this->dateSent = $dateSent;
        $this->personName = $personName;
        $this->importanceLevel = $importanceLevel;
    }

    // Базові операції (primitive operations)
    protected function createDateCriteria(): void
    {
        printf("Standard date criteria has been applied.");
    }

    protected function createSentPersonCriteria(): void
    {
        printf("Standard person criteria has been applied.");
    }

    protected function createImportanceCriteria(): void
    {
        printf("Standard importance criteria has been applied.");
    }

    // Метод, який називають шаблонним
    public function search(): string
    {
        $this->createDateCriteria();
        $this->createSentPersonCriteria();
        printf("Template method does some verification accordingly to search algo.\n");
        $this->createImportanceCriteria();
        printf("Template method verifies if message could be so important or useless from person provided in criteria.\n");
        return "Some list of messages...";
    }
}

class ImportantMessagesSearcher extends MessagesSearcher
{
    public function __construct(DateTime $dateSent, string $personName)
    {
        parent::__construct($dateSent, $personName, 3);// «3» означає, що повідомлення важливе
    }

    // Одна операція перевантажена (IMPORTANT в кінці)
    protected function createImportanceCriteria(): void
    {
        printf("Special importance criteria has been formed: IMPORTANT");
    }
}

class UselessMessagesSearcher extends MessagesSearcher
{
    public function __construct(DateTime $dateSent, string $personName)
    {
        parent::__construct($dateSent, $personName, 1);// «1» означає, що «в пень» воно треба
    }

    // Одна операція перевантажена (вивід відрізняється словом «USELESS» в кінці)
    protected function createImportanceCriteria(): void
    {
        printf("Special importance criteria has been formed: USELESS");
    }
}

$searcher = new UselessMessagesSearcher(new DateTime(), "Sally");
$searcher->search();

$searcher = new ImportantMessagesSearcher(new DateTime(), "Killer");
$searcher->search();