<?php
//Repareer de class "Student"
      {

    //properties
    public $name;
    public $number;
}














//Controle code

if (class_exists('Student')) {
    echo "Gelukt: de class `Student` is aangemaakt.<br>";
} else {
    echo "Fout: de class `Student` bestaat niet.<br>";
    exit(1);
}

$x = new Student();
$x->name = "timmy";
$x->number = 123;

if($x->name == "timmy" && $x->number == 123){
    echo "Gelukt: de class `Student` werkt.<br>";
} else {
    echo "Fout: de class `Student` werkt niet.<br>";
    exit(1);
}


Echo "Everything correct";