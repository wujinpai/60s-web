<?php
/**
 * Template Name: 微话主题
 * Description: 现代化微语主题，基于Material Design风格
 */
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
require_once __DIR__ . '/header.php';
?>

<!-- 英雄区域 -->
<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title"><?php echo $site_title; ?></h1>
        <p class="hero-description"><?php echo $options['description'] ?? '分享生活，记录点滴'; ?></p>
        
        <?php if (!$uid): ?>
        <div class="hero-actions">
            <button class="btn btn-primary btn-lg" id="heroLoginBtn">
                <span>🔑</span> 立即登录
            </button>
            <button class="btn btn-outline-primary btn-lg" id="heroRegisterBtn">
                <span>📝</span> 免费注册
            </button>
        </div>
        <?php else: ?>
        <button class="btn btn-primary btn-lg" id="heroPublishBtn">
            <span>✏️</span> 发布微语
        </button>
        <?php endif; ?>
    </div>
</section>

<!-- 微语列表 -->
<section class="twitter-section">
    <div class="container">
        <div class="twitter-list" id="twitterList">
            <?php
            global $CACHE;
            $twitter_model = new Twitter_Model();
            $twitters = $twitter_model->getTwitters(1, 20);
            
            if (!empty($twitters)):
                foreach ($twitters as $twitter):
                    $author = $CACHE->getUserInfo($twitter['author']);
                    $avatar = getAvatar($twitter['author']);
                    $content = htmlspecialchars($twitter['content']);
                    $time = smartDate($twitter['date']);
                    $images = !empty($twitter['ico']) ? unserialize($twitter['ico']) : [];
            ?>
            <article class="twitter-card" data-id="<?php echo $twitter['id']; ?>">
                <div class="twitter-header">
                    <img src="<?php echo $avatar; ?>" alt="<?php echo $author['name']; ?>" class="twitter-avatar">
                    <div class="twitter-meta">
                        <a href="<?php echo BLOG_URL; ?>author.php?uid=<?php echo $twitter['author']; ?>" class="twitter-author">
                            <?php echo $author['name']; ?>
                        </a>
                        <span class="twitter-time"><?php echo $time; ?></span>
                    </div>
                    <?php if ($uid == $twitter['author'] || $userData['role'] == 'admin'): ?>
                    <div class="twitter-actions-dropdown">
                        <button class="dropdown-toggle">...</button>
                        <div class="dropdown-menu">
                            <?php if ($uid == $twitter['author']): ?>
                            <a href="javascript:;" class="dropdown-item edit-twitter" data-id="<?php echo $twitter['id']; ?>">编辑</a>
                            <?php endif; ?>
                            <a href="javascript:;" class="dropdown-item delete-twitter" data-id="<?php echo $twitter['id']; ?>">删除</a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="twitter-content">
                    <p><?php echo $content; ?></p>
                </div>
                
                <?php if (!empty($images)): ?>
                <div class="twitter-images">
                    <?php foreach ($images as $index => $img): ?>
                    <?php if ($index < 9): ?>
                    <div class="twitter-image <?php echo count($images) == 1 ? 'single' : ''; ?>">
                        <img src="<?php echo BLOG_URL . $img; ?>" alt="微语图片" loading="lazy">
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div class="twitter-footer">
                    <button class="twitter-action like-btn <?php echo isTwitterLiked($twitter['id'], $uid) ? 'liked' : ''; ?>" data-id="<?php echo $twitter['id']; ?>">
                        <span class="action-icon">❤️</span>
                        <span class="action-count"><?php echo getTwitterLikeCount($twitter['id']); ?></span>
                    </button>
                    <button class="twitter-action comment-btn" data-id="<?php echo $twitter['id']; ?>">
                        <span class="action-icon">💬</span>
                        <span class="action-count"><?php echo getTwitterCommentCount($twitter['id']); ?></span>
                    </button>
                    <button class="twitter-action share-btn" data-id="<?php echo $twitter['id']; ?>">
                        <span class="action-icon">🔗</span>
                    </button>
                </div>
                
                <!-- 评论列表 -->
                <div class="twitter-comments" id="comments-<?php echo $twitter['id']; ?>">
                    <div class="comment-form">
                        <?php if ($uid): ?>
                        <img src="<?php echo getAvatar($uid); ?>" alt="我的头像" class="comment-avatar">
                        <input type="text" class="comment-input" placeholder="写下你的评论..." data-twitter="<?php echo $twitter['id']; ?>">
                        <?php else: ?>
                        <span class="comment-tip">登录后参与评论</span>
                        <?php endif; ?>
                    </div>
                    <div class="comment-list">
                        <?php
                        $comments = $twitter_model->getComments($twitter['id']);
                        if (!empty($comments)):
                            foreach ($comments as $comment):
                                $commentAuthor = $CACHE->getUserInfo($comment['uid']);
                        ?>
                        <div class="comment-item">
                            <img src="<?php echo getAvatar($comment['uid']); ?>" alt="<?php echo $commentAuthor['name']; ?>" class="comment-avatar">
                            <div class="comment-body">
                                <div class="comment-header">
                                    <a href="<?php echo BLOG_URL; ?>author.php?uid=<?php echo $comment['uid']; ?>" class="comment-author">
                                        <?php echo $commentAuthor['name']; ?>
                                    </a>
                                    <span class="comment-time"><?php echo smartDate($comment['date']); ?></span>
                                </div>
                                <p class="comment-content"><?php echo htmlspecialchars($comment['content']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
            
            <?php if (count($twitters) >= 20): ?>
            <div class="load-more">
                <button class="btn btn-outline btn-block" id="loadMoreBtn" data-page="1">
                    加载更多
                </button>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h3>暂无微语</h3>
                <p>还没有人发布微语，<?php echo $uid ? '快来发布第一条吧！' : '登录后成为第一个发布者'; ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- 侧边栏 -->
        <aside class="sidebar">
            <?php if ($uid): ?>
            <!-- 用户信息卡片 -->
            <div class="widget user-widget">
                <div class="user-info-header">
                    <img src="<?php echo getAvatar($uid); ?>" alt="<?php echo $userData['name']; ?>" class="user-avatar-lg">
                    <h3><?php echo $userData['name']; ?></h3>
                    <p><?php echo $userData['email']; ?></p>
                </div>
                <div class="user-stats">
                    <div class="stat-item">
                        <span class="stat-value"><?php echo getTwitterCount($uid); ?></span>
                        <span class="stat-label">微语</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo getFollowingCount($uid); ?></span>
                        <span class="stat-label">关注</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo getFollowerCount($uid); ?></span>
                        <span class="stat-label">粉丝</span>
                    </div>
                </div>
                <a href="<?php echo BLOG_URL; ?>user.php" class="btn btn-primary btn-block">进入个人中心</a>
            </div>
            <?php else: ?>
            <!-- 登录提示卡片 -->
            <div class="widget login-widget">
                <h3>欢迎回来</h3>
                <p>登录后可以发布微语、参与评论互动</p>
                <button class="btn btn-primary btn-block" id="sidebarLoginBtn">登录</button>
                <button class="btn btn-outline btn-block" id="sidebarRegisterBtn">注册账号</button>
            </div>
            <?php endif; ?>
            
            <!-- 热门微语 -->
            <div class="widget">
                <h3 class="widget-title">热门微语</h3>
                <div class="popular-list">
                    <?php
                    $popularTwitters = $twitter_model->getPopularTwitters(5);
                    if (!empty($popularTwitters)):
                        foreach ($popularTwitters as $popular):
                    ?>
                    <a href="#twitter-<?php echo $popular['id']; ?>" class="popular-item">
                        <p class="popular-content"><?php echo subString($popular['content'], 0, 50); ?></p>
                        <span class="popular-likes">❤️ <?php echo getTwitterLikeCount($popular['id']); ?></span>
                    </a>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="empty-tip">暂无热门内容</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- 网站统计 -->
            <div class="widget">
                <h3 class="widget-title">网站统计</h3>
                <div class="site-stats">
                    <div class="site-stat">
                        <span class="stat-label">微语总数</span>
                        <span class="stat-value"><?php echo getTwitterCount(); ?></span>
                    </div>
                    <div class="site-stat">
                        <span class="stat-label">用户总数</span>
                        <span class="stat-value"><?php echo getUserCount(); ?></span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</section>

<script>
window.TwitterConfig = {
    blogUrl: '<?php echo BLOG_URL; ?>',
    token: '<?php echoLoginToken(); ?>'
};
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
