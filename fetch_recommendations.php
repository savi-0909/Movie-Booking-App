<?php
// Initialize cURL session
$curl = curl_init();

// Set the URL to fetch
$api_url = "https://graph.imdbapi.dev/v1";

// Set cURL options
curl_setopt($curl, CURLOPT_URL, $api_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string

// Execute cURL session
$response = curl_exec($curl);

// Check for errors
if ($response === false) {
    $error = curl_error($curl);
    echo json_encode(["error" => "cURL Error: $error"]);
    exit;
}

// Close cURL session
curl_close($curl);

// Output the response
header('Content-Type: application/json');
echo $response;
?>
