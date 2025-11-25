<?php
$url = 'http://127.0.0.1:8000/oauth/token';
$ctx = stream_context_create(['http'=>['method'=>'POST','timeout'=>5]]);
$headers = @get_headers($url, 1);
if ($headers === false) {
    echo "No response\n";
    exit(1);
}
print_r($headers);
