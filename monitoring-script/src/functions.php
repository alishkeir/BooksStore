<?php

require_once 'src/MailerManager.php';

/**
 * Handle the email request.
 *
 * @param object|null $response_data The response data object.
 * @param string $store The store name.
 * @param string $task The task name.
 */
function handleEmailRequest($store = "", $task)
{

    $storeName = ucwords($store);
    $response_data = getData($_ENV['API_URL'], $store, $task);


    if ($task == "check") {
        $orderCount = $response_data->orderCount ?? 0;
        // [if there were no purchase, send an e-mail to a few admins ] -- if there are purchases, don't send email
        if ($orderCount > 0) {
            echo ('Purchases found: ' . $orderCount) . PHP_EOL;
            logEvent('Purchases found: ' . $orderCount);
            exit(1);
        }
        sendEmail($store, constructMessage($store, 0, 0), $storeName);
        exit(1);
    } else {
        sendEmail("alomgyar", constructMessage("alomgyar", $response_data->alomgyarOrderCount, $response_data->alomgyarTotalAmount), "alomgyar");
        sendEmail("olcsokonyvek", constructMessage("olcsokonyvek", $response_data->olcsokonyvekOrderCount, $response_data->olcsokonyvekTotalAmount), "olcsokonyvek");
        exit(1);

    }

}

/**
 * Send an email.
 *
 * @param int $store The store ID.
 * @param string $message The email message text.
 */
function sendEmail($store = 0, $message = '', $storeName = '')
{
    $email = "info@" . $store . ".hu";

    $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    $recipients = preg_grep($emailRegex, explode(',', str_replace(' ', '', $_ENV[strtoupper($storeName) . '_MAIL_RECIPIENTS']))); // Filter out invalid emails from the recipients env

    $subject = 'Order Count information';

    $mailerManager = new \monitoring\src\MailerManager(new PHPMailer\PHPMailer\PHPMailer());

    // Configure and send the email using the MailerManager
    $mailerManager->configureAndSend($email, $recipients, $subject, $message, $storeName);
}

function logEvent($message)
{
    $logFilePath = "./log/" . date('Y-m-d') . '.log';

    // Extract the directory from the file path
    $logDirectory = dirname($logFilePath);

    // Check if the directory exists, and create it if not
    if (!is_dir($logDirectory)) {
        // The third parameter true ensures that the directory is created recursively
        mkdir($logDirectory, 0755, true);
    }

    // Now you can log your message
    error_log("[" . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL, 3, $logFilePath);
}



function getData($API_URL, $store, $task)
{

    /**
     * Define API URLs.
     *
     * @var string $orderURL URL for order count API.
     */
    $orderURL = $API_URL . "/gephaz/" . ($task == "daily_report" ? 'report' : ($store == "alomgyar" ? 0 : 1)) . "/order/counter";


    /**
     * Initialize cURL session for order count request.
     *
     * @var resource|false|CurlHandle $ch cURL session handle.
     */
    $ch = curl_init();

    /**
     * Set cURL options for order count request.
     */
    curl_setopt($ch, CURLOPT_URL, $orderURL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("X_MONITORING_API_KEY:$_ENV[X_MONITORING_API_KEY]"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /**
     * Execute the cURL session for order count and get the response.
     *
     * @var mixed $response The response obtained from the order count request.
     */
    $response = curl_exec($ch);

    /**
     * Check for cURL errors in the request.
     */
    if (curl_errno($ch)) {
        echo 'Request error: ' . curl_error($ch) . PHP_EOL;
        logEvent('Request error: ' . curl_error($ch));
    }

    /**
     * Get HTTP status code from the cURL response.
     *
     * @var int $http_code HTTP status code.
     */
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    /**
     * Close the cURL session.
     */
    curl_close($ch);

    /**
     * Decode the response data from JSON.
     *
     * @var mixed $response_data Decoded response data from the order count request.
     */
    $response_data = json_decode($response);

    /**
     * Check for authentication or store code errors in the response.
     */
    if (isset($response_data->authError) && $response_data->authError) {
        echo "Wrong X_MONITORING_API_KEY" . PHP_EOL;
        logEvent("Wrong X_MONITORING_API_KEY");
        exit(1);
    }

    if (isset($response_data->storeCodeError) && $response_data->storeCodeError) {
        echo "Cannot find store $store" . PHP_EOL;
        logEvent("Cannot find store $store");
        exit(1);
    }

    if (isset($response_data->timeError) && $response_data->timeError) {
        echo "Cannot run sales check at this time" . PHP_EOL;
        logEvent("Cannot run sales check at this time");
        exit(1);
    }

    /**
     * Check if the response contains valid data and countSuccess is true.
     */
    if (isset($response_data->countSuccess) && $response_data->countSuccess) {
        return $response_data;
    } else {
        echo "An error occured while fetching data" . PHP_EOL;
        logEvent("An error occured while fetching data");
        exit(1);
    }


}

function constructMessage($store = "", $count = 0, $amount = 0)
{
    if ($count == 0)
        return "<h3>" . ucwords($store) . " - nem érkezett be megrendelés.</h3>";

    return "<h3>" . mb_convert_encoding(ucwords($store) . " - a leadott megrendelések száma: $count ($amount HF)", 'UTF-8') . "</h3>";

}