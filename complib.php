<?php
session_start();
require_once 'login.php';

// 使用PDO连接数据库
try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 执行查询
    $stmt = $pdo->query("SELECT * FROM Manufacturers");
    $manufacturers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 计算supmask
    $mask = 0;
    foreach ($manufacturers as $manufacturer) {
        $mask = (2 * $mask) + 1;
    }
    $_SESSION['supmask'] = $mask;

} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}

echo<<<_HEAD1
<html>
<body>
_HEAD1;

echo <<<_EOP
<script>
   function validate(form) {
       var fail = ""
       if(form.fn.value =="") fail = "Must Give First Name "
       if(form.sn.value == "") fail += "Must Give Surname"
       if(fail == "") return true
       else {alert(fail); return false}
   }
</script>
<form action="indexp.php" method="post" onSubmit="return validate(this)">
  <pre>
       First Name <input type="text" name="fn"/>
       Second Name <input type="text" name="sn"/>
                   <input type="submit" value="go" />
  </pre>
</form>
_EOP;

echo <<<_TAIL1
</body>
</html>
_TAIL1;
?>

