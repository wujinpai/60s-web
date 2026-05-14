<?php if (!defined('EMLOG_ROOT')) {exit('error!');} ?>

  <div class="container">
    <div class="content-wrapper">
      <div class="main-column">
        
        <div class="hero-section">
          <h1 class="hero-title"><?php echo $blogname; ?></h1>
          <p class="hero-desc"><?php echo $bloginfo; ?></p>
          <?php if($uid): ?>
            <button class="btn btn-primary" id="openPublishBtn">
              <span>✏️</span> 发布微语
            </button>
          <?php else: ?>
            <div class="hero-buttons">
              <button class="btn btn-primary" id="heroLoginBtn">
                <span>🔐</span> 登录
              </button>
              <button class="btn btn-outline-primary" id="heroRegisterBtn">
                <span>📝</span> 注册
              </button>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="twitter-list" id="twitterList">
          <?php 
          $twitterModel = new Twitter_Model();
          $twitters = $twitterModel->getTwitters(1, 20);
          
          if(!empty($twitters)): 
            foreach($twitters as $twitter): 
              $authorInfo = $CACHE->getUserInfo($twitter['author']);
              $avatarUrl = getAvatar($twitter['author']);
              $content = nl2br(htmlspecialchars($twitter['content']));
              $postTime = smartDate($twitter['date']);
              $images = !empty($twitter['ico']) ? unserialize($twitter['ico']) : [];
              $likeCount = getTwitterLikeCount($twitter['id']);
              $commentCount = getTwitterCommentCount($twitter['id']);
              $isLiked = isTwitterLiked($twitter['id'], $uid);
          ?>
            <article class="twitter-card" data-id="<?php echo $twitter['id']; ?>">
              <div class="twitter-header">
                <img src="<?php echo $avatarUrl; ?>" alt="<?php echo $authorInfo['name']; ?>" class="twitter-avatar">
                <div class="twitter-meta">
                  <span class="twitter-author"><?php echo $authorInfo['name']; ?></span>
                  <span class="twitter-time"><?php echo $postTime; ?></span>
                </div>
                <?php if($uid == $twitter['author'] || $userData['role'] == 'admin'): ?>
                  <div class="twitter-actions-menu">
                    <button class="btn-icon action-menu-btn">⋮</button>
                    <div class="action-dropdown">
                      <a href="javascript:void(0)" class="dropdown-item edit-twitter" data-id="<?php echo $twitter['id']; ?>">编辑</a>
                      <a href="javascript:void(0)" class="dropdown-item delete-twitter" data-id="<?php echo $twitter['id']; ?>">删除</a>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
              
              <div class="twitter-content">
                <?php echo $content; ?>
              </div>
              
              <?php if(!empty($images)): ?>
                <div class="twitter-images <?php echo count($images) == 1 ? 'single-image' : ''; ?>">
                  <?php foreach($images as $img): ?>
                    <div class="twitter-image-item">
                      <img src="<?php echo BLOG_URL . $img; ?>" alt="图片" loading="lazy">
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              
              <div class="twitter-actions">
                <button class="twitter-action like-btn <?php echo $isLiked ? 'liked' : ''; ?>" data-id="<?php echo $twitter['id']; ?>">
                  <span class="action-icon">❤️</span>
                  <span class="action-count"><?php echo $likeCount; ?></span>
                </button>
                <button class="twitter-action comment-btn" data-id="<?php echo $twitter['id']; ?>">
                  <span class="action-icon">💬</span>
                  <span class="action-count"><?php echo $commentCount; ?></span>
                </button>
                <button class="twitter-action share-btn" data-id="<?php echo $twitter['id']; ?>">
                  <span class="action-icon">🔗</span>
                </button>
              </div>
              
              <div class="twitter-comments" id="comments-<?php echo $twitter['id']; ?>">
                <div class="comment-input-wrapper">
                  <?php if($uid): ?>
                    <img src="<?php echo getAvatar($uid); ?>" alt="我的头像" class="comment-avatar">
                    <input type="text" class="comment-input" placeholder="写下你的评论..." data-tid="<?php echo $twitter['id']; ?>">
                  <?php else: ?>
                    <p class="comment-tip">登录后可评论</p>
                  <?php endif; ?>
                </div>
                <div class="comments-list">
                  <?php 
                  $comments = $twitterModel->getComments($twitter['id']);
                  if(!empty($comments)): 
                    foreach($comments as $comment): 
                      $commenter = $CACHE->getUserInfo($comment['uid']);
                  ?>
                    <div class="comment-item">
                      <img src="<?php echo getAvatar($comment['uid']); ?>" alt="<?php echo $commenter['name']; ?>" class="comment-avatar">
                      <div class="comment-content-wrapper">
                        <div class="comment-header">
                          <span class="comment-author"><?php echo $commenter['name']; ?></span>
                          <span class="comment-time"><?php echo smartDate($comment['date']); ?></span>
                        </div>
                        <p class="comment-text"><?php echo htmlspecialchars($comment['content']); ?></p>
                      </div>
                    </div>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
          
          <?php if(count($twitters) >= 20): ?>
            <div class="load-more-wrapper">
              <button class="btn btn-outline" id="loadMoreBtn" data-page="1">
                加载更多
              </button>
            </div>
          <?php endif; ?>
          
          <?php else: ?>
            <div class="empty-state">
              <div class="empty-icon">📝</div>
              <h3>还没有微语</h3>
              <p>来发布第一条微语吧！</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
      
      <aside class="sidebar">
        <?php if($uid): ?>
          <div class="widget user-widget">
            <div class="user-profile-header">
              <img src="<?php echo getAvatar($uid); ?>" alt="<?php echo $userData['name']; ?>" class="user-avatar-lg">
              <h3><?php echo $userData['name']; ?></h3>
              <p class="user-email"><?php echo $userData['email']; ?></p>
            </div>
            <div class="user-stats">
              <div class="stat-item">
                <span class="stat-value"><?php echo getTwitterCount($uid); ?></span>
                <span class="stat-label">微语</span>
              </div>
              <div class="stat-item">
                <span class="stat-value"><?php echo getFollowerCount($uid); ?></span>
                <span class="stat-label">粉丝</span>
              </div>
              <div class="stat-item">
                <span class="stat-value"><?php echo getFollowingCount($uid); ?></span>
                <span class="stat-label">关注</span>
              </div>
            </div>
            <a href="<?php echo BLOG_URL; ?>user.php" class="btn btn-primary btn-block">进入个人中心</a>
          </div>
        <?php else: ?>
          <div class="widget login-widget">
            <h3 class="widget-title">欢迎回来</h3>
            <p class="widget-desc">登录后可发布微语和参与互动</p>
            <button class="btn btn-primary btn-block" id="sidebarLoginBtn">登录</button>
            <button class="btn btn-outline btn-block" id="sidebarRegisterBtn">注册账号</button>
          </div>
        <?php endif; ?>
        
        <div class="widget">
          <h3 class="widget-title">热门微语</h3>
          <div class="hot-twitters">
            <?php 
            $hotTwitters = $twitterModel->getPopularTwitters(5);
            if(!empty($hotTwitters)): 
              foreach($hotTwitters as $hot): 
                $hotAuthor = $CACHE->getUserInfo($hot['author']);
            ?>
              <a href="#twitter-<?php echo $hot['id']; ?>" class="hot-twitter-item">
                <p class="hot-content"><?php echo subString($hot['content'], 0, 40); ?>...</p>
                <span class="hot-likes">❤️ <?php echo getTwitterLikeCount($hot['id']); ?></span>
              </a>
            <?php endforeach; ?>
            <?php else: ?>
              <p class="empty-hint">暂无热门内容</p>
            <?php endif; ?>
          </div>
        </div>
        
        <div class="widget">
          <h3 class="widget-title">站点统计</h3>
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
  </div>

<?php require_once View::getView('footer'); ?>
