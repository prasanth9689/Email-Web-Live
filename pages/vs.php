<?php
$logFile = __DIR__ . "/../log_.txt";

function parseLogLine($line) {
    preg_match('/^(.*?)\s+IP:\s+(.*?)\s+-\s+(.*)$/', $line, $matches);
    return [
        'datetime' => $matches[1] ?? '',
        'ip'       => $matches[2] ?? '',
        'status'   => $matches[3] ?? '',
    ];
}

// Read and reverse log
$lines = file_exists($logFile) ? array_reverse(file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skyblue mail visitor Log</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="/assets/mail/css/styles.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
         integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Ubuntu" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
                .table-container {
            width: 100%;
            overflow-x: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        thead {
            background-color: #007BFF;
            color: white;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:hover {
            background-color: #e9f5ff;
        }
        .status {
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>

<?php
$viewFile = __DIR__ . "/../views_.txt";

if (file_exists($viewFile)) {
    $count = (int)file_get_contents($viewFile);
    echo "<h2>Visitor Log ($count)</h2>";
} else {
    echo "0"; // No views yet
}

?>

<div class="table-container">
<table>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Date & Time</th>
            <th>IP Address</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $serial = 1;
        foreach ($lines as $line) {
            $data = parseLogLine($line);
            echo "<tr>
                    <td>{$serial}</td>
                    <td>" . htmlspecialchars($data['datetime']) . "</td>
                    <td>" . htmlspecialchars($data['ip']) . "</td>
                    <td class='status'>" . htmlspecialchars($data['status']) . "</td>
                  </tr>";
            $serial++;
        }
        ?>
    </tbody>
</table>
</div>



</body>
</html>

