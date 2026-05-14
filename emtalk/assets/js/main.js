document.addEventListener('DOMContentLoaded', function() {
    const app = document.getElementById('app');
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.querySelector('.theme-icon');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const loginBtns = document.querySelectorAll('#loginBtn, #heroLoginBtn, #sidebarLoginBtn, #mobileLoginBtn');
    const registerBtns = document.querySelectorAll('#registerBtn, #heroRegisterBtn, #sidebarRegisterBtn, #mobileRegisterBtn');
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const publishModal = document.getElementById('publishModal');
    const loginClose = document.getElementById('loginClose');
    const registerClose = document.getElementById('registerClose');
    const publishClose = document.getElementById('publishClose');
    const cancelPublish = document.getElementById('cancelPublish');
    const publishFab = document.getElementById('publishFab');
    const openPublishBtn = document.getElementById('openPublishBtn');
    const twitterContent = document.getElementById('twitterContent');
    const charCount = document.getElementById('charCount');
    const uploadBtn = document.getElementById('uploadBtn');
    const twitterImage = document.getElementById('twitterImage');
    const imagePreview = document.getElementById('imagePreview');
    const removeImage = document.getElementById('removeImage');
    
    const savedTheme = localStorage.getItem('emtalk_theme');
    if (savedTheme === 'dark') {
        app.classList.add('dark-mode');
        app.classList.remove('light-mode');
        if (themeIcon) themeIcon.textContent = '☀️';
    }
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const isDark = app.classList.toggle('dark-mode');
            app.classList.toggle('light-mode', !isDark);
            
            if (themeIcon) {
                themeIcon.textContent = isDark ? '☀️' : '🌙';
            }
            
            localStorage.setItem('emtalk_theme', isDark ? 'dark' : 'light');
        });
    }
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            mobileMenuBtn.classList.toggle('active');
        });
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.mobile-menu') && !e.target.closest('.mobile-menu-btn')) {
                mobileMenu.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
            }
        });
    }
    
    function openModal(modal) {
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeModal(modal) {
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    loginBtns.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal(loginModal);
            });
        }
    });
    
    registerBtns.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                openModal(registerModal);
            });
        }
    });
    
    if (loginClose) loginClose.addEventListener('click', () => closeModal(loginModal));
    if (registerClose) registerClose.addEventListener('click', () => closeModal(registerModal));
    if (publishClose) publishClose.addEventListener('click', () => closeModal(publishModal));
    if (cancelPublish) cancelPublish.addEventListener('click', () => closeModal(publishModal));
    
    if (publishFab) publishFab.addEventListener('click', () => openModal(publishModal));
    if (openPublishBtn) openPublishBtn.addEventListener('click', () => openModal(publishModal));
    
    [loginModal, registerModal, publishModal].forEach(modal => {
        if (modal) {
            modal.querySelector('.modal-overlay').addEventListener('click', function() {
                closeModal(modal);
            });
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            [loginModal, registerModal, publishModal].forEach(closeModal);
        }
    });
    
    if (twitterContent && charCount) {
        twitterContent.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 500) {
                charCount.style.color = '#b00020';
            } else {
                charCount.style.color = '';
            }
        });
    }
    
    if (uploadBtn && twitterImage) {
        uploadBtn.addEventListener('click', function() {
            twitterImage.click();
        });
        
        twitterImage.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (imagePreview) {
                        const img = imagePreview.querySelector('img');
                        img.src = e.target.result;
                        imagePreview.style.display = 'inline-block';
                        uploadBtn.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    if (removeImage) {
        removeImage.addEventListener('click', function(e) {
            e.preventDefault();
            if (twitterImage) twitterImage.value = '';
            if (imagePreview) imagePreview.style.display = 'none';
            if (uploadBtn) uploadBtn.style.display = 'inline-flex';
        });
    }
    
    document.querySelectorAll('.comment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tid = this.dataset.id;
            const comments = document.getElementById('comments-' + tid);
            if (comments) {
                comments.classList.toggle('show');
            }
        });
    });
    
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tid = this.dataset.id;
            const countEl = this.querySelector('.action-count');
            
            fetch(BLOG_URL + 'admin/twitter.php?action=like', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'tid=' + tid
            })
            .then(res => res.json())
            .then(data => {
                if (data.code === 0) {
                    if (data.data.action === 'like') {
                        this.classList.add('liked');
                        countEl.textContent = parseInt(countEl.textContent) + 1;
                    } else {
                        this.classList.remove('liked');
                        countEl.textContent = parseInt(countEl.textContent) - 1;
                    }
                } else if (data.code === -1) {
                    openModal(loginModal);
                }
            })
            .catch(err => console.error(err));
        });
    });
    
    document.querySelectorAll('.comment-input').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                const tid = this.dataset.tid;
                
                fetch(BLOG_URL + 'admin/twitter.php?action=comment', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'tid=' + tid + '&content=' + encodeURIComponent(this.value.trim())
                })
                .then(res => res.json())
                .then(data => {
                    if (data.code === 0) {
                        this.value = '';
                        location.reload();
                    } else if (data.code === -1) {
                        openModal(loginModal);
                    }
                })
                .catch(err => console.error(err));
            }
        });
    });
    
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tid = this.dataset.id;
            const url = window.location.href.split('#')[0] + '#twitter-' + tid;
            
            navigator.clipboard.writeText(url).then(function() {
                showToast('链接已复制');
            }).catch(function() {
                showToast('复制失败');
            });
        });
    });
    
    function showToast(message, type = 'info') {
        const toast = document.getElementById('toast');
        if (toast) {
            const msgEl = toast.querySelector('.toast-message');
            if (msgEl) msgEl.textContent = message;
            toast.className = 'toast show ' + type;
            
            setTimeout(function() {
                toast.classList.remove('show');
            }, 3000);
        }
    }
    
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const page = parseInt(this.dataset.page) + 1;
            
            this.disabled = true;
            this.textContent = '加载中...';
            
            fetch(BLOG_URL + '?page=' + page + '&ajax=1')
            .then(res => res.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html;
                
                const cards = temp.querySelectorAll('.twitter-card');
                if (cards.length) {
                    const list = document.getElementById('twitterList');
                    cards.forEach(card => {
                        list.insertBefore(card, loadMoreBtn.parentElement);
                    });
                    loadMoreBtn.dataset.page = page;
                    
                    if (cards.length < 20) {
                        loadMoreBtn.parentElement.remove();
                    }
                } else {
                    loadMoreBtn.parentElement.remove();
                }
            })
            .catch(err => {
                console.error(err);
                showToast('加载失败');
            })
            .finally(() => {
                loadMoreBtn.disabled = false;
                loadMoreBtn.textContent = '加载更多';
            });
        });
    }
});
