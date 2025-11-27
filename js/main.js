document.addEventListener('DOMContentLoaded', (event) => {

    // --- 1. 代码高亮 ---
    const wpCodeBlocks = document.querySelectorAll('pre.wp-block-code');
    wpCodeBlocks.forEach(block => {
        const code = block.querySelector('code');
        if (code) {
            block.classList.forEach(className => {
                if (className.startsWith('language-')) code.classList.add(className);
            });
        }
        if (typeof hljs !== 'undefined' && code) hljs.highlightElement(code);
    });

    // --- 2. Lightbox ---
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const closeBtn = document.getElementById('lightbox-close');
    const images = document.querySelectorAll('.entry-content img, .wp-block-image img');

    images.forEach(img => {
        img.classList.add('cursor-zoom-in');
        img.addEventListener('click', (e) => {
            if (img.parentElement.tagName === 'A') return;
            e.preventDefault();
            lightboxImg.src = img.src;
            if (img.srcset) lightboxImg.srcset = img.srcset;
            if (lightbox) {
                lightbox.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
    });
    const closeLightbox = () => {
        if (lightbox) {
            lightbox.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };
    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (lightbox) lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });

    // --- 3. TOC 目录 ---
    const article = document.getElementById('post-content');
    const tocContainer = document.getElementById('toc-container');
    const tocNav = document.getElementById('toc-nav');
    const progressBar = document.getElementById('reading-progress');

    window.addEventListener('scroll', () => {
        if (progressBar) {
            const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollPercent = (scrollTop / scrollHeight) * 100;
            progressBar.style.width = scrollPercent + '%';
        }
    });

    if (article && tocNav && tocContainer) {
        const headers = article.querySelectorAll('h2, h3');
        if (headers.length > 0) {
            tocContainer.classList.remove('opacity-0');
            let tocHTML = '';

            headers.forEach((header, index) => {
                if (!header.id) header.id = 'section-' + index;
                const level = header.tagName.toLowerCase();
                const paddingClass = level === 'h3' ? 'pl-6 text-xs' : 'pl-3 text-sm';
                tocHTML += `<a href="#${header.id}" class="toc-link ${paddingClass}" data-target="${header.id}">${header.textContent}</a>`;
            });

            // 新增：评论区链接 (优化样式：虚线分割)
            if (document.getElementById('comments')) {
                tocHTML += `<div class="mt-3 pt-3 border-t border-dashed border-gray-200 dark:border-gray-800"><a href="#comments" class="toc-link pl-3 text-sm">评论区</a></div>`;
            }

            tocNav.innerHTML = tocHTML;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        document.querySelectorAll('.toc-link').forEach(link => link.classList.remove('active'));
                        const activeLink = document.querySelector(`.toc-link[data-target="${entry.target.id}"]`);
                        if (activeLink) activeLink.classList.add('active');
                    }
                });
            }, { rootMargin: '-100px 0px -70% 0px' });
            headers.forEach(header => observer.observe(header));
        } else {
            tocContainer.style.display = 'none';
        }
    }

    // --- 4. 移动端菜单 ---
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // --- 5. 音频播放器 ---
    const audioElements = document.querySelectorAll('audio');
    audioElements.forEach(audio => {
        if (audio.closest('.zen-audio-player')) return;
        const player = document.createElement('div');
        player.className = 'zen-audio-player';
        const btn = document.createElement('div');
        btn.className = 'zen-audio-btn';
        btn.innerHTML = '<i class="ph ph-play text-lg"></i>';
        const progressContainer = document.createElement('div');
        progressContainer.className = 'zen-audio-progress-container';
        const progressBar = document.createElement('div');
        progressBar.className = 'zen-audio-progress-bar';
        progressContainer.appendChild(progressBar);
        const timeDisplay = document.createElement('div');
        timeDisplay.className = 'zen-audio-time';
        timeDisplay.innerText = '00:00';
        player.appendChild(btn);
        player.appendChild(progressContainer);
        player.appendChild(timeDisplay);
        audio.parentNode.insertBefore(player, audio.nextSibling);
        audio.style.display = 'none';
        btn.addEventListener('click', () => {
            if (audio.paused) { audio.play(); btn.innerHTML = '<i class="ph ph-pause text-lg"></i>'; }
            else { audio.pause(); btn.innerHTML = '<i class="ph ph-play text-lg"></i>'; }
        });
        audio.addEventListener('timeupdate', () => {
            const percent = (audio.currentTime / audio.duration) * 100;
            progressBar.style.width = percent + '%';
            const minutes = Math.floor(audio.currentTime / 60);
            const seconds = Math.floor(audio.currentTime % 60);
            timeDisplay.innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        });
        progressContainer.addEventListener('click', (e) => {
            const rect = progressContainer.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const duration = audio.duration;
            audio.currentTime = (clickX / rect.width) * duration;
        });
        audio.addEventListener('ended', () => {
            btn.innerHTML = '<i class="ph ph-play text-lg"></i>';
            progressBar.style.width = '0%';
        });
    });

    // --- 6. 返回顶部按钮 ---
    const backToTopBtn = document.getElementById('back-to-top');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
            } else {
                backToTopBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
            }
        });
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});