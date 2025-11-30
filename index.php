<?php get_header(); ?>

<!-- Breadcrumb -->
<nav aria-label="面包屑导航" class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-gray-600 dark:text-gray-400 border-b border-dashed border-gray-200 dark:border-gray-800 pb-4 mb-8">
    <a href="<?php echo home_url(); ?>" class="text-gray-900 dark:text-white font-medium border-b-2 border-gray-900 dark:border-white pb-0.5" aria-current="page">全部</a>
    <span class="text-gray-300" aria-hidden="true">/</span>
    <?php
    $categories = get_categories(array('number' => 5));
    foreach($categories as $category) {
        // A11y: text-gray-600 for contrast
        echo '<a href="' . get_category_link($category->term_id) . '" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">' . $category->name . '</a>';
        echo '<span class="text-gray-300 last:hidden" aria-hidden="true">/</span>';
    }
    ?>
</nav>

<div class="space-y-12">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('group'); ?> aria-labelledby="post-title-<?php the_ID(); ?>">
            <!-- A11y: text-gray-600 -->
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
            
            <h2 id="post-title-<?php the_ID(); ?>" class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
                <a href="<?php the_permalink(); ?>" class="hover:underline decoration-1 underline-offset-4 decoration-gray-400">
                    <?php the_title(); ?>
                </a>
            </h2>

            <!-- A11y: text-gray-600 -->
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4 line-clamp-3">
                <?php echo wp_trim_words(get_the_excerpt(), 100, '...'); ?>
            </div>

            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm font-semibold text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300 transition-colors group-hover:translate-x-1 duration-200">
                阅读更多 <span class="screen-reader-text">关于 <?php the_title(); ?></span> <i class="ph ph-arrow-right ml-1" aria-hidden="true"></i>
            </a>
        </article>

        <div class="post-divider" aria-hidden="true"></div>

    <?php endwhile; ?>
        
        <?php zen_pagination(); ?>

    <?php else : ?>
        <p>暂无文章。</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>