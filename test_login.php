<?php
require_once('vendor/autoload.php');

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;

$options = new ChromeOptions();
$options->addArguments(["--disable-gpu", "--window-size=1920x1080"]); 

$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $options);


$driver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);


$driver->get("http://localhost/TOKOSEPATU/login.php");


$emailField = $driver->findElement(WebDriverBy::id("email"));
$passwordField = $driver->findElement(WebDriverBy::id("password"));

$emailField->sendKeys("admin@gmail.com");  
$passwordField->sendKeys("admin123");    

$loginButton = $driver->findElement(WebDriverBy::xpath("//button[@type='submit']"));
$loginButton->click();

sleep(5); 

echo "Current URL: " . $driver->getCurrentURL() . "\n";

echo "Page title after login: " . $driver->getTitle() . "\n";

$driver->quit();

?>
