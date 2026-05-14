<?php
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $site_description; ?>">
    <title><?php echo $site_title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>assets/css/style.css">
    <?php doAction('head'); ?>
</head>
<body>
    <div id="app" class="light-mode">
        <!-- 顶部导航 -->
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo BLOG_URL; ?>">
                        <?php if (!empty($options['logo'])): ?>
                            <img src="<?php echo $options['logo']; ?>" alt="<?php echo $site_title; ?>">
                        <?php else: ?>
                            <span class="logo-text"><?php echo $options['logo'] ?? '微话'; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <nav class="nav-menu">
                    <a href="<?php echo BLOG_URL; ?>" class="nav-item active">
                        <span class="nav-icon">🏠</span>
                        <span>首页</span>
                    </a>
                    <?php if ($uid): ?>
                    <a href="<?php echo BLOG_URL; ?>user.php" class="nav-item">
                        <span class="nav-icon">👤</span>
                        <span>个人中心</span>
                    </a>
                    <?php endif; ?>
                </nav>

                <div class="header-actions">
                    <button class="theme-toggle" id="themeToggle" title="切换主题">
                        <span class="theme-icon">🌙</span>
                    </button>
                    
                    <?php if ($uid): ?>
                        <div class="user-menu">
                            <img src="<?php echo getAvatar($uid); ?>" alt="用户头像" class="user-avatar" id="userAvatar">
                            <div class="user-dropdown" id="userDropdown">
                                <a href="<?php echo BLOG_URL; ?>user.php" class="dropdown-item">
                                    <span>👤</span> 个人中心
                                </a>
                                <a href="<?php echo BLOG_URL; ?>action/logout" class="dropdown-item">
                                    <span>🚪</span> 退出登录
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-primary" id="loginBtn">
                            <span>登录</span>
                        </button>
                        <button class="btn btn-outline-primary" id="registerBtn">
                            <span>注册</span>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- 移动端菜单按钮 -->
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </header>

        <!-- 移动端菜单 -->
        <div class="mobile-menu" id="mobileMenu">
            <nav class="mobile-nav">
                <a href="<?php echo BLOG_URL; ?>" class="mobile-nav-item active">
                    <span>🏠</span> 首页
                </a>
                <?php if ($uid): ?>
                <a href="<?php echo BLOG_URL; ?>user.php" class="mobile-nav-item">
                    <span>👤</span> 个人中心
                </a>
                <a href="<?php echo BLOG_URL; ?>action/logout" class="mobile-nav-item">
                    <span>🚪</span> 退出登录
                </a>
                <?php else: ?>
                <a href="#" class="mobile-nav-item" id="mobileLoginBtn">
                    <span>🔑</span> 登录
                </a>
                <a href="#" class="mobile-nav-item" id="mobileRegisterBtn">
                    <span>📝</span> 注册
                </a>
                <?php endif; ?>
            </nav>
        </div>

        <!-- 主要内容区域 -->
        <main class="main-content">
