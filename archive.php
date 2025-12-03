<?php get_header(); ?>

<!-- 归档页头部 -->
<header class="mb-12 text-center animate-fade-in">
    <div class="text-sm font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
        <?php 
        if (is_category()) { echo '分类专栏'; }
        elseif (is_tag()) { echo '话题标签'; }
        elseif (is_author()) { echo '作者专栏'; }
        elseif (is_date()) { echo '历史足迹'; }
        else { echo '归档'; }
        ?>
    </div>
    
    <h1 class="text-3xl md:text-4xl font-bold mb-4 serif text-gray-900 dark:text-white">
        <?php 
        if (is_category()) {
            single_cat_title();
        } elseif (is_tag()) {
            single_tag_title();
        } elseif (is_author()) {
            echo get_the_author();
        } elseif (is_day()) {
            echo get_the_date();
        } elseif (is_month()) {
            echo get_the_date('Y年 n月');
        } elseif (is_year()) {
            echo get_the_date('Y年');
        } else {
            the_archive_title('', false); 
        }
        ?>
    </h1>

    <?php if (get_the_archive_description()) : ?>
        <div class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mt-4 prose dark:prose-invert">
            <?php the_archive_description(); ?>
        </div>
    <?php endif; ?>
</header>

<div class="space-y-12 max-w-zen mx-auto animate-fade-in">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('group'); ?>>
            <!-- 文章元信息 -->
            <div class="text-xs font-medium uppercase tracking-wider text-gray-600 dark:text-gray-400 mb-3 flex items-center gap-2">
                <?php 
                $cat = get_the_category(); 
                if ($cat) {
                    echo '<a href="' . get_category_link($cat[0]->term_id) . '" class="hover:text-gray-900 dark:hover:text-white transition-colors">' . $cat[0]->name . '</a>';
                    echo '<span class="text-gray-300 dark:text-gray-700" aria-hidden="true">*</span>';
                }
                ?>
                <a href="<?php echo get_month_link(get_the_date('Y'), get_the_date('n')); ?>" class="hover:text-gray-900 dark:hover:text-white transition-colors" aria-label="查看 <?php echo get_the_date('Y年n月'); ?> 的所有文章">
                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('Y年 n月 j日'); ?></time>
                </a>
            </div>
            
            <!-- 标题 -->
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
                <a href="<?php the_permalink(); ?>" class="hover:underline decoration-1 underline-offset-4 decoration-gray-400">
                    <?php the_title(); ?>
                </a>
            </h2>

            <!-- 摘要 -->
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4 line-clamp-3">
                <?php echo wp_trim_words(get_the_excerpt(), 100, '...'); ?>
            </div>

            <!-- 阅读更多 (优化动画) -->
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm font-bold tracking-wide text-gray-900 dark:text-white transition-all duration-300 ease-[cubic-bezier(0.23,1,0.32,1)] group-hover:translate-x-1.5 group-hover:text-blue-600 dark:group-hover:text-blue-400">
                阅读更多 
                <span class="screen-reader-text">关于 <?php the_title(); ?></span> 
                <i class="ph ph-arrow-right ml-1.5 transform transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true"></i>
            </a>
        </article>

        <div class="post-divider" aria-hidden="true"></div>

    <?php endwhile; ?>
        
        <!-- 分页导航 -->
        <?php zen_pagination(); ?>

    <?php else : ?>
        <div class="text-center py-20">
            <h2 class="text-xl font-bold mb-2">此处空空如也</h2>
            <p class="text-gray-500">该专栏或话题下暂时没有文章。</p>
            <a href="<?php echo home_url(); ?>" class="inline-block mt-4 text-blue-600 hover:underline">返回首页</a>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>