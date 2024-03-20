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
    // 使用PDO连接到数据库
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 执行查询
    $stmt = $pdo->query("SELECT * FROM Manufacturers");
    $manufacturers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $rows = count($manufacturers);

    $smask = $_SESSION['supmask'] ?? 0;
    $sid = [];
    $snm = [];
    $sact = [];
    
    foreach ($manufacturers as $index => $row) {
        $sid[$index] = $row['id']; // Assuming 'id' is the column name in your Manufacturers table
        $snm[$index] = $row['name']; // Assuming 'name' is the column name for manufacturer's name
        $sact[$index] = 0;
        $tvl = 1 << ($sid[$index] - 1);
        if ($tvl == ($tvl & $smask)) {
            $sact[$index] = 1;
        }
    }
    
    if(isset($_POST['supplier'])) {
        $supplier = $_POST['supplier'];
        $nele = count($supplier);
        foreach ($snm as $k => $name) {
            $sact[$k] = 0;
            foreach ($supplier as $selected) {
                if ($selected == $name) $sact[$k] = 1;
            }
        }

        $smask = 0;
        foreach ($sid as $j => $id) {
            if ($sact[$j] == 1) {
                $smask |= (1 << ($id - 1));
            }
        }
        $_SESSION['supmask'] = $smask;
    }
    
    echo 'Currently selected Suppliers: ';
    foreach ($sact as $j => $active) {
        if ($active == 1) {
            echo $snm[$j] . " ";
        }
    }
    
    echo '<br><pre> <form action="p1.php" method="post">';
    foreach ($snm as $j => $name) {
        echo $name;
        echo ' <input type="checkbox" name="supplier[]" value="';
        echo $name;
        echo '"/>';
        echo "\n";
    }

} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}

echo <<<_TAIL1
 <input type="submit" value="OK" />
</pre></form>
</body>
</html>
_TAIL1;
?>
