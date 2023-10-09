// Set the API endpoint URL
$url = 'https://example.com/api/endpoint';

// Set the POST data
$data = array(
    'param1' => 'value1',
    'param2' => 'value2'
);

// Set the HTTP headers
$headers = array(
    'Content-Type: application/json'
);

// Initialize cURL
$ch = curl_init();

// Set the cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL request
$response = curl_exec($ch);

// Close the cURL session
curl_close($ch);

// Return the response
echo $response;
