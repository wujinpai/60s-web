<?php
/**
 * Template Name: 用户中心 - 微话
 * Description: 用户中心页面
 */
if (!defined('EMLOG_ROOT')) {
    exit('error!');
}
require_once __DIR__ . '/header.php';

// 获取当前用户信息
$profile = $CACHE->getUserInfo($uid);
$user_twitters = $twitter_model->getTwittersByUid($uid, 1, 50);
$total_twitters = $twitter_model->getTwitterCountByUid($uid);
?>

<section class="user-center">
    <div class="container">
        <!-- 用户信息卡片 -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="<?php echo getAvatar($uid); ?>" alt="<?php echo $profile['name']; ?>">
                    <?php if ($userData['role'] == 'admin'): ?>
                    <span class="verified-badge" title="管理员">✓</span>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h1><?php echo $profile['name']; ?></h1>
                    <p class="profile-bio"><?php echo $profile['description'] ?? '这个人很懒，什么都没写'; ?></p>
                    <div class="profile-meta">
                        <span><strong><?php echo $total_twitters; ?></strong> 微语</span>
                        <span><strong><?php echo $profile['follower_count'] ?? 0; ?></strong> 粉丝</span>
                        <span><strong><?php echo $profile['following_count'] ?? 0; ?></strong> 关注</span>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="btn btn-outline" id="editProfileBtn">
                        <span>✏️</span> 编辑资料
                    </button>
                </div>
            </div>
        </div>

        <div class="user-content">
            <!-- 左侧导航 -->
            <nav class="user-nav">
                <a href="#my-twitters" class="nav-item active">
                    <span>📝</span> 我的微语
                </a>
                <a href="#my-comments" class="nav-item">
                    <span>💬</span> 我的评论
                </a>
                <a href="#my-likes" class="nav-item">
                    <span>❤️</span> 我的点赞
                </a>
                <a href="#settings" class="nav-item">
                    <span>⚙️</span> 账户设置
                </a>
            </nav>

            <!-- 右侧内容 -->
            <div class="user-main">
                <!-- 我的微语 -->
                <section id="my-twitters" class="content-section active">
                    <div class="section-header">
                        <h2>我的微语</h2>
                        <button class="btn btn-primary" id="quickPublishBtn">
                            <span>✏️</span> 发布微语
                        </button>
                    </div>
                    
                    <div class="twitter-list">
                        <?php if (!empty($user_twitters)): ?>
                        <?php foreach ($user_twitters as $twitter): ?>
                        <article class="twitter-card" data-id="<?php echo $twitter['id']; ?>">
                            <div class="twitter-header">
                                <img src="<?php echo getAvatar($uid); ?>" alt="我的头像" class="twitter-avatar">
                                <div class="twitter-meta">
                                    <span class="twitter-author"><?php echo $profile['name']; ?></span>
                                    <span class="twitter-time"><?php echo smartDate($twitter['date']); ?></span>
                                </div>
                                <div class="twitter-actions-dropdown">
                                    <button class="dropdown-toggle">...</button>
                                    <div class="dropdown-menu">
                                        <a href="javascript:;" class="dropdown-item edit-twitter" data-id="<?php echo $twitter['id']; ?>">编辑</a>
                                        <a href="javascript:;" class="dropdown-item delete-twitter" data-id="<?php echo $twitter['id']; ?>">删除</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="twitter-content">
                                <p><?php echo htmlspecialchars($twitter['content']); ?></p>
                            </div>
                            
                            <?php 
                            $images = !empty($twitter['ico']) ? unserialize($twitter['ico']) : [];
                            if (!empty($images)): 
                            ?>
                            <div class="twitter-images">
                                <?php foreach ($images as $index => $img): ?>
                                <div class="twitter-image <?php echo count($images) == 1 ? 'single' : ''; ?>">
                                    <img src="<?php echo BLOG_URL . $img; ?>" alt="微语图片">
                                </div>
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
                            </div>
                        </article>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">📝</div>
                            <h3>还没有发布微语</h3>
                            <p>点击上方按钮发布你的第一条微语吧！</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 我的评论 -->
                <section id="my-comments" class="content-section">
                    <div class="section-header">
                        <h2>我的评论</h2>
                    </div>
                    <div class="comment-list">
                        <?php
                        $my_comments = $twitter_model->getCommentsByUid($uid);
                        if (!empty($my_comments)):
                            foreach ($my_comments as $comment):
                                $twitter_data = $twitter_model->getTwitter($comment['tid']);
                        ?>
                        <div class="comment-item-card">
                            <div class="comment-context">
                                <p>评论了 <a href="#twitter-<?php echo $comment['tid']; ?>">这条微语</a></p>
                            </div>
                            <div class="comment-body">
                                <img src="<?php echo getAvatar($uid); ?>" alt="我的头像" class="comment-avatar">
                                <div class="comment-content">
                                    <p><?php echo htmlspecialchars($comment['content']); ?></p>
                                    <span class="comment-time"><?php echo smartDate($comment['date']); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">💬</div>
                            <h3>还没有评论</h3>
                            <p>去微语列表参与评论吧！</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 我的点赞 -->
                <section id="my-likes" class="content-section">
                    <div class="section-header">
                        <h2>我的点赞</h2>
                    </div>
                    <div class="twitter-list">
                        <?php
                        $liked_twitters = $twitter_model->getLikedTwitters($uid);
                        if (!empty($liked_twitters)):
                            foreach ($liked_twitters as $twitter):
                                $author = $CACHE->getUserInfo($twitter['author']);
                        ?>
                        <article class="twitter-card" data-id="<?php echo $twitter['id']; ?>">
                            <div class="twitter-header">
                                <img src="<?php echo getAvatar($twitter['author']); ?>" alt="<?php echo $author['name']; ?>" class="twitter-avatar">
                                <div class="twitter-meta">
                                    <a href="<?php echo BLOG_URL; ?>author.php?uid=<?php echo $twitter['author']; ?>" class="twitter-author">
                                        <?php echo $author['name']; ?>
                                    </a>
                                    <span class="twitter-time"><?php echo smartDate($twitter['date']); ?></span>
                                </div>
                            </div>
                            <div class="twitter-content">
                                <p><?php echo htmlspecialchars($twitter['content']); ?></p>
                            </div>
                            <div class="twitter-footer">
                                <button class="twitter-action like-btn liked" data-id="<?php echo $twitter['id']; ?>">
                                    <span class="action-icon">❤️</span>
                                    <span class="action-count"><?php echo getTwitterLikeCount($twitter['id']); ?></span>
                                </button>
                            </div>
                        </article>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon">❤️</div>
                            <h3>还没有点赞</h3>
                            <p>去微语列表点赞喜欢的微语吧！</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 账户设置 -->
                <section id="settings" class="content-section">
                    <div class="section-header">
                        <h2>账户设置</h2>
                    </div>
                    
                    <div class="settings-form">
                        <!-- 基本信息 -->
                        <div class="settings-card">
                            <h3>基本信息</h3>
                            <form id="profileForm">
                                <div class="form-group">
                                    <label>用户名</label>
                                    <input type="text" value="<?php echo $profile['name']; ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label>邮箱</label>
                                    <input type="email" name="email" value="<?php echo $profile['email']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>个人简介</label>
                                    <textarea name="description" rows="3" placeholder="介绍一下自己..."><?php echo $profile['description'] ?? ''; ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">保存修改</button>
                            </form>
                        </div>
                        
                        <!-- 修改密码 -->
                        <div class="settings-card">
                            <h3>修改密码</h3>
                            <form id="passwordForm">
                                <div class="form-group">
                                    <label>当前密码</label>
                                    <input type="password" name="old_password" required>
                                </div>
                                <div class="form-group">
                                    <label>新密码</label>
                                    <input type="password" name="new_password" required minlength="6">
                                </div>
                                <div class="form-group">
                                    <label>确认新密码</label>
                                    <input type="password" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">修改密码</button>
                            </form>
                        </div>
                        
                        <!-- 头像设置 -->
                        <div class="settings-card">
                            <h3>头像设置</h3>
                            <div class="avatar-upload">
                                <img src="<?php echo getAvatar($uid); ?>" alt="当前头像" class="current-avatar">
                                <div class="avatar-actions">
                                    <button class="btn btn-outline" id="uploadAvatarBtn">
                                        <span>📷</span> 上传新头像
                                    </button>
                                    <input type="file" id="avatarInput" accept="image/*" hidden>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

<script>
window.UserConfig = {
    blogUrl: '<?php echo BLOG_URL; ?>',
    uid: <?php echo $uid; ?>,
    token: '<?php echoLoginToken(); ?>'
};
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
