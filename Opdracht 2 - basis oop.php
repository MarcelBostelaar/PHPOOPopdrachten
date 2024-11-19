<?php
//Fix the class "Turtle" and add a constructor

{
    public $name;
    public $age;
    public $position;


    public function moveForward($distance)
    {
        $this->position += $distance;
    }

    public function moveBackward($distance)
    {
        $this->position -= $distance;
    }

    public function displayInfo()
    {
        return "{$this->name}, {$this->age}, {$this->position}";
    }
}










//controle code

$turtle = new Turtle("Leonardo", 5);
$turtle2 = new Turtle("Donatello", 9);
echo "Succes: Turtle made succesfully<br>";
if ($turtle->displayInfo() == "Leonardo, 5, 0" && $turtle2->displayInfo() == "Donatello, 9, 0"){
    echo "Succes: Turtle values set correctly by constructor<br>";
}
else{
    echo "Mistake: Turtle values not set correctly by constructor.<br>
First turtle should be: Leonardo, 5, 0<br>
Instead got: " . $turtle->displayInfo() . "<br>" . "
    Second turtle should be: Donatello, 9, 0<br>
Instead got: " . $turtle->displayInfo() . "<br>";
    echo "Remember to not manually set the info in the constructor, but to use function arguments instead.";
    exit(1);
}

$turtle->moveForward(10);
if ($turtle->displayInfo() == "Leonardo, 5, 10"){
    echo "Succes: Turtle can move forward<br>";
}
else{
    echo "Mistake: Turtle cant move forward, you edited something you shouldn't have.<br>
Should be: Leonardo, 5, 10<br>
Instead got: " . $turtle->displayInfo() . "<br>";
    exit(1);
}

$turtle->moveBackward(5);
if ($turtle->displayInfo() == "Leonardo, 5, 5"){
    echo "Succes: Turtle can move backwards<br>";
}
else{
    echo "Mistake: Turtle cant move backwards, you edited something you shouldn't have.<br>
Should be: Leonardo, 5, 5<br>
Instead got: " . $turtle->displayInfo() . "<br>";
    exit(1);
}

Echo "Everything correct";