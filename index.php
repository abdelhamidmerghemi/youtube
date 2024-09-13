<?php

// Set allowed HTTP methods
$allowedMethods = 'GET, POST';

// Set allowed origin (replace '*' with specific origin if required)
$allowedOrigin = '*';

// Set allowed headers (replace '*' with specific headers if required)
$allowedHeaders = '*';

// Set allowed credentials (true or false)
$allowCredentials = 'true';

// Set max age (in seconds)
$maxAge = '3600';

// Set allowed headers
header("Access-Control-Allow-Origin: $allowedOrigin");
header("Access-Control-Allow-Methods: $allowedMethods");
header("Access-Control-Allow-Headers: $allowedHeaders");
header("Access-Control-Allow-Credentials: $allowCredentials");
header("Access-Control-Max-Age: $maxAge");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Function to get player response
function getPlayerResponse($videoId) {
    $url = 'https://www.youtube.com/youtubei/v1/player?key=AIzaSyA8eiZmM1FaDVjRy-df2KTyQ_vz_yYM39w&prettyPrint=false';

    $data = array(
        'context' => array(
            'client' => array(
                'clientName' => 'ANDROID_TESTSUITE',
                'clientVersion' => '1.9',
                'androidSdkVersion' => 30,
                'hl' => 'en',
                'gl' => 'US',
                'utcOffsetMinutes' => 0,
            ),
        ),
        'videoId' => $videoId,
    );

    $headers = array(
        'Content-Type: application/json',
        'User-Agent: com.google.android.youtube/17.36.4 (Linux; U; Android 12; GB) gzip',
    );

    $options = array(
        'http' => array(
            'header'  => implode("\r\n", $headers),
            'method'  => 'POST',
            'content' => json_encode($data),
        ),
    );

    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    return $response;
}

// Handle request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$videoId = $_GET['videoId'];


    // Get player response
    $response = getPlayerResponse($videoId);

    // Set content type to JSON
    header('Content-Type: application/json');

    // Output response
    echo $response;
} else {
    // If request method is not allowed, return 405 Method Not Allowed
    http_response_code(405);
}

?>

