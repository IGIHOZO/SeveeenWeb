<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("main/drive.php");

class MainAction extends DBConnect
{
    public function fetchUserVisits()
    {
        try {
            $pdo = $this->connect();

            // Fetch data from the database in descending order using prepared statements
            $stmt = $pdo->prepare("SELECT * FROM user_visits ORDER BY visit_timestamp DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        } catch (PDOException $e) {
            // Log the error and display a user-friendly message
            error_log("Database Error: " . $e->getMessage());
            return [];
        }
    }
}

$mainAction = new MainAction();
$userVisits = $mainAction->fetchUserVisits();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Visits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow-x: auto; /* Make the table horizontally scrollable on small screens */
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        @media only screen and (max-width: 600px) {
            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>

<h1>User Visits</h1>

<!-- Display fetched user visits in a table -->
<table>
    <thead>
    <tr>
        <th>#</th>
        <?php foreach (array_keys($userVisits[0]) as $column): ?>
            <th><?= $column ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($userVisits as $index => $row): ?>
        <tr>
            <td><?= count($userVisits) - $index."." ?></td>
            <?php foreach ($row as $value): ?>
                <td><?= $value ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
