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

$plant1 = new Plant("Leafy", 5, 6);
$plant2 = new Plant("Greenbean", 10, 3);
echo "Succes: Plant made succesfully<br>";
if ($plant1->displayInfo() == "Leafy, 5, 6" && $plant2->displayInfo() == "Greenbean, 10, 3"){
    echo "Succes: Plant values set correctly by constructor<br>";
}
else{
    echo "Mistake: Plant values not set correctly by constructor.<br>
Plant 1 should be: Leafy, 5, 6<br>
Instead got: " . $plant1->displayInfo() . "<br>";
    echo "Mistake: Plant values not set correctly by constructor.<br>
Plant 2 should be: Greenbean, 10, 3<br>
Instead got: " . $plant2->displayInfo() . "<br>";

    exit(1);
}

function growCutCheck($nameFunc, $funccall, $plant1Expected, $plant2Expected){
    global $plant1;
    global $plant2;
    if (! method_exists('Plant', $nameFunc)) {
        echo "Mistake: The method {$nameFunc} does not exist in the class 'Plant'.<br>";
        exit(1);
    } else {
        $funccall($plant1, 4);
        $funccall($plant2, 7);
        $plant1Actual = $plant1->displayInfo();
        $plant2Actual = $plant2->displayInfo();
        if ($plant1Actual == $plant1Expected && $plant2Actual == $plant2Expected){
            echo "Succes: Plant can $nameFunc<br>";
        }
        else{
            if($plant1Expected == $plant1Actual || $plant2Actual == $plant2Expected){
                ?>
                Mistake: One of the plants <?php echo $nameFunc ?> correctly, the other did not. <br>
                Remember to use a parameter in the method, not just add some value manually. Example: <br>

                function someFunction($madeMoney){<br>
                &nbsp;&nbsp;&nbsp;&nbsp;$this->myMoney += $madeMoney;<br>
                }<br>
                <?php
            }
            else{
                ?>
                Mistake: Plant doesn't <?php echo $nameFunc ?> correctly. Make sure you change the "height" value of the plant inside the method<br>
                Plant 1 expected: <?php echo $plant1Expected; ?> <br>
                Plant 1 actual: <?php echo $plant1Actual; ?><br>
                Plant 2 expected: <?php echo $plant2Expected; ?> <br>
                Plant 2 actual: <?php echo $plant2Actual; ?><br>
                <?php
            }
            exit(1);
        }
    }
}

growCutCheck("grow", function ($obj, $val){
    $obj->grow($val);
} , "Leafy, 5, 10", "Greenbean, 10, 10");
growCutCheck("cut", function ($obj, $val){
    $obj->cut($val);
}, "Leafy, 5, 6", "Greenbean, 10, 3");