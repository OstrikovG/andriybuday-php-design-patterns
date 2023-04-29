<?php

interface IDocumentComponent
{
    public function gatherData(): string;
    public function addComponent(IDocumentComponent $documentComponent): void;
}

class CustomerDocumentComponent implements IDocumentComponent
{
    private int $customerIdToGatherData;

    public function __construct(int $customerIdToGatherData)
    {
        $this->customerIdToGatherData = $customerIdToGatherData;
    }

    public function gatherData(): string
    {
        switch ($this->customerIdToGatherData) {
            case 41:
                $customerData = "Andriy Buday";
                break;
            default:
                $customerData = "Someone else";
                break;
        }
        return sprintf("<Customer>%s</Customer>\n", $customerData);
    }

    public function addComponent(IDocumentComponent $documentComponent): void
    {
        printf("Cannot add to leaf...");
    }
}

class DocumentComponent implements IDocumentComponent
{
    private string $name;
    /**
     * @return IDocumentComponent[]
     */
    public ArrayObject $documentComponents;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->documentComponents = new ArrayObject();
    }

    public function gatherData(): string
    {
        $string = sprintf("<%s>\n", $this->name);
        /**
         * @var IDocumentComponent $documentComponent
         */
        foreach ($this->documentComponents as $documentComponent) {
            $string .= $documentComponent->gatherData();
        }
        $string .= sprintf("</%s>\n", $this->name);
        return $string;
    }

    public function addComponent(IDocumentComponent $documentComponent): void
    {
        $this->documentComponents->append($documentComponent);
    }
}

class HeaderDocumentComponent implements IDocumentComponent
{
    private string $name;

    public function __construct()
    {
        $this->name = "Header";
    }

    public function gatherData(): string
    {
        $string = sprintf("<%s>\n", $this->name);
        $string .= "<MessageTime>8:47:23</MessageTime>\n";
        $string .= sprintf("</%s>\n", $this->name);
        return $string;
    }

    public function addComponent(IDocumentComponent $documentComponent): void
    {
        printf("Cannot add to leaf...");
    }
}

class OrderDocumentComponent implements IDocumentComponent
{
    private int $orderIdToGatherData;

    public function __construct(int $orderIdToGatherData)
    {
        $this->orderIdToGatherData = $orderIdToGatherData;
    }

    public function gatherData(): string
    {
        switch ($this->orderIdToGatherData) {
            case 0:
                $orderData = "Kindle;Book1;Book2";
                break;
            case 1:
                $orderData = "Phone;Cable;Headset";
                break;
        }
        return sprintf("<Order>%s</Order>\n", $orderData);
    }
    public function addComponent(IDocumentComponent $documentComponent): void
    {
        printf("Cannot add to leaf...");
    }
}

$document = new DocumentComponent("ComposableDocument");
$headerDocumentSection = new HeaderDocumentComponent();
$body = new DocumentComponent("Body");
$document->addComponent($headerDocumentSection);
$document->addComponent($body);
$customerDocumentSection = new CustomerDocumentComponent(41);
$orders = new DocumentComponent("Orders");
$order0 = new OrderDocumentComponent(0);
$order1 = new OrderDocumentComponent(1);
$orders->addComponent($order0);
$orders->addComponent($order1);
$body->addComponent($customerDocumentSection);
$body->addComponent($orders);
$gatheredData = $document->gatherData();
echo $gatheredData;