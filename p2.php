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

    // 准备构建复杂的查询条件
    $smask = $_SESSION['supmask'] ?? 0;
    $mansel = "ManuID IN (";
    $firstmn = false;
    $params = []; // 动态绑定参数数组

    // 获取制造商信息
    $stmt = $pdo->query("SELECT * FROM Manufacturers");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sid = $row['id'];
        $snm = $row['name'];
        $tvl = 1 << ($sid - 1);
        if ($tvl == ($tvl & $smask)) {
            if ($firstmn) {
                $mansel .= ", ";
            }
            $mansel .= "?";
            $params[] = $sid; // 将ID添加到参数数组中
            $firstmn = true;
        }
    }
    $mansel .= ")";

    if (!empty($params)) { // 确保至少有一个制造商ID符合条件
        // 处理表单提交，构造查询
        if(isset($_POST['natmax']) && $_POST['natmax'] !== "") {
            $compsel = "SELECT catn FROM Compounds WHERE ";
            $conditions = [];
            $queryParams = [];

            // 为每个条件构建查询字符串和参数
            if (!empty($_POST['natmax']) && !empty($_POST['natmin'])) {
                $conditions[] = "(natm >= ? AND natm <= ?)";
                array_push($queryParams, $_POST['natmin'], $_POST['natmax']);
            }
             if (!empty($_POST['nntmax']) && !empty($_POST['nntmin'])) {
        $conditions[] = "(nnit >= ? AND nnit <= ?)";
        array_push($queryParams, $_POST['nntmin'], $_POST['nntmax']);
    }
    if (!empty($_POST['noxmax']) && !empty($_POST['noxmin'])) {
        $conditions[] = "(noxy >= ? AND noxy <= ?)";
        array_push($queryParams, $_POST['noxmin'], $_POST['noxmax']);
    }

            if (!empty($conditions)) {
                $compsel .= implode(" AND ", $conditions);
                $compsel .= " AND " . $mansel; // 添加制造商选择条件
                $queryParams = array_merge($queryParams, $params); // 合并参数数组
                
                $stmt = $pdo->prepare($compsel);
                $stmt->execute($queryParams);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "<pre>";
                foreach ($results as $row) {
                    echo htmlspecialchars($row['catn']), "\n";
                }
                echo "</pre>";
            }
        }
    } else {
        echo "<pre>No manufacturers selected or no matching criteria.</pre>";
    }
} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}

echo <<<_TAIL1
<form action="p2.php" method="post"><pre>
Max Atoms      <input type="text" name="natmax"/>    Min Atoms    <input type="text" name="natmin"/>
Max Carbons    <input type="text" name="ncrmax"/>    Min Carbons  <input type="text" name="ncrmin"/>
Max Nitrogens  <input type="text" name="nntmax"/>    Min Nitrogens<input type="text" name="nntmin"/>
Max Oxygens    <input type="text" name="noxmax"/>    Min Oxygens  <input type="text" name="noxmin"/>
<input type="submit" value="list" />
</pre></form>
</body>
</html>
_TAIL1;
?>

