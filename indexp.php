<?php
session_start();

// 检查是否接收到了名字和姓氏的POST请求
if(isset($_POST['fn']) && isset($_POST['sn'])) {
    // 保存接收到的名字和姓氏到会话中
    $_SESSION['forname'] = $_POST['fn'];
    $_SESSION['surname'] = $_POST['sn'];
    
    // 假设$_SESSION['supmask']已经在之前被设置
    $smask = $_SESSION['supmask'] ?? '未设置'; // 使用空值合并运算符以防止未定义变量警告

    // 在任何HTML内容输出之前设置这些变量，确保能够正确管理会话和重定向
    $forname = htmlspecialchars($_SESSION['forname']);
    $surname = htmlspecialchars($_SESSION['surname']);
    
    echo<<<_HEAD1
    <html>
    <body>
_HEAD1;
    include 'menuf.php';
    // 显示mask值
    echo <<<_TAIL1
<pre>
   name: $forname $surname
   Mask Value: $smask
</pre>
</body>
</html>
_TAIL1;

} else { 
    // 如果$USER未定义或为空，重定向到登录页或其他默认页面
    $userRedirect = isset($_SESSION['user']) ? $_SESSION['user'] : 's2530615';
    header("Location: https://bioinfmsc8.bio.ed.ac.uk/~$userRedirect/complib.php");
    exit; // 重定向后终止脚本执行
}
?>

