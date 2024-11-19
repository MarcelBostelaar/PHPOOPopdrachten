<?php

class Milkshake{

    public $flavor;
    public $content;

    function __construct($flavor, $content){
        $this->flavor = $flavor;
        $this->content = $content;
    }

    function drink($amount){
        $this->content -= $amount;
    }

    function refill($amount){
        $this->content += $amount;
    }
}


/**
 * Read this function, try to understand what it does, and then write the milkshake class.
 * The checks in this function should not be changed, and dont need to be read.
 */
function usingTheMilkshake()
{
    //Using the milkshake
    $chocolateMilkshake = new Milkshake("chocolate", 100);
    $strawberryMilkshake = new Milkshake("strawberry", 300);

    checkConstructor($chocolateMilkshake, $strawberryMilkshake);

    $chocolateMilkshake->drink(20);
    $strawberryMilkshake->drink(50);

    checkDrink($chocolateMilkshake, $strawberryMilkshake);

    $chocolateMilkshake->refill(10);
    $strawberryMilkshake->refill(20);

    checkRefill($chocolateMilkshake, $strawberryMilkshake);
    finalCheck($chocolateMilkshake, $strawberryMilkshake);
}
























//controle code

function hasProperty($object, $propertyName){
    $reflection = new ReflectionClass($object);
    return $reflection->hasProperty($propertyName);
}

function checkConstructor($chocolate, $strawberry){
    $AllGood = True;
    if (!hasProperty($chocolate, "flavor")){
        echo "Mistake: Milkshake does not have a flavor property<br>";
        $AllGood = False;
    }
    if (!hasProperty($chocolate, "content")){
        echo "Mistake: Milkshake does not have a content property<br>";
        $AllGood = False;
    }
    if(!method_exists($chocolate, "drink")){
        echo "Mistake: Milkshake does not have a drink method<br>";
        $AllGood = False;
    }
    if(!method_exists($chocolate, "refill")){
        echo "Mistake: Milkshake does not have a refill method<br>";
        $AllGood = False;
    }
    if(!$AllGood){
        exit(1);
    }

    if($chocolate->flavor != "chocolate"){
        echo "Mistake: Chocolate milkshake flavor not set correctly<br>";
        echo "Should be: 'chocolate'<br>";
        echo "Is: '" . $chocolate->flavor . "'<br>";
        $AllGood = False;
    }
    if($chocolate->content != 100){
        echo "Mistake: Chocolate milkshake content not set correctly<br>";
        echo "Should be: 100<br>";
        echo "Is: " . $chocolate->content . "<br>";
        $AllGood = False;
    }

    if($strawberry->flavor != "strawberry"){
        echo "Mistake: Strawberry milkshake flavor not set correctly<br>";
        echo "Should be: 'strawberry'<br>";
        echo "Is: '" . $strawberry->flavor . "'<br>";
        $AllGood = False;
    }
    if($strawberry->content != 300){
        echo "Mistake: Strawberry milkshake content not set correctly<br>";
        echo "Should be: 300<br>";
        echo "Is: " . $strawberry->content . "<br>";
        $AllGood = False;
    }
    if(!$AllGood){
        exit(1);
    }
}

function checkDrink($chocolate, $strawberry){
    $AllGood = True;
    if($chocolate->content != 80){
        echo "Mistake: Chocolate milkshake content not updated correctly after drinking<br>";
        echo "Should be: 80<br>";
        echo "Is: " . $chocolate->content . "<br>";
        $AllGood = False;
    }
    if($strawberry->content != 250){
        echo "Mistake: Strawberry milkshake content not updated correctly after drinking<br>";
        echo "Should be: 250<br>";
        echo "Is: " . $strawberry->content . "<br>";
        $AllGood = False;
    }
    if(!$AllGood){
        exit(1);
    }
}

function checkRefill($chocolate, $strawberry){
    $AllGood = True;
    if($chocolate->content != 90){
        echo "Mistake: Chocolate milkshake content not updated correctly after refilling<br>";
        echo "Should be: 90<br>";
        echo "Is: " . $chocolate->content . "<br>";
        $AllGood = False;
    }
    if($strawberry->content != 270){
        echo "Mistake: Strawberry milkshake content not updated correctly after refilling<br>";
        echo "Should be: 270<br>";
        echo "Is: " . $strawberry->content . "<br>";
        $AllGood = False;
    }
    if(!$AllGood){
        exit(1);
    }
}

function finalCheck($chocolate, $strawberry){
    echo "All good!";
}

usingTheMilkshake();