<?php
class Logger {
  private function addEntry($message){
    date_default_timezone_set('Europe/Athens');
    $fileName = sprintf("./webVisits/visits_%s\n", date('c'));
    $handle = fopen($fileName, 'a');
    fwrite($handle, sprintf("%s %s\n", date('c'), $message));
    fclose($handle);
  }

  public function warn($message){
    self::addEntry(self::addVisitorInfo()." [WARNING] ".$message);
  }

  public function info($message){
    self::addEntry(self::addVisitorInfo()." [INFO] ".$message);
  }

  public function debug($message){
    self::addEntry(self::addVisitorInfo()." [DEBUG] ".$message);
  }

  public function navigateToPage() {
    self::addEntry(self::addVisitorInfo()." -- Navigated to page: ".$_SERVER["REQUEST_URI"]);
  }

  private static function addVisitorInfo() {
    return "IP [".self::getRealUserIp()."] \n Device: ".$_SERVER["HTTP_USER_AGENT"]." -- Page: ".$_SERVER["REQUEST_URI"];
  }

  private static function getRealUserIp(){
    switch(true){
      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
      default : return $_SERVER['REMOTE_ADDR'];
    }
  }
}
?>
