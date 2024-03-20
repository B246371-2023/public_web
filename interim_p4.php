<?php
session_start();
include 'redir.php';
require_once 'login.php';

echo<<<_HEAD1
<html>
<body>
_HEAD1;

include 'menuf.php';
$dbfs = array("natm","ncar","nnit","noxy","nsul","ncycl","nhdon","nhacc","nrotb","mw","TPSA","XLogP");
$nms = array("n atoms","n carbons","n nitrogens","n oxygens","n sulphurs","n cycles","n H donors","n H acceptors","n rot bonds","mol wt","TPSA","XLogP");

echo <<<_MAIN1
    <pre>
This is the Statistics Page  (not Complete)
    </pre>
_MAIN1;

if(isset($_POST['tgval'])) {
    $chosen = 0;
    $tgval = $_POST['tgval'];
    for($j = 0; $j < count($dbfs); ++$j) {
        if(strcmp($dbfs[$j], $tgval) == 0) $chosen = $j; 
    }
    echo "Statistics for " . htmlspecialchars($dbfs[$chosen]) . " (" . htmlspecialchars($nms[$chosen]) . ")<br />\n";

    try {
        $pdo = new PDO("mysql:host=$db_hostname;dbname=$db_database;charset=utf8", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Using prepared statement to prevent SQL injection
        $stmt = $pdo->prepare("SELECT AVG($dbfs[$chosen]), STD($dbfs[$chosen]) FROM Compounds");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_NUM);

        if ($row) {
            printf(" Average: %f  Standard Dev: %f <br />\n", $row[0], $row[1]);
        }
    } catch (PDOException $e) {
        die("Unable to connect to database: " . $e->getMessage());
    }
}

echo '<form action="p3.php" method="post"><pre>';
for($j = 0; $j < count($dbfs); ++$j) {
    echo ' ' . str_pad(htmlspecialchars($nms[$j]), 15);
    echo '<input type="radio" name="tgval" value="' . htmlspecialchars($dbfs[$j]) . '"' . ($j == 0 ? ' checked' : '') . '/>';
    echo "\n";
} 
echo '<input type="submit" value="OK" />';
echo '</pre></form>';

echo <<<_TAIL1
</body>
</html>
_TAIL1;
?>

