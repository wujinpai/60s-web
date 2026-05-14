        </main>

        <!-- 底部 -->
        <footer class="footer">
            <div class="footer-content">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $site_title; ?>. All rights reserved.</p>
                <?php if (!empty($options['ICP'])): ?>
                <p><a href="https://beian.miit.gov.cn/" target="_blank"><?php echo $options['ICP']; ?></a></p>
                <?php endif; ?>
            </div>
        </footer>

        <!-- 登录弹窗 -->
        <div class="modal" id="loginModal">
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h3>登录</h3>
                    <button class="modal-close" id="loginClose">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="form-group">
                            <label for="loginUsername">用户名</label>
                            <input type="text" id="loginUsername" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="loginPassword">密码</label>
                            <input type="password" id="loginPassword" name="password" required>
                        </div>
                        <div class="form-group checkbox-group">
                            <label>
                                <input type="checkbox" name="ispersist" value="1"> 记住我
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">登录</button>
                        </div>
                        <div class="form-footer">
                            <a href="#" id="forgotPassword">忘记密码？</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 注册弹窗 -->
        <div class="modal" id="registerModal">
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h3>注册</h3>
                    <button class="modal-close" id="registerClose">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="registerForm">
                        <div class="form-group">
                            <label for="registerUsername">用户名</label>
                            <input type="text" id="registerUsername" name="username" required pattern="[a-zA-Z0-9_]{4,20}">
                            <span class="form-hint">4-20位字母、数字或下划线</span>
                        </div>
                        <div class="form-group">
                            <label for="registerEmail">邮箱</label>
                            <input type="email" id="registerEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="registerPassword">密码</label>
                            <input type="password" id="registerPassword" name="password" required minlength="6">
                            <span class="form-hint">至少6位字符</span>
                        </div>
                        <div class="form-group">
                            <label for="registerPassword2">确认密码</label>
                            <input type="password" id="registerPassword2" name="password2" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">注册</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 发布微语弹窗 -->
        <?php if ($uid): ?>
        <div class="modal" id="publishModal">
            <div class="modal-overlay"></div>
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h3>发布微语</h3>
                    <button class="modal-close" id="publishClose">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="publishForm">
                        <div class="form-group">
                            <textarea id="twitterContent" name="content" placeholder="分享你的想法..." required></textarea>
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

        <!-- 提示消息 -->
        <div class="toast" id="toast">
            <span class="toast-message"></span>
        </div>

        <!-- 发布按钮 -->
        <?php if ($uid): ?>
        <button class="fab" id="publishFab" title="发布微语">
            <span>+</span>
        </button>
        <?php endif; ?>
    </div>

    <script src="<?php echo TEMPLATE_URL; ?>assets/js/main.js"></script>
    <?php doAction('footer'); ?>
    <?php if (!empty($options['statistics'])): ?>
    <?php echo $options['statistics']; ?>
    <?php endif; ?>
</body>
</html>
