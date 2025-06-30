<?php

  return [
      'paths' => ['api/*', 'sanctum/csrf-cookie'],
      'allowed_methods' => ['*'],
      'allowed_origins' => ['http://localhost:5173'], // Adjust to your Vite port
      'allowed_headers' => ['*'],
      'exposed_headers' => [],
      'max_age' => 0,
      'supports_credentials' => true,
  ];