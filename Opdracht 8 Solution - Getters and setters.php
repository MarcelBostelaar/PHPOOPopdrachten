<?php


class Order
{
    // Private properties
    private $orderId;
    private $items = [];
    private $totalAmount = 0;

    // Public property
    public $status = 'pending'; //Kan ook "delivered" of "cancelled" zijn

    // Constructor
    public function __construct($orderId)
    {
        $this->setOrderId($orderId);
    }

    // Getter for orderId
    public function getOrderId()
    {
        return $this->orderId;
    }

    // Setter for orderId
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    // Getter for totalAmount
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    // Add item to the order
    public function addItem($itemName, $price, $quantity)
    {
        $this->items[] = [
            'itemName' => $itemName,
            'price' => $price,
            'quantity' => $quantity,
        ];

        // Update the total amount
        $this->updateTotalAmount();
    }

    // Get all items
    public function getItems()
    {
        return $this->items;
    }

    // Private method to update the total amount
    private function updateTotalAmount()
    {
        $this->totalAmount = 0; // Reset total amount

        foreach ($this->items as $item) {
            $this->totalAmount += $item['price'] * $item['quantity'];
        }
    }

    // Change order status
    public function changeStatus($newStatus)
    {
        $this->status = $newStatus;
    }
}

function validate(){
    $order1 = validator::Create('Order', 1, function(){
        return new Order(1);
    });
    $order2 = validator::Create('Order', 1, function(){
        return new Order(2);
    });

    $order1
        ->methodPublic('getOrderId')
        ->methodPublic('setOrderId')
        ->methodPublic('getTotalAmount')
        ->methodPublic('addItem')
        ->methodPublic('getItems')
        ->methodPublic('changeStatus')
        ->propertyPrivate('orderId')
        ->propertyPrivate('items')
        ->propertyPrivate('totalAmount')
        ->propertyPublic('status')
        ->breakpoint()
        ->methodParameterCount('getOrderId', 0)
        ->methodParameterCount('setOrderId', 1)
        ->methodParameterCount('getTotalAmount', 0)
        ->methodParameterCount('addItem', 3)
        ->methodParameterCount('getItems', 0)
        ->methodParameterCount('changeStatus', 1)
        ->breakpoint()
        ->exitReportIfErrors("Order 1")
        ->execute('getOrderId')
        ->assertResultEquals(1)
        ->execute('setOrderId', 3)
        ->execute('getOrderId')
        ->assertResultEquals(3)
        ->execute('getTotalAmount')
        ->assertResultEquals(0)
        ->execute('addItem', 'item1', 10, 2)
        ->execute('getTotalAmount')
        ->assertResultEquals(20)
        ->execute('addItem', 'item2', 5, 3)
        ->execute('getTotalAmount')
        ->assertResultEquals(35)
        ->execute('getItems')
        ->assertResultEquals([['itemName' => 'item1', 'price' => 10, 'quantity' => 2], ['itemName' => 'item2', 'price' => 5, 'quantity' => 3]])
        ->assertPropertyEquals('status', 'pending')
        ->execute('changeStatus', 'delivered')
        ->assertPropertyEquals('status', 'delivered')
        ->report("Order 1");

    $order2
        ->execute('getOrderId')
        ->assertResultEquals(2)
        ->execute('setOrderId', 7)
        ->execute('getOrderId')
        ->assertResultEquals(7)
        ->execute('getTotalAmount')
        ->assertResultEquals(0)
        ->execute('addItem', 'item1', 30, 5)
        ->execute('getTotalAmount')
        ->assertResultEquals(150)
        ->execute('addItem', 'item2', 3, 2)
        ->execute('getTotalAmount')
        ->assertResultEquals(156)
        ->execute('getItems')
        ->assertResultEquals([['itemName' => 'item1', 'price' => 30, 'quantity' => 5], ['itemName' => 'item2', 'price' => 3, 'quantity' => 2]])
        ->assertPropertyEquals('status', 'pending')
        ->execute('changeStatus', 'cancelled')
        ->assertPropertyEquals('status', 'cancelled')
        ->report("Order 2");
}

#VALIDATORSTART
?><?php
class validator{
    private $objectToValidate;
    private $wrappedInReflector;
    private $continueTests = true;
    private $className;
    private $lastResult = null;
    private $lastInvocation = "";
    private $errorLog = [];
    private function __construct(){}

    public static function Create($className, $constructorParamCount, $classInstanceFactory, $isInterface = false){
        $x = new validator();
        $x->className = $className;
        if($isInterface){
            $x->validateInterfaceExists();
        }
        else{
            $x->validateClassExists();
        }
        if(!$x->continueTests){
            return $x;
        }
        $x->wrappedInReflector = new ReflectionClass($className);
        if($isInterface){
            return $x;
        }
        $hasConstructor = $x->wrappedInReflector->getConstructor() !== null;
        if(!$hasConstructor){
            $x->addError("Class $className does not have a constructor");
            return $x->breakpoint();
        }
        $x->checkConstructor($constructorParamCount)->breakpoint();
        if(!$x->continueTests){
            return $x;
        }
        $x->objectToValidate = $classInstanceFactory();
        $x->objectFactory = $classInstanceFactory;
        return $x;
    }

