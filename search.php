<?php get_header(); ?>

<!-- 搜索结果头部 -->
<header class="mb-12 text-center animate-fade-in">
    <div class="text-sm font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
        搜索结果
    </div>
    
    <h1 class="text-3xl md:text-4xl font-bold mb-4 serif text-gray-900 dark:text-white break-words">
        “<?php echo get_search_query(); ?>”
    </h1>

    <div class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mt-4 prose dark:prose-invert">
        <?php 
        global $wp_query;
        $count = $wp_query->found_posts;
        echo '共找到 ' . $count . ' 篇相关文章';
        ?>
    </div>
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
                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('Y年 n月 j日'); ?></time>
            </div>
            
            <!-- 标题 (高亮搜索词) -->
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
                <a href="<?php the_permalink(); ?>" class="hover:underline decoration-1 underline-offset-4 decoration-gray-400">
                    <?php 
                        // 高亮标题中的关键词
                        $title = get_the_title();
                        $keys = explode(" ", get_search_query());
                        $title = preg_replace('/('.implode('|', $keys) .')/iu', '<mark class="bg-yellow-200 dark:bg-yellow-800 dark:text-white rounded-sm px-0.5">$0</mark>', $title);
                        echo $title;
                    ?>
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
        <div class="text-center py-20 bg-gray-50 dark:bg-[#1a1a1a] rounded-lg border border-dashed border-gray-200 dark:border-gray-800">
            <div class="mb-4 text-gray-400">
                <i class="ph ph-magnifying-glass text-4xl"></i>
            </div>
            <h2 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">未找到相关内容</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">尝试更换关键词，或者查看归档页面。</p>
            
            <?php 
            // 自动查找归档页面链接
            $archive_pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'page-archives.php',
                'number' => 1
            ));
            // 如果找到，使用该页面链接；否则回退到首页
            $archive_url = (!empty($archive_pages)) ? get_permalink($archive_pages[0]->ID) : home_url();
            ?>
            
            <a href="<?php echo esc_url($archive_url); ?>" class="inline-block px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors text-sm font-medium">
                浏览归档
            </a>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>