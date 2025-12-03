<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <!-- 
        1. PC端侧边栏目录 (Large Screen Sidebar TOC)
        显示条件: 屏幕宽度 >= xl (1280px)
        布局: 固定定位，位于内容右侧
    -->
    <aside id="toc-container" class="hidden xl:block fixed top-32 w-56 opacity-0 transition-opacity duration-500" style="left: calc(50% + 28rem + 2rem);" aria-label="文章目录">
        <div class="relative">
            <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-4 pl-3">
                目录
            </h4>
            <!-- 
                FIX: 调整最大高度，防止与评论区重叠
                max-h-[calc(100vh-24rem)]: 增加底部留白，从 12rem 增加到 24rem
            -->
            <nav id="toc-nav" class="relative border-l-2 border-gray-100 dark:border-gray-800 space-y-1 max-h-[calc(100vh-24rem)] overflow-y-auto pr-2 custom-scrollbar" aria-label="桌面端目录导航">
                <!-- JS 填充内容 -->
            </nav>
        </div>
    </aside>

    <!-- 
        2. 悬浮折叠目录按钮 (Floating Toggle Button)
        显示条件: 屏幕宽度 < xl
        位置: 屏幕右侧边缘，垂直居中
        动画: 使用 Tailwind 实现弹性过渡 (Bezier Curve)
    -->
    <button id="floating-toc-btn" 
            class="xl:hidden fixed right-0 top-1/2 -translate-y-1/2 z-50 
                   bg-white dark:bg-[#1a1a1a] p-3 rounded-l-lg 
                   shadow-lg hover:shadow-xl dark:shadow-none dark:hover:shadow-[0_0_15px_rgba(255,255,255,0.15)]
                   border-y border-l border-gray-200 dark:border-gray-700 
                   text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white 
                   transition-all duration-500 ease-[cubic-bezier(0.175,0.885,0.32,1.275)]
                   hover:scale-110 hover:-translate-x-2
                   hidden" 
            aria-label="打开目录" 
            aria-haspopup="dialog" 
            aria-controls="drawer-toc" 
            aria-expanded="false">
        <i class="ph ph-list-bullets text-2xl" aria-hidden="true"></i>
    </button>

    <!-- 
        3. 滑动型目录抽屉 (Sliding Drawer TOC)
    -->
    <div id="toc-overlay" class="fixed inset-0 bg-black/20 dark:bg-black/50 backdrop-blur-sm z-[60] hidden transition-opacity opacity-0" aria-hidden="true"></div>
    
    <aside id="drawer-toc" 
           class="fixed top-0 right-0 w-80 h-full bg-white dark:bg-[#1a1a1a] z-[70] transform translate-x-full transition-transform duration-300 shadow-2xl flex flex-col border-l border-gray-100 dark:border-gray-800" 
           role="dialog" 
           aria-modal="true" 
           aria-labelledby="drawer-toc-title"
           inert>
        
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-[#1f1f1f]/50">
            <h3 id="drawer-toc-title" class="text-sm font-bold uppercase tracking-widest text-gray-900 dark:text-white">目录</h3>
            <button id="drawer-toc-close" class="text-gray-500 hover:text-gray-900 dark:hover:text-white p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500" aria-label="关闭目录">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            <nav id="drawer-toc-nav" class="relative border-l-2 border-gray-100 dark:border-gray-800 space-y-0" aria-label="移动端目录导航">
                <!-- JS 填充内容 -->
            </nav>
        </div>
    </aside>

    <header class="mb-10 text-center">
        <div class="text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-4 flex items-center justify-center gap-2">
            <?php 
            $cat = get_the_category();
            if ($cat) {
                echo '<a href="' . get_category_link($cat[0]->term_id) . '" class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition">' . $cat[0]->name . '</a>';
                echo '<span class="text-gray-300 dark:text-gray-600">/</span>';
            }
            ?>
            <time datetime="<?php echo get_the_date('c'); ?>">
                <a href="<?php echo get_month_link(get_the_date('Y'), get_the_date('n')); ?>" class="hover:text-gray-900 dark:hover:text-white transition-colors">
                    <?php echo get_the_date('Y年 n月 j日'); ?>
                </a>
            </time>
        </div>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight serif">
            <?php the_title(); ?>
        </h1>
    </header>

    <article id="post-content" class="prose prose-lg prose-zinc dark:prose-invert mx-auto focus:outline-none entry-content">
        <?php the_content(); ?>
    </article>

    <div class="mt-12 pt-6 border-t border-gray-100 dark:border-gray-800">
        <div class="flex flex-wrap gap-2 mb-8">
            <span class="text-sm text-gray-600 mr-2">标签:</span>
            <?php 
            $tags = get_the_tags();
            if ($tags) {
                foreach($tags as $tag) {
                    echo '<a href="' . get_tag_link($tag->term_id) . '" class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-2 py-1 rounded hover:bg-gray-200 transition">#' . $tag->name . '</a> ';
                }
            } else {
                echo '<span class="text-xs text-gray-600">无</span>';
            }
            ?>
        </div>
    </div>

    <?php 
    if (comments_open() || get_comments_number()) :
        comments_template();
    endif;
    ?>

<?php endwhile; endif; ?>

<?php get_footer(); ?>