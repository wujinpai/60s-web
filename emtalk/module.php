<?php
if (!defined('EMLOG_ROOT')) {exit('error!');}

$options = Cache::getInstance()->readCache('options');
$options = is_array($options) ? $options : [];

$options = array_merge([
    'logo_text' => '微话',
    'footer_info' => 'Powered by Emlog Pro',
    'icp' => ''
], $options);

if (!function_exists('getAvatar')) {
    function getAvatar($uid, $size = 48) {
        $cache = Cache::getInstance();
        $user = $cache->getUserInfo($uid);
        
        if (!empty($user['avatar'])) {
            return BLOG_URL . $user['avatar'];
        }
        
        $email = !empty($user['email']) ? $user['email'] : '';
        $gravatarUrl = 'https://cravatar.cn/avatar/';
        
        if ($email) {
            return $gravatarUrl . md5(strtolower(trim($email))) . "?s=$size&d=mm";
        }
        
        return $gravatarUrl . md5($uid) . "?s=$size&d=mm";
    }
}

if (!function_exists('smartDate')) {
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
}

if (!function_exists('subString')) {
    function subString($text, $start, $length) {
        if (mb_strlen($text, 'UTF-8') > $length) {
            return mb_substr($text, $start, $length, 'UTF-8');
        }
        return $text;
    }
}

if (!function_exists('getTwitterLikeCount')) {
    function getTwitterLikeCount($tid) {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) as c FROM " . DB_PREFIX . "twitter_like WHERE tid = $tid";
        $result = $db->query($sql);
        $row = $db->fetch_array($result);
        return $row['c'] ?? 0;
    }
}

if (!function_exists('getTwitterCommentCount')) {
    function getTwitterCommentCount($tid) {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) as c FROM " . DB_PREFIX . "twitter_comment WHERE tid = $tid";
        $result = $db->query($sql);
        $row = $db->fetch_array($result);
        return $row['c'] ?? 0;
    }
}

if (!function_exists('isTwitterLiked')) {
    function isTwitterLiked($tid, $uid) {
        if (!$uid) return false;
        $db = Database::getInstance();
        $sql = "SELECT id FROM " . DB_PREFIX . "twitter_like WHERE tid = $tid AND uid = $uid LIMIT 1";
        $result = $db->query($sql);
        return $db->num_rows($result) > 0;
    }
}

if (!function_exists('getTwitterCount')) {
    function getTwitterCount($uid = null) {
        $db = Database::getInstance();
        if ($uid) {
            $sql = "SELECT COUNT(*) as c FROM " . DB_PREFIX . "twitter WHERE author = $uid AND hide = 'n'";
        } else {
            $sql = "SELECT COUNT(*) as c FROM " . DB_PREFIX . "twitter WHERE hide = 'n'";
        }
        $result = $db->query($sql);
        $row = $db->fetch_array($result);
        return $row['c'] ?? 0;
    }
}

if (!function_exists('getFollowerCount')) {
    function getFollowerCount($uid) {
        $cache = Cache::getInstance();
        $user = $cache->getUserInfo($uid);
        return $user['follower_count'] ?? 0;
    }
}

if (!function_exists('getFollowingCount')) {
    function getFollowingCount($uid) {
        $cache = Cache::getInstance();
        $user = $cache->getUserInfo($uid);
        return $user['following_count'] ?? 0;
    }
}

if (!function_exists('getUserCount')) {
    function getUserCount() {
        $db = Database::getInstance();
        $sql = "SELECT COUNT(*) as c FROM " . DB_PREFIX . "user";
        $result = $db->query($sql);
        $row = $db->fetch_array($result);
        return $row['c'] ?? 0;
    }
}
?>
