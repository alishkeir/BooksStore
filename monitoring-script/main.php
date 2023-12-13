<?php
/**
 * Set the content type to HTML with UTF-8 encoding.
 */
header('Content-Type: text/html; charset=utf-8');

/**
 * Include necessary files and functions.
 */
require 'vendor/autoload.php';
require_once 'src/functions.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();


try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    echo 'Error loading .env file: ' . $e->getMessage();
    logEvent('Error loading .env file: ' . $e->getMessage());
    exit(1);
}

/**
 * Command Line Usage: php main.php <taskID> <storeID> <API_URL> <email> <password>
 */

/**
 * Check if taskID is provided.
 *
 * @param int $argv[1] - Task ID provided as a command line argument.
 */
if (!isset($argv[1])) {
    echo "----------------------------------------------------------------" . PHP_EOL;
    echo "No task provided Exiting..." . PHP_EOL;
    echo "----------------------------------------------------------------" . PHP_EOL;
    logEvent("No task provided Exiting...");
    exit(1);
}

/**
 * Validate taskID.
 *
 * @param int $argv[1] - Task ID provided as a command line argument.
 */
if (!in_array($argv[1], ['check', 'daily_report'])) {
    echo "----------------------------------------------------------------" . PHP_EOL;
    echo "Invalid task provided. Valid tasks:" . PHP_EOL;
    echo "- check" . PHP_EOL;
    echo "- daily_report" . PHP_EOL;
    echo "Exiting..." . PHP_EOL;
    echo "----------------------------------------------------------------" . PHP_EOL;
    logEvent('Invalid task provided');
    exit(1);
}


if ($argv[1] !== 'daily_report') {

    /**
     * Check if storeID is provided.
     *
     * @param int $argv[2] - Store ID provided as a command line argument.
     */

    if (!isset($argv[2])) {
        echo "----------------------------------------------------------------" . PHP_EOL;
        echo "No store provided Exiting..." . PHP_EOL;
        echo "----------------------------------------------------------------" . PHP_EOL;
        logEvent('No store provided');
        exit(1);
    }

    /**
     * Validate storeID.
     *
     * @param int $argv[2] - Store ID provided as a command line argument.
     */
    if (!in_array($argv[2], ['alomgyar', 'olcsokonyvek'])) {
        echo "----------------------------------------------------------------" . PHP_EOL;
        echo "Invalid store provided. Valid stores:" . PHP_EOL;
        echo "- alomgyar" . PHP_EOL;
        echo "- olcsokonyvek" . PHP_EOL;
        echo "Exiting..." . PHP_EOL;
        echo "----------------------------------------------------------------" . PHP_EOL;
        logEvent('Invalid store provided');
        exit(1);
    }

}


/**
 * Represents the unique identifier of a store.
 *
 * @var string $task The task identifier.
 * @var string $store The store identifier.
 */
$task = $argv[1];
$store = $argv[2] ?? "";



handleEmailRequest($store, $task);