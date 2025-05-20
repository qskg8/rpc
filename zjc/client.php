<?php
// 模拟存储在线用户的数据库，使用文件存储
$file = 'online_users.json';

// 调试信息
error_log("访问时间: " . date('Y-m-d H:i:s'));

// 检查文件是否存在，不存在则创建
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

// 读取文件内容
$users = json_decode(file_get_contents($file), true);
$current_time = time();
$user_ip = $_SERVER['REMOTE_ADDR'];  // 获取用户IP地址

// 调试信息
error_log("访问IP: " . $user_ip);

// 更新当前用户的时间戳
$users[$user_ip] = $current_time;

// 移除过期用户
foreach ($users as $ip => $timestamp) {
    if ($current_time - $timestamp > 300) { // 假设 session 生命周期为 5 分钟
        unset($users[$ip]);
    }
}

// 保存更新后的用户列表
file_put_contents($file, json_encode($users));

// 获取在线用户数量和IP地址列表
$online_count = count($users);
$online_ips = array_keys($users);

// 返回在线用户数量和IP地址列表
echo json_encode(['count' => $online_count, 'ips' => $online_ips]);
?>
