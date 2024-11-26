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

#VALIDATORREPLACE

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
