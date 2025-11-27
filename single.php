<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <!-- TOC Container (Desktop) -->
    <!-- 调整 left 位置，使其位于文章内容 (56rem) 的右侧，留出适当间距 -->
    <aside id="toc-container" class="hidden xl:block fixed top-32 w-48 opacity-0 transition-opacity duration-500" style="left: calc(50% + 28rem + 3rem);">
        <div class="relative">
            <!-- 标题 CONTENTS -->
            <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 pl-3">
                目录
            </h4>
            <!-- 导航列表容器 -->
            <!-- border-l-2 border-gray-100 创建基础灰线 -->
            <nav id="toc-nav" class="relative border-l-2 border-gray-100 dark:border-gray-800 space-y-0">
                <!-- JS 将在此处生成链接 -->
            </nav>
        </div>
    </aside>

    <header class="mb-10 text-center">
        <div class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-4 flex items-center justify-center gap-2">
            <?php 
            $cat = get_the_category();
            if ($cat) {
                echo '<a href="' . get_category_link($cat[0]->term_id) . '" class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition">' . $cat[0]->name . '</a>';
                echo '<span class="text-gray-300 dark:text-gray-600">/</span>';
            }
            ?>
            <time><?php echo get_the_date('Y年 n月 j日'); ?></time>
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
            <span class="text-sm text-gray-500 mr-2">标签:</span>
            <?php 
            $tags = get_the_tags();
            if ($tags) {
                foreach($tags as $tag) {
                    echo '<a href="' . get_tag_link($tag->term_id) . '" class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">#' . $tag->name . '</a> ';
                }
            } else {
                echo '<span class="text-xs text-gray-400">无</span>';
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