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