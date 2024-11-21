<?php

class Rectangle
{
    public $width;
    public $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    //Implementeer de calculateArea functie

    //Implementeer de calculatePerimeter functie

    public function describeRectangle()
    {
        //Hier worden de functies die je moet maken gebruikt,
        // je kan dus je methods ook in je eigen class aanroepen!
        $area = $this->calculateArea();
        $perimeter = $this->calculatePerimeter();
        return "The rectangle has a width of {$this->width}, a height of {$this->height}, an area of {$area}, and a perimeter of {$perimeter}.";
    }
}
























//controle code

function hasProperty($object, $propertyName){
    $reflection = new ReflectionClass($object);
    return $reflection->hasProperty($propertyName);
}

$rectangle1 = new Rectangle(8, 6);
$rectangle2 = new Rectangle(4, 3);
$allPropertiesExist = true;
//check if calculatePerimeter exists
if(!method_exists($rectangle1, "calculatePerimeter")){
    echo "Mistake: Rectangle does not have a calculatePerimeter method<br>";
    $allPropertiesExist = false;
}
if(!method_exists($rectangle2, "calculateArea")){
    echo "Mistake: Rectangle does not have a calculateArea method<br>";
    $allPropertiesExist = false;
}
if(!$allPropertiesExist){
    exit(1);
}

function checkResult($obj, $funccall, $expectedResult){
    $result = $obj->{$funccall}();
    $allGood = true;
    //check typeof result
    if(!is_int($result)){
        echo "Mistake: {$funccall} returns value '{$result}' which is not an integer but a " . gettype($result) . "<br>";
        $allGood = false;
    }
    else{
        if($result != $expectedResult){
            echo "Mistake: calculateArea returns value {$result} instead of {$expectedResult}<br>";
            $allGood = false;
        }
    }
    return $allGood;
}

$resultsAreCorrect = true;
$resultsAreCorrect &= checkResult($rectangle1, "calculateArea", 48);
$resultsAreCorrect &= checkResult($rectangle2, "calculateArea", 12);
$resultsAreCorrect &= checkResult($rectangle1, "calculatePerimeter", 28);
$resultsAreCorrect &= checkResult($rectangle2, "calculatePerimeter", 14);
if(!$resultsAreCorrect){
    exit(1);
}
if($rectangle1->describeRectangle() != "The rectangle has a width of 8, a height of 6, an area of 48, and a perimeter of 28."){
    echo "Mistake: describeRectangle returns wrong value. Remember to not change the describeRectangle function.<br>";
    exit(1);
}

echo "All tests passed!";