    public function exitReportIfErrors($customTestName = null){
        if($this->hasErrors()){
            $this->report($customTestName);
            exit();
        }
        return $this;
    }

    public function hasErrors(){
        return count($this->errorLog) > 0;
    }

    private function addError($message){
        array_push($this->errorLog, $message);
    }

    public function report($customTestName = null){
        if($customTestName == null){
            $testname = "class " . $this->className;
        }
        else{
            $testname = "test " . $customTestName;
        }
        if(count($this->errorLog) > 0){
            echo "Validation failed for $testname<br>";
            foreach($this->errorLog as $error){
                echo " - $error<br>";
            }
        }
        else{
            echo "Validation passed for $testname<br>";
        }
    }

    public function breakpoint(){
        if(count($this->errorLog) > 0){
            $this->continueTests = false;
        }
        return $this;
    }

    private function validateClassExists(){
        if(!$this->continueTests){
            return $this;
        }
        if(!class_exists($this->className)){
            $this->addError("Class $this->className does not exist");
        }
        return $this->breakpoint();
    }

    private function validateInterfaceExists(){
        if(!$this->continueTests){
            return $this;
        }
        if(!interface_exists($this->className)){
            $this->addError("Class $this->className does not exist");
        }
        return $this->breakpoint();
    }

    public function method($methodName)
    {
        if(!$this->continueTests){
            return $this;
        }
        if (!$this->wrappedInReflector->hasMethod($methodName)) {
            $this->addError("Method $methodName does not exist in class $this->className");
        }
        return $this;
    }

    public function methodPublic($methodName)
    {
        if(!$this->continueTests){
            return $this;
        }
        if (!$this->wrappedInReflector->hasMethod($methodName)) {
            return $this->method($methodName);
        }
        if (!$this->wrappedInReflector->getMethod($methodName)->isPublic()) {
            $this->addError("Method $methodName is not public in class $this->className");
        }
        return $this;
    }

    public function methodPrivate($methodName)
    {
        if(!$this->continueTests){
            return $this;
        }
        if (!$this->wrappedInReflector->hasMethod($methodName)) {
            return $this->method($methodName);
        }
        if (!$this->wrappedInReflector->getMethod($methodName)->isPrivate()) {
            $this->addError("Method $methodName is not private in class $this->className");
        }
        return $this;
    }

    private function checkConstructor($expectedParameterCount)
    {
        return $this->methodParameterCount('__construct', $expectedParameterCount);
    }

    public function methodParameterCount($methodName, $expectedParameterCount)
    {
        if(!$this->continueTests){
            return $this;
        }
        $this->method($methodName);
        $method = $this->wrappedInReflector->getMethod($methodName);
        $actualParameterCount = $method->getNumberOfParameters();
        if ($actualParameterCount !== $expectedParameterCount) {
            $this->addError("Method $methodName in class $this->className does not have $expectedParameterCount parameters, but has $actualParameterCount");
        }
        return $this;
    }

    public function property($propertyName)
    {
        if(!$this->continueTests){
            return $this;
        }
        if (!$this->wrappedInReflector->hasProperty($propertyName)) {
            $this->addError("Property $propertyName does not exist in class $this->className");
        }
        return $this;
    }

    public function propertyPublic($propertyName)
    {
        if(!$this->continueTests){
            return $this;
        }
        if (!$this->wrappedInReflector->hasProperty($propertyName)) {
            return $this->property($propertyName);
        }
        if (!$this->wrappedInReflector->getProperty($propertyName)->isPublic()) {
            $this->addError("Property $propertyName is not public in class $this->className");
        }
        return $this;
    }

    public function propertyPrivate($propertyName)
    {
        if(!$this->continueTests){
            return $this;
        }
        if (!$this->wrappedInReflector->hasProperty($propertyName)) {
            return $this->property($propertyName);
        }
        if (!$this->wrappedInReflector->getProperty($propertyName)->isPrivate()) {
            $this->addError("Property $propertyName is not private in class $this->className");
        }
        return $this;
    }

    public function execute($methodName, ...$params){
        if(!$this->continueTests){
            return $this;
        }
        try{
            $this->lastInvocation = "$methodName with parameters " . implode(", ", $params);
            $method = $this->wrappedInReflector->getMethod($methodName);
            $method->setAccessible(true);
            $this->lastResult = $method->invoke($this->objectToValidate, ...$params);
        }
        catch(Exception $e){
            $this->addError("Error when executing '$this->lastInvocation': " . $e->getMessage());
        }
        return $this;
    }

    public function construct(...$params){
        return $this->execute('__construct', ...$params);
    }

    public function assertResultEquals($expected){
        if(!$this->continueTests){
            return $this;
        }
        if($this->lastResult !== $expected){
            $this->addError("When calling '$this->lastInvocation', Expected result '$expected', got '$this->lastResult'");
        }
        return $this;
    }

    public function assertPropertyEquals($propertyName, $expected){
        if(!$this->continueTests){
            return $this;
        }
        $property = $this->wrappedInReflector->getProperty($propertyName);
        $property->setAccessible(true);
        $actual = $property->getValue($this->objectToValidate);
        if($actual !== $expected){
            $this->addError("Expected property $propertyName to be '$expected', got '$actual'");
        }
        return $this;
    }
}
?><?php
#VALIDATOREND

validate();