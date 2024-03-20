<?php
session_start(); // 确保在脚本最开始调用

// 检查$USER变量是否已经在会话中设置，如果没有，可以设为默认值或重定向
if(isset($_SESSION['user'])) {
    $USER = $_SESSION['user']; // 假设会话变量中存储了用户名
} else {
    $USER = '~s2530615';// 这里设置一个默认值或根据你的需要进行处理
}

echo <<<_MENU1
   Your options are <br>
    <table width ="70%" border="0" cellspacing="0" align="center"> <tr>
   <td bgcolor="#DCEFFE"><div align="center">
    <a href="https://bioinfmsc8.bio.ed.ac.uk/{$USER}/p1.php"> Select Suppliers </a>
    </div></td>
   <td bgcolor="#DCEFFE"><div align="center">
    <a href="https://bioinfmsc8.bio.ed.ac.uk/{$USER}/p2.php"> Search Compounds </a>
    </div></td>
   <td bgcolor="#DCEFFE"><div align="center">
    <a href="https://bioinfmsc8.bio.ed.ac.uk/{$USER}/p3.php"> Stats </a>
    </div></td>
   <td bgcolor="#DCEFFE"><div align="center">
    <a href="https://bioinfmsc8.bio.ed.ac.uk/{$USER}/p4.php"> Correlations </a>
    </div></td>
   <td bgcolor="#DCEFFE"><div align="center">
    <a href="https://bioinfmsc8.bio.ed.ac.uk/{$USER}/p5.php"> Exit </a>
    </div></td>
    <td bgcolor="#DCEFFE"><div align="center">
    <a href="https://bioinfmsc8.bio.ed.ac.uk/{$USER}/phelp.php"> About this website </a>
    </div></td>
    </tr></table>
_MENU1;
?>

