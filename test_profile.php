<?php
require_once('vendor/autoload.php');

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;

// Set up Chrome options and WebDriver
$options = new ChromeOptions();
$options->addArguments(["--disable-gpu", "--window-size=1920x1080"]);

$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

$driver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);

// Langsung ke halaman login dan login
$driver->get("http://localhost/TOKOSEPATU/login.php");

// Tunggu elemen input login muncul
$wait = new WebDriverWait($driver, 10);
$wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id("email")));

// Isi form login
$driver->findElement(WebDriverBy::id("email"))->sendKeys("admin@gmail.com");
$driver->findElement(WebDriverBy::id("password"))->sendKeys("admin123");
$driver->findElement(WebDriverBy::cssSelector("button[type='submit']"))->click();

// Tunggu sampai halaman index terbuka
$wait->until(WebDriverExpectedCondition::urlContains("index.php"));

// Verifikasi login sukses
if ($driver->getCurrentURL() !== 'http://localhost/TOKOSEPATU/index.php') {
    echo "Login failed: Redirected to wrong page.\n";
    $driver->quit();
    exit();
}

// Pilih menu 'Profil' yang mengarahkan ke halaman profile.php
$driver->findElement(WebDriverBy::linkText("Profil"))->click();

// Tunggu sampai halaman profil terbuka
$wait->until(WebDriverExpectedCondition::urlContains("profile.php"));

// Verifikasi halaman profil terbuka
if ($driver->getCurrentURL() !== 'http://localhost/TOKOSEPATU/profile.php') {
    echo "Test failed: Redirected to wrong page after clicking profile.\n";
    $driver->quit();
    exit();
}

// Tunggu sampai form profil tersedia
$wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id("username")));

// Isi form username baru (hanya username yang diganti)
$driver->findElement(WebDriverBy::id("username"))->clear();
$driver->findElement(WebDriverBy::id("username"))->sendKeys("newadmin");

// Kirim form untuk memperbarui username
$driver->findElement(WebDriverBy::cssSelector("button[type='submit']"))->click();

// Tunggu sampai pesan sukses atau halaman terupdate
$wait->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector(".alert-success")));

// Verifikasi pesan sukses
if ($driver->findElement(WebDriverBy::cssSelector(".alert-success"))->getText() === "Profile updated successfully!") {
    echo "Test passed: Profile updated successfully.\n";
} else {
    echo "Test failed: Profile update not successful.\n";
}

// Cek apakah username baru terlihat di halaman
$currentUsername = $driver->findElement(WebDriverBy::id("username"))->getAttribute('value');

if ($currentUsername === "newadmin") {
    echo "Test passed: Username updated correctly.\n";
} else {
    echo "Test failed: Username did not update correctly.\n";
}

$driver->quit();
?>
