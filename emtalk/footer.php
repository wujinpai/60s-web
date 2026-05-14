  </main>

  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <p>&copy; <?php echo date('Y'); ?> <?php echo $blogname; ?>. All rights reserved.</p>
        <?php if(!empty($options['icp'])): ?>
          <p class="icp-link">
            <a href="https://beian.miit.gov.cn/" target="_blank"><?php echo $options['icp']; ?></a>
          </p>
        <?php endif; ?>
        <?php if(!empty($options['footer_info'])): ?>
          <p class="footer-info"><?php echo $options['footer_info']; ?></p>
        <?php endif; ?>
      </div>
    </div>
  </footer>

  <?php if($uid): ?>
    <button class="fab" id="publishFab" title="发布微语">
      <span>+</span>
    </button>
  <?php endif; ?>

  <div class="modal" id="loginModal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h3>登录</h3>
        <button class="modal-close" id="loginClose">&times;</button>
      </div>
      <div class="modal-body">
        <form id="loginForm" action="<?php echo BLOG_URL; ?>?action=login" method="post">
          <div class="form-group">
            <label for="loginUser">用户名</label>
            <input type="text" id="loginUser" name="user" required placeholder="请输入用户名">
          </div>
          <div class="form-group">
            <label for="loginPass">密码</label>
            <input type="password" id="loginPass" name="pw" required placeholder="请输入密码">
          </div>
          <div class="form-group checkbox-group">
            <label>
              <input type="checkbox" name="ispersist" value="y"> 记住我
            </label>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">登录</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal" id="registerModal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h3>注册</h3>
        <button class="modal-close" id="registerClose">&times;</button>
      </div>
      <div class="modal-body">
        <form id="registerForm" action="<?php echo BLOG_URL; ?>?action=register" method="post">
          <div class="form-group">
            <label for="regUser">用户名</label>
            <input type="text" id="regUser" name="user" required pattern="[a-zA-Z0-9_]{4,20}" placeholder="4-20位字母、数字或下划线">
          </div>
          <div class="form-group">
            <label for="regEmail">邮箱</label>
            <input type="email" id="regEmail" name="email" required placeholder="请输入邮箱地址">
          </div>
          <div class="form-group">
            <label for="regPass">密码</label>
            <input type="password" id="regPass" name="pw" required minlength="6" placeholder="至少6位字符">
          </div>
          <div class="form-group">
            <label for="regPass2">确认密码</label>
            <input type="password" id="regPass2" name="pw2" required placeholder="再次输入密码">
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">注册</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php if($uid): ?>
    <div class="modal" id="publishModal">
      <div class="modal-overlay"></div>
      <div class="modal-content modal-lg">
        <div class="modal-header">
          <h3>发布微语</h3>
          <button class="modal-close" id="publishClose">&times;</button>
        </div>
        <div class="modal-body">
          <form id="publishForm" action="<?php echo BLOG_URL; ?>admin/twitter.php?action=pub" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <textarea id="twitterContent" name="content" placeholder="分享你的想法..." required maxlength="500"></textarea>
              <div class="char-count"><span id="charCount">0</span> / 500</div>
            </div>
            <div class="form-group">
              <label>添加图片</label>
              <div class="upload-area" id="uploadArea">
                <input type="file" id="twitterImage" name="image" accept="image/*" hidden>
                <button type="button" class="btn btn-outline" id="uploadBtn">
                  <span>📷</span> 上传图片
                </button>
                <div class="image-preview" id="imagePreview" style="display: none;">
                  <img src="" alt="预览">
                  <button type="button" class="remove-image" id="removeImage">&times;</button>
                </div>
              </div>
            </div>
            <div class="form-actions">
              <button type="button" class="btn btn-outline" id="cancelPublish">取消</button>
              <button type="submit" class="btn btn-primary">发布</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <div class="toast" id="toast">
    <span class="toast-message"></span>
  </div>
</div>

<script src="<?php echo TEMPLATE_URL; ?>assets/js/main.js?v=1.1"></script>
<?php doAction('index_footer'); ?>
</body>
</html>
