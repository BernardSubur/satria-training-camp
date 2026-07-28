<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/demo-register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
// CSRF token is disabled for API or we can just bypass it?
// We need to bypass CSRF to make this test work.
// Let's modify routes/web.php temporarily to use GET for /demo-register.
