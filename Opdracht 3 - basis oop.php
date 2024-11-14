<?php
class Plant {
    public $name;
    public $age;
    public $height;

    //implement a constructor

    //Implement the "grow" and "cut" methods. Both take a number as an argument and modify the plants height.

    public function displayInfo() {
        return "{$this->name}, {$this->age}, {$this->height}";
    }
}
















//controle code
$allok = True;

$turtle = new Plant("Leafy", 5, 6);
echo "Succes: Plant made succesfully<br>";
if ($turtle->displayInfo() == "Leafy, 5, 6"){
    echo "Succes: Plant values set correctly by constructor<br>";
}
else{
    echo "Mistake: Plant values not set correctly by constructor.<br>
Should be: Leafy, 5, 6<br>
Instead got: " . $turtle->displayInfo() . "<br>";
    exit(1);
}

if (! method_exists('Plant', "grow")) {
    echo "Mistake: The method grow does not exist in the class 'ExampleClass'.<br>";
    $allok = False;
} else {
    $turtle->grow(4);
    if ($turtle->displayInfo() == "Leafy, 5, 10"){
        echo "Succes: Plant can grow<br>";
    }
    else{
        echo "Mistake: Plant cant grow correctly.<br>
Should be: Leafy, 5, 10<br>
Instead got: " . $turtle->displayInfo() . "<br>";
        exit(1);
    }
}


if (! method_exists('Plant', "cut")) {
    echo "Mistake: The method cut does not exist in the class 'ExampleClass'.<br>";
    $allok = False;
} else {
    $turtle->cut(5);
    if ($turtle->displayInfo() == "Leafy, 5, 5") {
        echo "Succes: Plant can be cut<br>";
    } else {
        echo "Mistake: Plant cant be cut.<br>
Should be: Leafy, 5, 5<br>
Instead got: " . $turtle->displayInfo() . "<br>";
        exit(1);
    }
}

if($allok) {
    echo "Everything correct";
}