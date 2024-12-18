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
$wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id("search")));
$searchIcon = $driver->findElement(WebDriverBy::id("search"));
$searchIcon->click();

$wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id("searchInput")));


$searchInput = $driver->findElement(WebDriverBy::id("searchInput"));
$searchInput->sendKeys("Sneakers");

$products = $driver->findElements(WebDriverBy::cssSelector(".box"));

$productFound = false;
foreach ($products as $product) {
    $productName = $product->findElement(WebDriverBy::tagName("h3"))->getText();
    if (strpos(strtolower($productName), "sneakers") !== false) {
        $productFound = true;
        break;
    }
}

if ($productFound) {
    echo "Test passed: A product matching the search query 'Sneakers' was found.\n";
} else {
    echo "Test failed: No product matching the search query 'Sneakers' was found.\n";
}

$driver->quit();
?>
