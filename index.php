<?php
header('ngrok-skip-browser-warning', 1233);
error_reporting(1);
ini_set('display_errors', true);

require_once('config/app.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title><?= APP_NAME; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <style>
     body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f4f4f4;
    }

    .instructions-container {
      max-width: 300px;
      width: 100%;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    ol {
      list-style-type: decimal;
      padding-left: 20px;
      text-align: left;
    }

    li {
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
<div class="instructions-container">
  <h2>Instructions:</h2>
  <ol>
    <li>Start by opening the box.</li>
    <li>Take out the contents carefully.</li>
    <li>Assemble the parts as per the manual.</li>
    <li>Connect the device to a power source.</li>
    <li>Turn on the device and follow on-screen instructions.</li>
  </ol>
  <a target="_blank" href="<?= AUTHORIZATION_URL; ?>?response_type=<?= RESPONSE_TYPE; ?>&client_id=<?= CLIENT_ID; ?>&scope=<?= SCOPES ?>" class="btn btn-primary mt-2">Edit Redirection Rules</a>
</div>
    
</body>

</html>