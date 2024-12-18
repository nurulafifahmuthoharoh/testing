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

$driver->get("http://localhost/TOKOSEPATU/payment.php");

$wait = new WebDriverWait($driver, 10);
$wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id("name")));

$name = $driver->findElement(WebDriverBy::id("name"));
$name->sendKeys("Nabiel Pramudya");

$address = $driver->findElement(WebDriverBy::id("address"));
$address->sendKeys("Semarang");

$email = $driver->findElement(WebDriverBy::id("email"));
$email->sendKeys("nabielpramudyafc@gmail.com");

$phone = $driver->findElement(WebDriverBy::id("phone"));
$phone->sendKeys("081310287270");

$submitButton = $driver->findElement(WebDriverBy::cssSelector("button[type='submit']"));
$driver->executeScript("arguments[0].scrollIntoView(true);", [$submitButton]);

$wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::cssSelector("button[type='submit']")));

$submitButton->click();

$wait->until(WebDriverExpectedCondition::urlContains("https://api.whatsapp.com/send?phone=081310287270&text="));

if (strpos($driver->getCurrentURL(), 'https://api.whatsapp.com/send?phone=081310287270&text=') !== false) {
    echo "Test passed: Checkout redirected to WhatsApp with the correct order message.\n";
} else {
    echo "Test failed: Checkout did not redirect to WhatsApp.\n";
}

$driver->quit();
?>
