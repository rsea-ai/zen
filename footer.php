</main> <!-- End Main -->

<footer role="contentinfo" class="w-full border-t border-gray-100 dark:border-gray-800 mt-auto">
    <div class="max-w-zen mx-auto px-4 sm:px-6 py-6 md:h-20 md:py-0 flex flex-col md:flex-row items-center justify-between text-xs text-gray-600 dark:text-gray-400 gap-y-3">
        <div class="text-center md:text-left">
            &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 保留所有权利.
        </div>
        <div class="flex items-center gap-4">
            <!-- A11y: aria-label starts with visible text -->
            <a href="https://github.com/rsea-ai/zen" target="_blank" rel="noopener noreferrer" class="hover:text-gray-900 dark:hover:text-white transition-colors" aria-label="Theme By Zen (在新窗口打开)">Theme By Zen</a>
            <span class="inline-block w-1 h-1 bg-gray-300 rounded-full" aria-hidden="true"></span>
            <a href="https://wordpress.org/" target="_blank" rel="noopener noreferrer" class="hover:text-gray-900 dark:hover:text-white transition-colors" aria-label="Powered By WordPress (在新窗口打开)">Powered By WordPress</a>
            <span class="inline-block w-1 h-1 bg-gray-300 rounded-full" aria-hidden="true"></span>
            <a href="<?php bloginfo('rss2_url'); ?>" target="_blank" class="hover:text-gray-900 dark:hover:text-white transition-colors flex items-center gap-1" title="订阅 RSS">
                <i class="ph ph-rss text-sm" aria-hidden="true"></i> RSS
            </a>
        </div>
    </div>
</footer>

<!-- Lightbox -->
<div id="lightbox" 
     role="dialog" 
     aria-modal="true" 
     aria-label="图片查看器" 
     class="fixed inset-0 z-[100] bg-white/95 dark:bg-black/95 backdrop-blur-sm hidden flex items-center justify-center p-4 cursor-zoom-out"
     tabindex="-1">
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-screen object-contain shadow-2xl rounded-sm">
    <button id="lightbox-close" class="absolute top-4 right-4 text-gray-500 hover:text-gray-900 dark:hover:text-white text-3xl focus:outline-none focus:ring-2 focus:ring-blue-500 rounded" aria-label="关闭图片">
        &times;
    </button>
</div>

<!-- Back to Top -->
<button id="back-to-top" class="fixed bottom-8 right-8 w-10 h-10 bg-gray-900 dark:bg-white text-white dark:text-black rounded-full shadow-lg flex items-center justify-center opacity-0 pointer-events-none translate-y-4 transition-all duration-300 z-50 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900" aria-label="返回顶部">
    <i class="ph ph-arrow-up text-xl" aria-hidden="true"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>