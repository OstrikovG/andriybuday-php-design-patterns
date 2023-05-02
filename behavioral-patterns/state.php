<?php

class Product
{
    private string $name;
    private int $price;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}

abstract class OrderState
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function addProduct(Product $product): void
    {
        $this->operationIsNotAllowed("AddProduct");
    }

    public function register(): void
    {
        $this->operationIsNotAllowed("Register");
    }

    public function grant(): void
    {
        $this->operationIsNotAllowed("Grant");
    }

    public function ship(): void
    {
        $this->operationIsNotAllowed("Ship");
    }

    public function invoice(): void
    {
        $this->operationIsNotAllowed("Invoice");
    }

    public function cancel(): void
    {
        $this->operationIsNotAllowed("Cancel");
    }

    // Наступні методи (Register, Grant, Ship, Invoice, Cancel) виглядають так же
    private function operationIsNotAllowed(string $operationName): void
    {
        printf("\033[01;31mOperation %s is not allowed for Order's state %s \033[0m\n", $operationName, static::class);
    }
}

class Order
{
    private OrderState $state;
    private array $products = [];

    public function __construct()
    {
        $this->state = new NewOrder($this);
    }

    public function setOrderState(OrderState $state): void
    {
        $this->state  = $state;
    }

    public function writeCurrentStateName(): void
    {
        printf("Current Order's state: %s\n", $this->state::class);
    }

    public function addProduct(Product $product)
    {
        $this->state->addProduct($product);
    }

    public function register()
    {
        $this->state->register();
    }

    public function grant(): void
    {
        $this->state->grant();
    }

    public function shipping()
    {
        $this->state->ship();
    }

    public function invoice()
    {
        $this->state->invoice();
    }

    public function cancel()
    {
        $this->state->cancel();
    }

    public function doAddProduct(Product $product)
    {
        $this->products[] = $product;
        printf("Adding product...\n");
    }

    public function doRegister()
    {
        printf("Registration...\n");
    }

    public function doGrant(): void
    {
        printf("Granting...\n");
    }

    public function doShipping()
    {
        // Тут водій вантажівки нагружає ваше замовлення і «рулить»
        printf("Shipping...\n");
    }

    public function doInvoice()
    {
        printf("Invoicing...\n");
    }

    public function doCancel()
    {
        printf("Cancelling...\n");
    }
}

class NewOrder extends OrderState
{
    public function addProduct(Product $product): void
    {
        $this->order->doAddProduct($product);
    }

    public function register(): void
    {
        $this->order->doRegister();
        $this->order->setOrderState(new Registered($this->order));
    }

    public function cancel(): void
    {
        $this->order->doCancel();
        $this->order->setOrderState(new Cancelled($this->order));
    }
}

class Registered extends OrderState
{
    public function addProduct(Product $product): void
    {
        $this->order->doAddProduct($product);
        $this->order->setOrderState(new NewOrder($this->order));
    }

    public function grant(): void
    {
        $this->order->doRegister();
        $this->order->setOrderState(new Granted($this->order));
    }

    public function cancel(): void
    {
        $this->order->doCancel();
        $this->order->setOrderState(new Cancelled($this->order));
    }
}

class Granted extends OrderState
{
    public function addProduct(Product $product): void
    {
        $this->order->doAddProduct($product);
    }

    public function ship(): void
    {
        $this->order->doShipping();
        $this->order->setOrderState(new Shipped($this->order));
    }

    public function cancel(): void
    {
        $this->order->doCancel();
        $this->order->setOrderState(new Cancelled($this->order));
    }
}

class Shipped extends OrderState
{
    public function invoice(): void
    {
        $this->order->doInvoice();
        $this->order->setOrderState(new Invoiced($this->order));
    }
}

class Cancelled extends OrderState
{
}

class Invoiced extends OrderState
{
}

$beer = new Product();
$beer->setName("MyBestBeer");
$beer->setPrice(78000);

$order = new Order();
$order->writeCurrentStateName();

$order->addProduct($beer);
$order->writeCurrentStateName();

$order->register();
$order->writeCurrentStateName();

$order->grant();
$order->writeCurrentStateName();

$order->shipping();
$order->writeCurrentStateName();

// Пробуємо дозамовити пива до вже відправленого замовлення
// і дивимося що буде (див. довгий червоний рядок у виводі)
$order->addProduct($beer);
$order->writeCurrentStateName();

$order->invoice();
$order->writeCurrentStateName();