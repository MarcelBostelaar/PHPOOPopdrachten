<?php

class Car{

    function __construct($color, $x, $y){
        $this->color = $color;
        $this->x = $x;
        $this->y = $y;
    }

    function driveTo($x, $y){
        $this->x = $x;
        $this->y = $y;
    }

    function describeCar(){
        return "The " . $this->color . " car is at '" . strval($this->x) . ', ' . strval($this->y) . "'";
    }
}

//controle code
function hasProperty($object, $propertyName){
    $reflection = new ReflectionClass($object);
    return $reflection->hasProperty($propertyName);
}

function isPropertyPrivate($object, $propertyName) {
    $reflection = new ReflectionClass($object);
    if ($reflection->hasProperty($propertyName)) {
        $property = $reflection->getProperty($propertyName);
        return $property->isPrivate();
    }
    return false; // Property does not exist
}

$allCorrect = True;
$testCar = new Car("red", 4, 6);
if(!hasProperty($testCar, "x")){
    echo "Fout! Property 'x' bestaat niet<br>";
    $allCorrect = False;
}
if(!hasProperty($testCar, "y")){
    echo "Fout! Property 'y' bestaat niet<br>";
    $allCorrect = False;
}
if(!hasProperty($testCar, "color")){
    echo "Fout! Property 'color' bestaat niet<br>";
    $allCorrect = False;
}
if(!$allCorrect){
    exit(1);
}
if(!isPropertyPrivate($testCar, "y")){
    echo "Fout! Property 'y' is niet private<br>";
    $allCorrect = False;
}
if(!isPropertyPrivate($testCar, "x")){
    echo "Fout! Property 'x' is niet private<br>";
    $allCorrect = False;
}

$description = $testCar->describeCar();
$expected = "The red car is at '4, 6'";
if($description != $expected){
    echo "Fout! Constructor is fout, beschrijving moet zijn:<br>";
    echo $expected . "<br>Maar is:<br>";
    echo $description . "<br>";
    $allCorrect = False;
    exit(1);
}

$testCar->driveTo(7, 8);

$actual = $testCar->describeCar();
$expected = "The red car is at '7, 8'";
if($actual != $expected){
    echo "Fout! Er gaat iets fout in de driveTo functie, beschrijving moet zijn:<br>";
    echo $actual . "<br>Maar is:<br>";
    echo $description . "<br>";
    $allCorrect = False;
    exit(1);
}

if($allCorrect){
    echo "Alles correct!";
}
