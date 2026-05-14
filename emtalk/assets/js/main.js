/**
 * 微话主题 - JavaScript交互文件
 */

(function() {
    'use strict';

    // 配置
    const Config = window.TwitterConfig || window.UserConfig || {
        blogUrl: '/',
        token: ''
    };

    // DOM元素
    const elements = {
        themeToggle: document.getElementById('themeToggle'),
        mobileMenuBtn: document.getElementById('mobileMenuBtn'),
        mobileMenu: document.getElementById('mobileMenu'),
        userAvatar: document.getElementById('userAvatar'),
        userDropdown: document.getElementById('userDropdown'),
        loginBtn: document.getElementById('loginBtn'),
        registerBtn: document.getElementById('registerBtn'),
        loginModal: document.getElementById('loginModal'),
        registerModal: document.getElementById('registerModal'),
        publishModal: document.getElementById('publishModal'),
        publishFab: document.getElementById('publishFab'),
        publishBtn: document.getElementById('heroPublishBtn'),
        quickPublishBtn: document.getElementById('quickPublishBtn'),
        publishClose: document.getElementById('publishClose'),
        cancelPublish: document.getElementById('cancelPublish'),
        loginClose: document.getElementById('loginClose'),
        registerClose: document.getElementById('registerClose'),
        loginForm: document.getElementById('loginForm'),
        registerForm: document.getElementById('registerForm'),
        publishForm: document.getElementById('publishForm'),
        uploadBtn: document.getElementById('uploadBtn'),
        twitterImage: document.getElementById('twitterImage'),
        imagePreview: document.getElementById('imagePreview'),
        removeImage: document.getElementById('removeImage'),
        twitterContent: document.getElementById('twitterContent'),
        charCount: document.getElementById('charCount'),
        toast: document.getElementById('toast'),
        loadMoreBtn: document.getElementById('loadMoreBtn')
    };

    // 工具函数
    const Utils = {
        // 显示提示
        showToast(message, type = 'info') {
            const toast = elements.toast;
            if (!toast) return;
            
            toast.querySelector('.toast-message').textContent = message;
            toast.className = 'toast show ' + type;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        },

        // 异步请求
        async request(url, options = {}) {
            try {
                const formData = new FormData();
                if (options.data) {
                    Object.keys(options.data).forEach(key => {
                        formData.append(key, options.data[key]);
                    });
                }
                
                const response = await fetch(url, {
                    method: options.method || 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                return await response.json();
            } catch (error) {
                console.error('Request error:', error);
                return { code: -1, msg: '网络错误，请稍后重试' };
            }
        },

        // 格式化文件大小
        formatSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    };

    // 主题切换
    const ThemeManager = {
        init() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            this.setTheme(savedTheme);
            
            if (elements.themeToggle) {
                elements.themeToggle.addEventListener('click', () => {
                    const currentTheme = document.getElementById('app').classList.contains('dark-mode') ? 'dark' : 'light';
                    this.setTheme(currentTheme === 'dark' ? 'light' : 'dark');
                });
            }
        },

        setTheme(theme) {
            const app = document.getElementById('app');
            const themeIcon = document.querySelector('.theme-icon');
            
            if (theme === 'dark') {
                app.classList.add('dark-mode');
                app.classList.remove('light-mode');
                if (themeIcon) themeIcon.textContent = '☀️';
            } else {
                app.classList.add('light-mode');
                app.classList.remove('dark-mode');
                if (themeIcon) themeIcon.textContent = '🌙';
            }
            
            localStorage.setItem('theme', theme);
        }
    };

    // 移动端菜单
    const MobileMenu = {
        init() {
            if (elements.mobileMenuBtn) {
                elements.mobileMenuBtn.addEventListener('click', () => {
                    elements.mobileMenu.classList.toggle('active');
                    this.toggleIcon();
                });
            }

            // 点击其他区域关闭菜单
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.mobile-menu') && !e.target.closest('.mobile-menu-btn')) {
                    elements.mobileMenu.classList.remove('active');
                    this.resetIcon();
                }
            });
        },

        toggleIcon() {
            const spans = elements.mobileMenuBtn.querySelectorAll('span');
            if (elements.mobileMenu.classList.contains('active')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -6px)';
            } else {
                this.resetIcon();
            }
        },

        resetIcon() {
            const spans = elements.mobileMenuBtn.querySelectorAll('span');
            spans[0].style.transform = '';
            spans[1].style.opacity = '';
            spans[2].style.transform = '';
        }
    };

    // 模态框管理
    const ModalManager = {
        init() {
            // 登录按钮
            const loginBtns = [
                elements.loginBtn,
                document.getElementById('heroLoginBtn'),
                document.getElementById('sidebarLoginBtn'),
                document.getElementById('mobileLoginBtn')
            ].filter(Boolean);

            loginBtns.forEach(btn => {
                btn.addEventListener('click', () => this.open('login'));
            });

            // 注册按钮
            const registerBtns = [
                elements.registerBtn,
                document.getElementById('heroRegisterBtn'),
                document.getElementById('sidebarRegisterBtn'),
                document.getElementById('mobileRegisterBtn')
            ].filter(Boolean);

            registerBtns.forEach(btn => {
                btn.addEventListener('click', () => this.open('register'));
            });

            // 发布按钮
            const publishBtns = [
                elements.publishFab,
                elements.publishBtn,
                elements.quickPublishBtn
            ].filter(Boolean);

            publishBtns.forEach(btn => {
                btn.addEventListener('click', () => this.open('publish'));
            });

            // 关闭按钮
            if (elements.loginClose) {
                elements.loginClose.addEventListener('click', () => this.close('login'));
            }
            if (elements.registerClose) {
                elements.registerClose.addEventListener('click', () => this.close('register'));
            }
            if (elements.publishClose) {
                elements.publishClose.addEventListener('click', () => this.close('publish'));
            }
            if (elements.cancelPublish) {
                elements.cancelPublish.addEventListener('click', () => this.close('publish'));
            }

            // 点击遮罩关闭
            const modals = ['loginModal', 'registerModal', 'publishModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.querySelector('.modal-overlay').addEventListener('click', () => {
                        const modalName = modalId.replace('Modal', '');
                        this.close(modalName);
                    });
                }
            });

            // ESC键关闭
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    ['login', 'register', 'publish'].forEach(modal => this.close(modal));
                }
            });
        },

        open(modalName) {
            const modal = document.getElementById(modalName + 'Modal');
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        },

        close(modalName) {
            const modal = document.getElementById(modalName + 'Modal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    };

    // 用户菜单
    const UserMenu = {
        init() {
            if (elements.userAvatar) {
                elements.userAvatar.addEventListener('click', () => {
                    elements.userDropdown.classList.toggle('show');
                });

                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.user-menu')) {
                        elements.userDropdown.classList.remove('show');
                    }
                });
            }
        }
    };

    // 登录注册表单
    const AuthForm = {
        init() {
            if (elements.loginForm) {
                elements.loginForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    await this.handleLogin();
                });
            }

            if (elements.registerForm) {
                elements.registerForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    await this.handleRegister();
                });
            }
        },

        async handleLogin() {
            const form = elements.loginForm;
            const username = form.querySelector('[name="username"]').value;
            const password = form.querySelector('[name="password"]').value;
            const ispersist = form.querySelector('[name="ispersist"]').checked ? 1 : 0;

            const result = await Utils.request(Config.blogUrl + 'action/login', {
                data: {
                    username,
                    password,
                    ispersist
                }
            });

            if (result.code === 0) {
                Utils.showToast('登录成功', 'success');
                ModalManager.close('login');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                Utils.showToast(result.msg || '登录失败', 'error');
            }
        },

        async handleRegister() {
            const form = elements.registerForm;
            const username = form.querySelector('[name="username"]').value;
            const email = form.querySelector('[name="email"]').value;
            const password = form.querySelector('[name="password"]').value;
            const password2 = form.querySelector('[name="password2"]').value;

            if (password !== password2) {
                Utils.showToast('两次密码输入不一致', 'error');
                return;
            }

            const result = await Utils.request(Config.blogUrl + 'action/register', {
                data: {
                    username,
                    email,
                    password
                }
            });

            if (result.code === 0) {
                Utils.showToast('注册成功', 'success');
                ModalManager.close('register');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                Utils.showToast(result.msg || '注册失败', 'error');
            }
        }
    };

    // 发布微语
    const TwitterPublisher = {
        imageFile: null,

        init() {
            if (elements.publishForm) {
                elements.publishForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    await this.publish();
                });
            }

            if (elements.uploadBtn) {
                elements.uploadBtn.addEventListener('click', () => {
                    elements.twitterImage.click();
                });
            }

            if (elements.twitterImage) {
                elements.twitterImage.addEventListener('change', (e) => {
                    this.handleImageUpload(e.target.files[0]);
                });
            }

            if (elements.removeImage) {
                elements.removeImage.addEventListener('click', () => {
                    this.removeImage();
                });
            }

            if (elements.twitterContent) {
                elements.twitterContent.addEventListener('input', () => {
                    this.updateCharCount();
                });
            }
        },

        updateCharCount() {
            if (elements.charCount) {
                const length = elements.twitterContent.value.length;
                elements.charCount.textContent = length;
                
                if (length > 500) {
                    elements.charCount.style.color = '#b00020';
                } else {
                    elements.charCount.style.color = '';
                }
            }
        },

        handleImageUpload(file) {
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                Utils.showToast('请上传图片文件', 'error');
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                Utils.showToast('图片大小不能超过5MB', 'error');
                return;
            }

            this.imageFile = file;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = elements.imagePreview;
                const img = preview.querySelector('img');
                img.src = e.target.result;
                preview.style.display = 'inline-block';
                elements.uploadBtn.style.display = 'none';
            };
            reader.readAsDataURL(file);
        },

        removeImage() {
            this.imageFile = null;
            elements.imagePreview.style.display = 'none';
            elements.uploadBtn.style.display = 'inline-flex';
            elements.twitterImage.value = '';
        },

        async publish() {
            const content = elements.twitterContent.value.trim();

            if (!content) {
                Utils.showToast('请输入内容', 'error');
                return;
            }

            if (content.length > 500) {
                Utils.showToast('内容不能超过500字', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('content', content);
            
            if (this.imageFile) {
                formData.append('image', this.imageFile);
            }

            try {
                const response = await fetch(Config.blogUrl + 'admin/twitter.php?action=pub', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.code === 0) {
                    Utils.showToast('发布成功', 'success');
                    ModalManager.close('publish');
                    elements.publishForm.reset();
                    this.removeImage();
                    this.updateCharCount();
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Utils.showToast(result.msg || '发布失败', 'error');
                }
            } catch (error) {
                console.error('Publish error:', error);
                Utils.showToast('发布失败，请稍后重试', 'error');
            }
        }
    };

    // 微语交互
    const TwitterActions = {
        init() {
            // 点赞
            document.querySelectorAll('.like-btn').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    const tid = btn.dataset.id;
                    await this.toggleLike(tid, btn);
                });
            });

            // 评论
            document.querySelectorAll('.comment-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tid = btn.dataset.id;
                    const commentsSection = document.getElementById('comments-' + tid);
                    if (commentsSection) {
                        commentsSection.classList.toggle('show');
                    }
                });
            });

            // 评论输入
            document.querySelectorAll('.comment-input').forEach(input => {
                input.addEventListener('keypress', async (e) => {
                    if (e.key === 'Enter') {
                        const tid = input.dataset.twitter;
                        const content = input.value.trim();
                        if (content) {
                            await this.postComment(tid, content, input);
                        }
                    }
                });
            });

            // 删除微语
            document.querySelectorAll('.delete-twitter').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const tid = btn.dataset.id;
                    if (confirm('确定要删除这条微语吗？')) {
                        await this.deleteTwitter(tid);
                    }
                });
            });

            // 分享
            document.querySelectorAll('.share-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tid = btn.dataset.id;
                    const url = window.location.href.split('#')[0] + '#twitter-' + tid;
                    this.copyToClipboard(url);
                });
            });

            // 加载更多
            if (elements.loadMoreBtn) {
                elements.loadMoreBtn.addEventListener('click', () => this.loadMore());
            }
        },

        async toggleLike(tid, btn) {
            const result = await Utils.request(Config.blogUrl + 'admin/twitter.php?action=like', {
                data: { tid }
            });

            if (result.code === 0) {
                const countEl = btn.querySelector('.action-count');
                const currentCount = parseInt(countEl.textContent);
                
                if (result.data.action === 'like') {
                    btn.classList.add('liked');
                    countEl.textContent = currentCount + 1;
                } else {
                    btn.classList.remove('liked');
                    countEl.textContent = currentCount - 1;
                }
            } else if (result.code === -1) {
                // 未登录，弹出登录框
                ModalManager.open('login');
            } else {
                Utils.showToast(result.msg || '操作失败', 'error');
            }
        },

        async postComment(tid, content, inputEl) {
            const result = await Utils.request(Config.blogUrl + 'admin/twitter.php?action=comment', {
                data: { tid, content }
            });

            if (result.code === 0) {
                Utils.showToast('评论成功', 'success');
                inputEl.value = '';
                
                // 刷新评论列表
                window.location.reload();
            } else if (result.code === -1) {
                ModalManager.open('login');
            } else {
                Utils.showToast(result.msg || '评论失败', 'error');
            }
        },

        async deleteTwitter(tid) {
            const result = await Utils.request(Config.blogUrl + 'admin/twitter.php?action=del', {
                data: { tid }
            });

            if (result.code === 0) {
                Utils.showToast('删除成功', 'success');
                const card = document.querySelector(`.twitter-card[data-id="${tid}"]`);
                if (card) {
                    card.remove();
                }
            } else {
                Utils.showToast(result.msg || '删除失败', 'error');
            }
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Utils.showToast('链接已复制到剪贴板', 'success');
            }).catch(() => {
                Utils.showToast('复制失败', 'error');
            });
        },

        async loadMore() {
            const btn = elements.loadMoreBtn;
            const currentPage = parseInt(btn.dataset.page) + 1;
            
            btn.disabled = true;
            btn.textContent = '加载中...';

            try {
                const response = await fetch(`${Config.blogUrl}?page=${currentPage}&ajax=1`);
                const html = await response.text();
                
                if (html) {
                    const twitterList = document.getElementById('twitterList');
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    
                    const newCards = tempDiv.querySelectorAll('.twitter-card');
                    newCards.forEach(card => {
                        twitterList.insertBefore(card, btn.closest('.load-more'));
                    });

                    btn.dataset.page = currentPage;
                    
                    if (newCards.length < 20) {
                        btn.closest('.load-more').remove();
                    }
                } else {
                    btn.closest('.load-more').remove();
                }
            } catch (error) {
                console.error('Load more error:', error);
                Utils.showToast('加载失败', 'error');
            }

            btn.disabled = false;
            btn.textContent = '加载更多';
        }
    };

    // 用户中心
    const UserCenter = {
        init() {
            // 导航切换
            const navItems = document.querySelectorAll('.user-nav .nav-item');
            navItems.forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    // 更新导航状态
                    navItems.forEach(nav => nav.classList.remove('active'));
                    item.classList.add('active');
                    
                    // 显示对应内容
                    const targetId = item.getAttribute('href').substring(1);
                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.remove('active');
                    });
                    document.getElementById(targetId).classList.add('active');
                });
            });

            // 头像上传
            const avatarInput = document.getElementById('avatarInput');
            const uploadAvatarBtn = document.getElementById('uploadAvatarBtn');
            
            if (uploadAvatarBtn && avatarInput) {
                uploadAvatarBtn.addEventListener('click', () => {
                    avatarInput.click();
                });

                avatarInput.addEventListener('change', async (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        await this.uploadAvatar(file);
                    }
                });
            }

            // 资料表单
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    await this.updateProfile(profileForm);
                });
            }

            // 密码表单
            const passwordForm = document.getElementById('passwordForm');
            if (passwordForm) {
                passwordForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    await this.updatePassword(passwordForm);
                });
            }
        },

        async uploadAvatar(file) {
            if (!file.type.startsWith('image/')) {
                Utils.showToast('请上传图片文件', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('avatar', file);

            try {
                const response = await fetch(Config.blogUrl + 'admin/user.php?action=update_avatar', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.code === 0) {
                    Utils.showToast('头像更新成功', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Utils.showToast(result.msg || '上传失败', 'error');
                }
            } catch (error) {
                console.error('Upload error:', error);
                Utils.showToast('上传失败', 'error');
            }
        },

        async updateProfile(form) {
            const formData = new FormData(form);
            
            try {
                const response = await fetch(Config.blogUrl + 'admin/user.php?action=update_profile', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.code === 0) {
                    Utils.showToast('资料更新成功', 'success');
                } else {
                    Utils.showToast(result.msg || '更新失败', 'error');
                }
            } catch (error) {
                console.error('Update profile error:', error);
                Utils.showToast('更新失败', 'error');
            }
        },

        async updatePassword(form) {
            const formData = new FormData(form);
            const newPassword = formData.get('new_password');
            const confirmPassword = formData.get('confirm_password');

            if (newPassword !== confirmPassword) {
                Utils.showToast('两次密码输入不一致', 'error');
                return;
            }

            try {
                const response = await fetch(Config.blogUrl + 'admin/user.php?action=update_password', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.code === 0) {
                    Utils.showToast('密码修改成功', 'success');
                    form.reset();
                } else {
                    Utils.showToast(result.msg || '修改失败', 'error');
                }
            } catch (error) {
                console.error('Update password error:', error);
                Utils.showToast('修改失败', 'error');
            }
        }
    };

    // 图片预览
    const ImagePreview = {
        init() {
            document.querySelectorAll('.twitter-image img').forEach(img => {
                img.addEventListener('click', () => {
                    this.show(img.src);
                });
            });
        },

        show(src) {
            const overlay = document.createElement('div');
            overlay.className = 'image-preview-overlay';
            overlay.innerHTML = `<img src="${src}" alt="预览">`;
            overlay.addEventListener('click', () => {
                overlay.remove();
            });
            document.body.appendChild(overlay);
            requestAnimationFrame(() => {
                overlay.classList.add('active');
            });
        }
    };

    // 初始化
    function init() {
        ThemeManager.init();
        MobileMenu.init();
        ModalManager.init();
        UserMenu.init();
        AuthForm.init();
        TwitterPublisher.init();
        TwitterActions.init();
        UserCenter.init();
        ImagePreview.init();

        console.log('微话主题初始化完成');
    }

    // DOM加载完成后初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
