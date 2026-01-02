<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/main.jsx'])
</head>
<body>
    <div id="root"></div>
    <noscript>You need to enable JavaScript to run this app.</noscript>
    
    <!-- Admin Auto-Refresh Script - Only runs on admin panel -->
    <script>
        // Check if we're on the admin panel (Filament)
        if (window.location.pathname.includes('/admin')) {
            // Auto-refresh admin panel every 30 seconds
            setInterval(() => {
                window.location.reload();
            }, 30000);
        }
    </script>
</body>
</html>
