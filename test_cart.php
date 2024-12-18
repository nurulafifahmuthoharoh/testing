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

$driver->get("http://localhost/TOKOSEPATU/index.php");

$wait = new WebDriverWait($driver, 10);
$wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id("cart-count")));

$wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::cssSelector(".btn.btn-primary")));
$addToCartButton = $driver->findElement(WebDriverBy::cssSelector(".btn.btn-primary"));
$addToCartButton->click();
$wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id("cart-count")));

$cartCount = $driver->findElement(WebDriverBy::id("cart-count"))->getText();

if (intval($cartCount) > 0) {
    echo "Test passed: Product successfully added to the cart.\n";
} else {
    echo "Test failed: Product was not added to the cart.\n";
}

$driver->quit();
?>
