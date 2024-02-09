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
        /* Your CSS Styles Here */
    </style>
</head>
<body>

<h1>User Visits</h1>

<!-- Display fetched user visits in a table -->
<table>
    <thead>
    <tr>
        <?php foreach (array_keys($userVisits[0]) as $column): ?>
            <th><?= $column ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($userVisits as $row): ?>
        <tr>
            <?php foreach ($row as $value): ?>
                <td><?= $value ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
