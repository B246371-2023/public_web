<?php
session_start();
require_once 'login.php';
include 'redir.php';

echo<<<_HEAD1
<html>
<body>
_HEAD1;

include 'menuf.php';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbfs = array("natm","ncar","nnit","noxy","nsul","ncycl","nhdon","nhacc","nrotb","mw","TPSA","XLogP");
    $nms = array("n atoms","n carbons","n nitrogens","n oxygens","n sulphurs","n cycles","n H donors","n H acceptors","n rot bonds","mol wt","TPSA","XLogP");

    echo <<<_MAIN1
    <pre>
This is the correlation Page  
    </pre>
_MAIN1;

    if(isset($_POST['tgval']) && isset($_POST['tgvalb'])) {
        $chosen = array_search($_POST['tgval'], $dbfs);
        $chosenb = array_search($_POST['tgvalb'], $dbfs);

        $smask = $_SESSION['supmask'] ?? 0;
        $mansel = "ManuID IN (";
        $params = [];

        $stmt = $pdo->query("SELECT * FROM Manufacturers");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sid = $row['id'];
            $snm = $row['name'];
            if ((1 << ($sid - 1)) & $smask) {
                $params[] = $sid;
            }
        }
        if (count($params) > 0) {
            $mansel .= implode(', ', array_fill(0, count($params), '?')) . ")";
        } else {
            $mansel = "1=1"; // Default to true if no manufacturers match
        }

        printf(" Correlation for %s (%s) vs %s (%s) \n", $dbfs[$chosen], $nms[$chosen], $dbfs[$chosenb], $nms[$chosenb]);

        // Assuming $comtodo needs to be executed with system, and it's safe to do so
        $comtodo = "./correlate3.py " . escapeshellarg($dbfs[$chosen]) . " " . escapeshellarg($dbfs[$chosenb]) . " " . escapeshellarg($mansel);
        $rescor = system($comtodo);
    }

    echo '<form action="interim_p4.php" method="post"><pre>';
    foreach($dbfs as $index => $dbf) {
        printf('%15s <input type="radio" name="tgval" value="%s"/> %15s <input type="radio" name="tgvalb" value="%s"/>',
            htmlspecialchars($nms[$index]), htmlspecialchars($dbf), htmlspecialchars($nms[$index]), htmlspecialchars($dbf));
        echo "\n";
    }
    echo '<input type="submit" value="OK" />';
    echo '</pre></form>';

} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}

echo <<<_TAIL1
</body>
</html>
_TAIL1;
?>

