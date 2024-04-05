<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bun Bay</title> <!-- Changed title to "Bun Bay" -->
    <link rel="icon" type="image/png" href="img/logo.png"> 
    <style>
        /* Style for the logo */
        .logo {
            position: fixed;
            top: 10px; /* Adjust top position */
            left: 10px; /* Adjust left position */
            width: 90px;
            height: auto;
            cursor: pointer;
        }
        
        .header {
            text-align: center; /* Center align the header content */
            margin-top: 10px; /* Add margin to the top */
            margin-bottom: 10px; /* Add margin to the bottom */
        }

        .header h1 {
            font-family: Times New Roman, sans-serif; 
            margin: 0; /* Remove all margins */
            font-style: italic; /* Ensure normal font style */
        }    

        .header h4 {
            margin: 0; /* Remove all margins */
            font-family: Georgia, serif;
        }

        /* Style for hovering effect (optional) */
        .logo:hover {
            opacity: 0.8; /* Adjust opacity as needed */
        }

        /* Remove default body margins and paddings */
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <!-- Clickable logo -->
    <a href="dashboard.php">
        <img src="img/logo.png" alt="Logo" class="logo">
    </a>
    <!-- Header section -->
    <header class="header">
        <h1>BUN BAY</h1> <!-- Changed header title to "Bun Bay" -->
        <h4>Students, We got you covered 😉</h4> <!-- Updated subtitle -->
    </header>
</body>
</html>
