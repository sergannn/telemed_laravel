<?php

$apiKey = '76b9a8244e1657186429a60d2a9007e81956c1c1e3927eff228ee058b8040160';
$roomName = 'My Test Room';
$maxParticipants = 4;

try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.daily.co/v1/rooms/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'properties' => [
            'room_name' => $roomName,
            'max_participants' => $maxParticipants,
            'exp' => time() + 3600 // Expire in 1 hour
        ]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $apiKey]);

    $response = curl_exec($ch);

    if ($response === false) {
        throw new Exception(curl_error($ch));
    }

    $responseData = json_decode($response, true);

    if (isset($responseData['error'])) {
        echo "API request failed: " . $responseData['error'] . "\n";
    } else {
        echo "Room created successfully. ID: {$responseData['id']}\n";
    }

} catch (Exception $e) {
    echo 'An error occurred: ',  $e->getMessage(), "\n";
}
