<?php
// error reporting
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// w set?
if (!isset($_GET['w']) || empty($_GET['w'])) {
    http_response_code(400); // Bad Request
    echo "Fehler: Es wurde keine URL angegeben.";
    exit;
}

// base64 encoded url decode
$encodedUrl = $_GET['w'];
$decodedUrl = base64_decode($encodedUrl, true);

// url valid?
if ($decodedUrl === false || !filter_var($decodedUrl, FILTER_VALIDATE_URL)) {
    http_response_code(400); // Bad Request
    echo "Fehler: Ungültige URL.";
    exit;
}

// get
$options = [
    "http" => [
        "header" => "User-Agent: CB-Proxy/1.0\r\n"
    ]
];
$context = stream_context_create($options);
$response = @file_get_contents($decodedUrl, false, $context);

// error handling
if ($response === false) {
    http_response_code(500); // Internal Server Error
    echo "Fehler: Die angeforderte URL konnte nicht abgerufen werden.";
    exit;
}

// set headers
$headers = $http_response_header ?? [];
foreach ($headers as $header) {
    if (stripos($header, "Content-Type:") === 0) {
        header($header);
        break;
    }
}

// echo response
echo $response;
?>