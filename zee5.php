<?php

/* Fill this with your actual access token  */
$access_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwbGF0Zm9ybV9jb2RlIjoiV2ViQCQhdDM4NzEyIiwiaXNzdWVkQXQiOiIyMDI0LTEyLTIzVDA3OjUxOjI4Ljk1NVoiLCJwcm9kdWN0X2NvZGUiOiJ6ZWU1QDk3NSIsInR0bCI6ODY0MDAwMDAsImlhdCI6MTczNDk0MDI4OH0.vQnv04rxYEi6EScLAPv0RkdvqCvqhSTLy5bdwkDTE-k";
$authorization_token = "eyJraWQiOiJkZjViZjBjOC02YTAxLTQ0MWEtOGY2MS0yMDllMjE2MGU4MTUiLCJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI2MjkwRTMzQS01RDQwLTQxOTQtQTdENS1FMEZEMDRCNDY4NjIiLCJkZXZpY2VfaWQiOiIxMTg5MGI1Zi1iZTQ1LTQ1MTgtOWI2Ny0zMzc1YzUwYzY2N2UiLCJhbXIiOlsiZGVsZWdhdGlvbiJdLCJ0YXJnZXRlZF9pZCI6dHJ1ZSwiaXNzIjoiaHR0cHM6Ly91c2VyYXBpLnplZTUuY29tIiwidmVyc2lvbiI6MTAsImNsaWVudF9pZCI6InJlZnJlc2hfdG9rZW4iLCJhdWQiOlsidXNlcmFwaSIsInN1YnNjcmlwdGlvbmFwaSIsInByb2ZpbGVhcGkiLCJnYW1lLXBsYXkiXSwidXNlcl90eXBlIjoiUmVnaXN0ZXJlZCIsIm5iZiI6MTczNDk1NTc5NCwidXNlcl9pZCI6IjYyOTBlMzNhLTVkNDAtNDE5NC1hN2Q1LWUwZmQwNGI0Njg2MiIsInNjb3BlIjpbInVzZXJhcGkiLCJzdWJzY3JpcHRpb25hcGkiLCJwcm9maWxlYXBpIl0sInNlc3Npb25fdHlwZSI6IkdFTkVSQUwiLCJleHAiOjE3MzUzMDEzOTQsImlhdCI6MTczNDk1NTc5NCwianRpIjoiOGJhMjBiYjEtNDMxYy00MmU2LTllY2MtY2UxMjk2YjQ0NzgxIn0.e0ecvJCjr9-DnS2pDcxtfdX_VOhe-zMI6TFb3ouHs5Y9AoNBAVYHygLGQKhodgkF8fbV0JmHaXPRk9bqRBs6P78B7Mh980WG4fKzyvEEPPTXsyqC56U8QCkcpznw1B2wGrNFYcFv7CmTXEauEiGhs04760-TKBc9WalmRMCFTqmjBdmF3sQaiWEfpwEc5Ih3-B3KHW0LoTWVuz3dxwJY-zV2aKix9-_Bw7PQbS9A0CMIQHTRupJZ89_j5A8weDmLwwUXO1eRK5z4jNtE5kIOVuRg_h7xOmHDycaoWFi4EZSHZhZpvpuR_ZJnlA2bv1WJrAyPDiuMm43SmttCQ5zTZQ";
$device_id="11890b5f-be45-4518-9b67-3375c50c667e";

if (isset($_GET["id"])) {
    $channel = $_GET["id"];
} else {
    exit("Error: Channel ID not found.");
}

$curl = curl_init();

$url = "https://spapi.zee5.com/singlePlayback/getDetails/secure?channel_id=$channel&&device_id=$device_id&platform_name=desktop_web&country=IN&check_parental_control=false";

curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "application/json",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => '{
        "x-access-token": "' . $access_token . '",
        "Authorization": "' . $authorization_token . '"
    }',
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($curl);

if ($response === false) {
    exit("cURL Error: " . curl_error($curl));
}

curl_close($curl);

$zx = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    exit("JSON Decode Error: " . json_last_error_msg());
}

if (isset($zx["keyOsDetails"]) && isset($zx["keyOsDetails"]["video_token"])) {
    $playit = $zx["keyOsDetails"]["video_token"];
    header("Location: $playit");
} else {
    exit("Error: Video URL not found in the response.");
}

?>
