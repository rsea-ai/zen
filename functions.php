<?php
if (!defined('ABSPATH')) exit;

// 1. 主题设置与国际化加载
function zen_setup() {
    load_theme_textdomain('zen', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'zen'),
        'footer'  => __('Footer Menu', 'zen'),
    ));
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_filter( 'pre_option_link_manager_enabled', '__return_true' );
}
add_action('after_setup_theme', 'zen_setup');

// 2. 加载资源
function zen_scripts() {
    wp_enqueue_script('phosphor-icons', 'https://unpkg.com/@phosphor-icons/web', array(), null, false);
    wp_enqueue_style('highlight-css', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css');
    wp_enqueue_script('highlight-js', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js', array(), null, true);
    
    // 版本号 1.1.5
    wp_enqueue_script('zen-main', get_template_directory_uri() . '/js/main.js', array(), '1.1.5', true); 

    if (file_exists(get_template_directory() . '/assets/css/style.css')) {
        wp_enqueue_style('zen-compiled-style', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime(get_template_directory() . '/assets/css/style.css'));
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'zen_scripts');

// 3. 样式补丁
function zen_add_tailwind_dev() {
    if (!file_exists(get_template_directory() . '/assets/css/style.css')) {
        ?>
        <script src="https://cdn.tailwindcss.com?plugins=typography,forms"></script>
        <script>
            tailwind.config = {
                darkMode: 'media',
                theme: {
                    extend: {
                        maxWidth: { 'zen': '56rem' },
                        typography: (theme) => ({
                            DEFAULT: {
                                css: {
                                    '--tw-prose-body': theme('colors.gray.700'),
                                    '--tw-prose-headings': theme('colors.gray.900'),
                                    '--tw-prose-links': theme('colors.gray.900'),
                                    maxWidth: '100%',
                                    a: { textDecoration: 'none', borderBottom: '1px solid #e5e7eb', transition: 'border-color 0.2s', '&:hover': { borderBottomColor: '#111827' } },
                                    'figure iframe': { marginTop: '0', marginBottom: '0' },
                                },
                            },
                            invert: {
                                css: {
                                    '--tw-prose-body': theme('colors.gray.300'),
                                    '--tw-prose-headings': theme('colors.white'),
                                    '--tw-prose-links': theme('colors.white'),
                                    a: { borderBottom: '1px solid #374151', '&:hover': { borderBottomColor: '#f3f4f6' } },
                                }
                            }
                        }),
                    }
                }
            }
        </script>
        <?php 
    }
    
    ?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@400;700&family=Inter:wght@300;400;600&display=swap');
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .serif { font-family: 'Noto Serif SC', serif; }
        .post-divider { height: 1px; background: linear-gradient(to right, transparent, #e5e7eb, transparent); }
        .dark .post-divider { background: linear-gradient(to right, transparent, #374151, transparent); }
        
        /* --- 评论列表样式 --- */
        .comment-list, .comment-list .children { list-style: none !important; margin: 0; padding: 0; }
        .comment-list > li { margin-bottom: 3rem; }
        .comment-list { padding-top: 1rem; }
        .comment-list .children { margin-top: 2rem; margin-left: 0.5rem; padding-left: 1rem; border-left: 2px solid #e5e7eb; }
        .dark .comment-list .children { border-color: #374151; }
        .comment-list .children > li { margin-top: 2rem; position: relative; }
        @media (min-width: 768px) {
            .comment-list .children { margin-left: 3.5rem; padding-left: 2rem; }
        }

        /* --- 按钮与组件样式 --- */
        #submit {
            background-image: none !important;
            background-color: #111827 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 4px !important; 
            padding: 0.5rem 1.5rem !important; 
            font-weight: 500 !important;
            font-size: 0.8rem !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
            letter-spacing: 0.05em !important;
        }
        #submit:hover { opacity: 0.9; transform: translateY(-1px); }
        @media (prefers-color-scheme: dark) {
            #submit { background-color: #fff !important; color: #000 !important; }
            #submit:hover { background-color: #e5e7eb !important; }
        }

        /* --- TOC 目录样式 --- */
        .toc-link { display: block; padding: 0.375rem 0; border-left: 2px solid transparent; margin-left: -1px; color: #9ca3af; transition: all 0.3s ease; text-decoration: none; }
        
        /* 亮色模式悬浮 */
        .toc-link:hover { color: #111827; }
        /* 暗色模式悬浮 (class 切换方式) */
        .dark .toc-link:hover { color: #fff; }
        /* 修复：暗色模式悬浮 (系统自动方式) - 这段是你缺失的 */
        @media (prefers-color-scheme: dark) {
            .toc-link:hover { color: #fff; }
        }

        .toc-link.active { border-left-color: #111827; color: #111827; font-weight: 500; }
        @media (prefers-color-scheme: dark) { .toc-link.active { border-left-color: #fff; color: #fff; } }

        .wp-block-embed iframe { width: 100% !important; height: auto !important; aspect-ratio: 16 / 9; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }

        .zen-audio-player { display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 9999px; margin: 2rem 0; transition: all 0.3s ease; }
        @media (prefers-color-scheme: dark) { .zen-audio-player { background: #18181b; border-color: #27272a; } }
        .zen-audio-btn { width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #111827; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
        @media (prefers-color-scheme: dark) { .zen-audio-btn { background: #e4e4e7; color: #000; } }
        .zen-audio-progress-container { flex-grow: 1; height: 4px; background: #d1d5db; border-radius: 2px; position: relative; cursor: pointer; }
        @media (prefers-color-scheme: dark) { .zen-audio-progress-container { background: #3f3f46; } }
        .zen-audio-progress-bar { height: 100%; background: #111827; border-radius: 2px; width: 0%; position: relative; }
        @media (prefers-color-scheme: dark) { .zen-audio-progress-bar { background: #fff; } }
        .zen-audio-time { font-size: 0.75rem; font-family: monospace; color: #6b7280; min-width: 40px; text-align: right; }

        .wp-block-file { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; margin: 2rem 0; background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s; }
        .wp-block-file:hover { border-color: #9ca3af; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        @media (prefers-color-scheme: dark) { .wp-block-file { background: #18181b; border-color: #27272a; } .wp-block-file:hover { border-color: #52525b; } }
        .wp-block-file a:first-child { font-weight: 500; color: #111827; text-decoration: none; border: none; }
        @media (prefers-color-scheme: dark) { .wp-block-file a:first-child { color: #f4f4f5; } }
        .wp-block-file .wp-block-file__button { background: #111827; color: #fff; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; text-decoration: none; border: none; transition: opacity 0.2s; }
        .wp-block-file .wp-block-file__button:hover { opacity: 0.9; }
        @media (prefers-color-scheme: dark) { .wp-block-file .wp-block-file__button { background: #fff; color: #000; } }
    </style>
    <?php
}
add_action('wp_head', 'zen_add_tailwind_dev', 5);

// 4. 优化与功能
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
function zen_custom_excerpt_length($length) { return 120; }
add_filter('excerpt_length', 'zen_custom_excerpt_length', 999);
function zen_pagination() {
    global $wp_query;
    $big = 999999999;
    $pages = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
        'type' => 'array',
    ));
    if (is_array($pages)) {
        echo '<div class="pt-8 flex justify-center items-center gap-2">';
        foreach ($pages as $page) {
            if (strpos($page, 'current') !== false) {
                echo "<span class='page-numbers px-3 py-1 border border-transparent bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded-sm text-sm'>" . strip_tags($page) . "</span>";
            } else {
                echo str_replace('page-numbers', 'page-numbers px-3 py-1 border border-gray-200 dark:border-gray-700 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors rounded-sm text-gray-600 dark:text-gray-400', $page);
            }
        }
        echo '</div>';
    }
}
function zen_comment_callback($comment, $args, $depth) {
    ?>
    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body flex gap-4 group relative animate-fade-in">
        <div class="shrink-0">
            <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-full object-cover grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all shadow-sm')); ?>
        </div>
        <div class="flex-grow min-w-0">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white"><?php echo get_comment_author(); ?></h4>
                    <?php if ($comment->user_id === get_the_author_meta('ID')) : ?>
                        <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 dark:bg-gray-800 rounded-sm text-gray-600 dark:text-gray-300 font-medium border border-gray-200 dark:border-gray-700">作者</span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-3">
                     <time class="text-xs text-gray-400 font-mono"><?php printf(__('%1$s前', 'zen'), human_time_diff(get_comment_time('U'), current_time('timestamp'))); ?></time>
                </div>
            </div>
            <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed prose dark:prose-invert max-w-none break-words mb-3"><?php comment_text(); ?></div>
            <div class="text-xs mt-5">
                <?php 
                $reply_args = array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '回复', 'add_below' => 'div-comment'));
                $reply_link = get_comment_reply_link($reply_args);
                echo str_replace("class='comment-reply-link'", "class='inline-flex items-center gap-1 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors cursor-pointer font-medium'", $reply_link);
                ?>
            </div>
        </div>
    </article>
    <?php
}
?>