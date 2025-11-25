<?php
$ctx = stream_context_create(['http'=>['timeout'=>5]]);
$headers = @get_headers('http://127.0.0.1:8000', 1);
if ($headers === false) {
    echo "No response from server\n";
    exit(1);
}
echo implode("\n", (array)$headers) . "\n";
