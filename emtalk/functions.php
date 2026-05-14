<?php
/**
 * 微话主题函数库
 */

// 注册主题设置
function theme_config($shound = false) {
    $config = [
        'logo' => [
            'type' => 'text',
            'name' => '网站标题',
            'value' => '微话'
        ],
        'description' => [
            'type' => 'text',
            'name' => '网站描述',
            'value' => '一个现代化的微语分享平台'
        ],
        'icp' => [
            'type' => 'text',
            'name' => 'ICP备案号',
            'value' => ''
        ],
        'statistics' => [
            'type' => 'textarea',
            'name' => '统计代码',
            'value' => ''
        ]
    ];
    
    if ($shound) {
        return $config;
    }
    
    return Option::getWidgetKeyConfig('emtalk', $config);
}

// 获取用户头像
function getAvatar($uid, $size = 80) {
    $cache = Cache::getInstance();
    $user = $cache->getUserInfo($uid);
    $avatar = $user['avatar'] ?? '';
    
    if (empty($avatar)) {
        return 'https://cravatar.cn/avatar/' . md5($user['email']) . '?s=' . $size . '&d=mm';
    }
    
    return BLOG_URL . $avatar;
}

// 获取微语点赞数
function getTwitterLikeCount($tid) {
    $db = Database::getInstance();
    $row = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "twitter_like WHERE tid = '$tid'")->fetch(PDO::FETCH_ASSOC);
    return $row['count'] ?? 0;
}

// 获取微语评论数
function getTwitterCommentCount($tid) {
    $db = Database::getInstance();
    $row = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "twitter_comment WHERE tid = '$tid'")->fetch(PDO::FETCH_ASSOC);
    return $row['count'] ?? 0;
}

// 检查用户是否点赞
function isTwitterLiked($tid, $uid) {
    if (!$uid) return false;
    $db = Database::getInstance();
    $row = $db->query("SELECT id FROM " . DB_PREFIX . "twitter_like WHERE tid = '$tid' AND uid = '$uid'")->fetch(PDO::FETCH_ASSOC);
    return !empty($row);
}

// 获取用户微语数
function getTwitterCount($uid = null) {
    $db = Database::getInstance();
    if ($uid) {
        $row = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "twitter WHERE author = '$uid' AND hide = 'n'")->fetch(PDO::FETCH_ASSOC);
    } else {
        $row = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "twitter WHERE hide = 'n'")->fetch(PDO::FETCH_ASSOC);
    }
    return $row['count'] ?? 0;
}

// 获取关注数
function getFollowingCount($uid) {
    $cache = Cache::getInstance();
    $user = $cache->getUserInfo($uid);
    return $user['following_count'] ?? 0;
}

// 获取粉丝数
function getFollowerCount($uid) {
    $cache = Cache::getInstance();
    $user = $cache->getUserInfo($uid);
    return $user['follower_count'] ?? 0;
}

// 获取用户总数
function getUserCount() {
    $db = Database::getInstance();
    $row = $db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "user")->fetch(PDO::FETCH_ASSOC);
    return $row['count'] ?? 0;
}

// 智能时间格式化
function smartDate($timestamp) {
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) {
        return '刚刚';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . '分钟前';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . '小时前';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . '天前';
    } else {
        return date('Y-m-d', $timestamp);
    }
}

// 截取字符串
function subString($content, $start = 0, $length = 100, $suffix = '...') {
    $len = mb_strlen($content, 'UTF-8');
    if ($len <= $length) {
        return $content;
    }
    return mb_substr($content, $start, $length, 'UTF-8') . $suffix;
}

// 获取登录Token
function echoLoginToken() {
    return isset($_COOKIE['EMLOG_TOKEN']) ? $_COOKIE['EMLOG_TOKEN'] : '';
}
