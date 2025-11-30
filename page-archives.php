<?php
/*
Template Name: Archives Template
*/
get_header(); ?>

<header class="mb-12 text-center">
    <h1 class="text-3xl font-bold mb-2 serif"><?php the_title(); ?></h1>
    <div class="text-gray-600 dark:text-gray-400">文章时光机</div>
</header>

<div class="max-w-2xl mx-auto space-y-8 animate-fade-in">
    <?php
    // 获取所有文章
    $args = array(
        'posts_per_page' => -1, // 显示所有
        'ignore_sticky_posts' => 1, // 忽略置顶
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $archives_query = new WP_Query($args);
    $year_prev = null;
    
    if ($archives_query->have_posts()) : 
        // A11y: 修复列表结构。年份作为标题在列表之外。
        while ($archives_query->have_posts()) : $archives_query->the_post(); 
            $year_current = get_the_date('Y');
            
            // 如果年份改变
            if ($year_current != $year_prev) {
                // 如果不是第一年，先闭合上一个年份的列表容器
                if ($year_prev != null) { ?>
                    </ul>
                </section>
                <?php } ?>
                
                <section>
                    <h3 class="text-xl font-bold mb-4 text-gray-600 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 pb-2"><?php echo $year_current; ?></h3>
                    <ul class="space-y-4">
            <?php } ?>
            
            <li class="flex items-baseline justify-between group">
                <a href="<?php the_permalink(); ?>" class="text-lg text-gray-800 dark:text-gray-200 hover:underline decoration-1 underline-offset-4">
                    <?php the_title(); ?>
                </a>
                <span class="text-sm text-gray-600 dark:text-gray-500 shrink-0 font-mono"><?php echo get_the_date('m-d'); ?></span>
            </li>

            <?php $year_prev = $year_current; ?>
            
        <?php endwhile; ?>
            </ul> <!-- Close last ul -->
        </section> <!-- Close last section -->
    <?php else : ?>
        <p class="text-center text-gray-600">暂无文章。</p>
    <?php endif; wp_reset_postdata(); ?>
</div>

<?php get_footer(); ?>