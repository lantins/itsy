<?php



class itsy_suite extends itsy_framework_testsuite
{
  public static function suite()
  {
    $suite = new itsy_suite('Itsy Core');
    $suite->addAllTestFromDir();
    
    return $suite;
  }
}

?>