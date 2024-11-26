<?php
class Rectangle
{
    public $width;
    private $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function calculateArea()
    {
        return $this->width * $this->height;
    }

    private function calculatePerimeter()
    {
        return 2 * ($this->width + $this->height);
    }

    public function describeRectangle()
    {
        $area = $this->calculateArea();
        $perimeter = $this->calculatePerimeter();
        return "The rectangle has a width of {$this->width}, a height of {$this->height}, an area of {$area}, and a perimeter of {$perimeter}.";
    }
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

    public static function Create($className, $constructorParamCount, $classInstanceFactory){
        $x = new validator();
        $x->className = $className;
        $x->validateClassExists();
        if(!$x->continueTests){
            return $x;
        }
        $x->wrappedInReflector = new ReflectionClass($className);
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

    private function addError($message){
        array_push($this->errorLog, $message);
    }

    public function report(){
        if(count($this->errorLog) > 0){
            echo "Validation failed for class $this->className<br>";
            foreach($this->errorLog as $error){
                echo " - $error<br>";
            }
        }
        else{
            echo "Validation passed for class $this->className<br>";
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
        $this->method($methodName);
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
        $this->method($methodName);
        if (!$this->wrappedInReflector->getMethod($methodName)->isPrivate()) {
            $this->addError("Method $methodName is not private in class $this->className");
        }
        return $this;
    }

    public function checkConstructor($expectedParameterCount)
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
        $this->property($propertyName);
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
        $this->property($propertyName);
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

validator::Create('Rectangle', 2, function(){
    return new Rectangle(5, 10);
})
    ->propertyPublic("width")
    ->propertyPrivate("height")
    ->checkConstructor(2)
    ->methodParameterCount("__construct", 2)
    ->methodPublic("calculateArea")
    ->methodParameterCount("calculateArea", 0)
    ->methodPrivate("calculatePerimeter")
    ->methodParameterCount("describeRectangle", 0)
    ->methodPublic("describeRectangle")
    ->methodParameterCount("describeRectangle", 0)
    ->breakpoint()
    ->execute("calculateArea")
    ->assertResultEquals(50)
    ->execute("calculatePerimeter")
    ->assertResultEquals(30)
    ->execute("describeRectangle")
    ->assertResultEquals("The rectangle has a width of 5, a height of 10, an area of 50, and a perimeter of 30.")
    ->report();