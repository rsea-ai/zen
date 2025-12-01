<?php
if (!defined('ABSPATH')) exit;

/**
 * 1. 资源预连接 (性能优化)
 */
function zen_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = 'https://fonts.googleapis.com';
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'zen_resource_hints', 10, 2 );

/**
 * 2. 主题设置
 */
function zen_setup() {
    load_theme_textdomain('zen', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'zen'),
        'footer'  => __('Footer Menu', 'zen'),
    ));
    
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style'));
    add_filter( 'pre_option_link_manager_enabled', '__return_true' );
}
add_action('after_setup_theme', 'zen_setup');

/**
 * 3. 加载资源
 */
function zen_scripts() {
    $ver = '1.1.18'; // 更新版本号

    // A. Google Fonts
    wp_enqueue_style('zen-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Noto+Serif+SC:wght@200..900&display=swap', array(), null);

    // B. Phosphor Icons
    wp_enqueue_script('phosphor-icons', get_template_directory_uri() . '/assets/js/phosphor-icons.js', array(), $ver, false);

    // C. Highlight.js
    wp_enqueue_style('highlight-css', get_template_directory_uri() . '/assets/css/github-dark.min.css', array(), $ver);
    wp_enqueue_script('highlight-js', get_template_directory_uri() . '/assets/js/highlight.min.js', array(), $ver, true);
    
    // D. Main JS
    wp_enqueue_script('zen-main', get_template_directory_uri() . '/js/main.js', array(), $ver, true); 

    // E. Compiled Tailwind CSS
    if (file_exists(get_template_directory() . '/assets/css/style.css')) {
        wp_enqueue_style('zen-compiled-style', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime(get_template_directory() . '/assets/css/style.css'));
    }
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'zen_scripts');

/**
 * 4. 样式补丁 (关键修复: 恢复丢失的样式 + 修复音频播放器对比度 + 音频播放器暗色样式)
 */
function zen_custom_styles() {
    ?>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .serif { font-family: 'Noto Serif SC', serif; }
        .post-divider { height: 1px; background: linear-gradient(to right, transparent, #e5e7eb, transparent); }
        .dark .post-divider { background: linear-gradient(to right, transparent, #374151, transparent); }
        
        /* 评论列表样式 */
        .comment-list, .comment-list .children { list-style: none !important; margin: 0; padding: 0; }
        .comment-list > li { margin-bottom: 3rem; }
        .comment-list { padding-top: 1rem; }
        .comment-list .children { margin-top: 2rem; margin-left: 0.5rem; padding-left: 1rem; border-left: 2px solid #e5e7eb; }
        .dark .comment-list .children { border-color: #374151; }
        .comment-list .children > li { margin-top: 2rem; position: relative; }
        @media (min-width: 768px) { .comment-list .children { margin-left: 3.5rem; padding-left: 2rem; } }

        /* 按钮样式 */
        #submit {
            background-image: none !important;
            background-color: #111827 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 4px !important; 
            padding: 0.75rem 2rem !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        }
        #submit:hover, #submit:focus { opacity: 0.9; transform: translateY(-1px); outline: 2px solid #3b82f6; outline-offset: 2px; }
        
        /* * 暗色模式样式修复：
         * 将原来的白色背景(#fff)改为深灰色(#27272a)，文字改为浅灰(#e5e7eb)，
         * 并添加微弱边框以增加层次感，避免"亮瞎眼"。
         */
        @media (prefers-color-scheme: dark) {
            #submit { 
                background-color: #27272a !important; /* Zinc-800 */
                color: #e5e7eb !important; /* Zinc-200 */
                border: 1px solid #3f3f46 !important; /* Zinc-700 */
            }
            #submit:hover, #submit:focus { 
                background-color: #3f3f46 !important; /* Zinc-700 */
                color: #fff !important;
            }
        }

        /* Skip Link */
        .skip-link { position: absolute; top: -9999px; left: 50%; transform: translateX(-50%); background: #000; color: #fff; padding: 1rem 2rem; z-index: 9999; text-decoration: none; border-radius: 0 0 4px 4px; font-weight: bold; }
        .skip-link:focus { top: 0; outline: 3px solid #3b82f6; }
        .dark .skip-link { background: #fff; color: #000; }

        /* TOC */
        .toc-link { display: block; padding: 0.375rem 0; border-left: 2px solid transparent; margin-left: -1px; color: #4b5563; transition: all 0.3s ease; text-decoration: none; }
        .toc-link:hover, .toc-link:focus { color: #111827; }
        .dark .toc-link { color: #9ca3af; }
        .dark .toc-link:hover, .dark .toc-link:focus { color: #fff; }
        .toc-link.active { border-left-color: #111827; color: #111827; font-weight: 500; }
        @media (prefers-color-scheme: dark) { .toc-link.active { border-left-color: #fff; color: #fff; } }

        /* --- 修复: 恢复音频播放器样式并增强对比度与暗色支持 --- */
        .zen-audio-player { display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1rem; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 9999px; margin: 2rem 0; transition: all 0.3s ease; }
        
        /* 暗色模式下播放器背景变深，边框调整 */
        @media (prefers-color-scheme: dark) { 
            .zen-audio-player { background: #1f2937; border-color: #374151; } 
        }
        
        .zen-audio-btn { width: 2.5rem; height: 2.5rem; border-radius: 50%; background: #111827; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; border: none; padding: 0; }
        .zen-audio-btn:focus { outline: 2px solid #3b82f6; outline-offset: 2px; }
        
        /* 暗色模式下按钮变成浅色，图标深色 */
        @media (prefers-color-scheme: dark) { 
            .zen-audio-btn { background: #e5e7eb; color: #111827; } 
        }
        
        .zen-audio-progress-container { flex-grow: 1; height: 4px; background: #9ca3af; border-radius: 2px; position: relative; cursor: pointer; }
        /* 暗色模式下进度条底色变深 */
        @media (prefers-color-scheme: dark) { 
            .zen-audio-progress-container { background: #4b5563; } 
        }

        .zen-audio-progress-bar { height: 100%; background: #111827; border-radius: 2px; width: 0%; position: relative; }
        /* 暗色模式下进度条填充色变成浅色 */
        @media (prefers-color-scheme: dark) { 
            .zen-audio-progress-bar { background: #e5e7eb; } 
        }
        
        .zen-audio-time { font-size: 0.75rem; font-family: monospace; color: #4b5563; min-width: 40px; text-align: right; font-weight: 500; }
        /* 暗色模式下时间文字颜色变浅 */
        @media (prefers-color-scheme: dark) { 
            .zen-audio-time { color: #d1d5db; } 
        }

        /* --- 修复: 恢复文件下载块样式 --- */
        .wp-block-file { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; margin: 2rem 0; background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s; }
        .wp-block-file:hover { border-color: #9ca3af; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        @media (prefers-color-scheme: dark) { .wp-block-file { background: #18181b; border-color: #27272a; } .wp-block-file:hover { border-color: #52525b; } }
        .wp-block-file a:first-child { font-weight: 500; color: #111827; text-decoration: none; border: none; }
        @media (prefers-color-scheme: dark) { .wp-block-file a:first-child { color: #f4f4f5; } }
        .wp-block-file .wp-block-file__button { background: #111827; color: #fff; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; text-decoration: none; border: none; transition: opacity 0.2s; }
        .wp-block-file .wp-block-file__button:hover { opacity: 0.9; }
        @media (prefers-color-scheme: dark) { .wp-block-file .wp-block-file__button { background: #fff; color: #000; } }

        /* Embeds */
        .wp-block-embed iframe { width: 100% !important; height: auto !important; aspect-ratio: 16 / 9; border-radius: 0.5rem; }
        
        /* Code blocks */
        .prose pre, .wp-block-code { overflow-x: auto !important; max-width: 100%; }
        
        /* Screen Reader Text */
        .screen-reader-text { border: 0; clip: rect(1px, 1px, 1px, 1px); clip-path: inset(50%); height: 1px; margin: -1px; overflow: hidden; padding: 0; position: absolute; width: 1px; word-wrap: normal !important; }
    </style>
    <?php
}
add_action('wp_head', 'zen_custom_styles', 5);

/**
 * 5. SEO: JSON-LD
 */
function zen_json_ld() {
    if (is_singular('post')) {
        global $post;
        $author_id = $post->post_author;
        $payload = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array('@type' => 'Person', 'name' => get_the_author_meta('display_name', $author_id)),
            'publisher' => array('@type' => 'Organization', 'name' => get_bloginfo('name'), 'logo' => array('@type' => 'ImageObject', 'url' => get_avatar_url(get_option('admin_email')))),
            'description' => get_the_excerpt()
        );
        if (has_post_thumbnail()) { $payload['image'] = get_the_post_thumbnail_url($post, 'full'); }
        echo '<script type="application/ld+json">' . json_encode($payload) . '</script>';
    }
}
add_action('wp_head', 'zen_json_ld');

// 移除 emoji
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
        'prev_text' => '<span aria-hidden="true">&larr;</span><span class="screen-reader-text">上一页</span>',
        'next_text' => '<span class="screen-reader-text">下一页</span><span aria-hidden="true">&rarr;</span>',
        'type' => 'array',
    ));
    if (is_array($pages)) {
        echo '<nav class="pt-8 flex justify-center items-center gap-2" aria-label="分页导航">';
        foreach ($pages as $page) {
            if (strpos($page, 'current') !== false) {
                echo "<span class='page-numbers px-3 py-1 border border-transparent bg-gray-900 text-white dark:bg-white dark:text-gray-900 rounded-sm text-sm' aria-current='page'>" . strip_tags($page) . "</span>";
            } else {
                echo str_replace('page-numbers', 'page-numbers px-3 py-1 border border-gray-200 dark:border-gray-700 text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors rounded-sm text-gray-600 dark:text-gray-400', $page);
            }
        }
        echo '</nav>';
    }
}

// 评论回调 (保持 A11y 结构修复，调整间距)
function zen_comment_callback($comment, $args, $depth) {
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body flex gap-4 group relative animate-fade-in">
            <div class="shrink-0">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size'], '', get_comment_author() . ' 的头像', array('class' => 'rounded-full object-cover grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all shadow-sm')); ?>
            </div>
            <div class="flex-grow min-w-0">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white"><?php echo get_comment_author(); ?></h4>
                    <time class="text-xs text-gray-600 dark:text-gray-400 font-sans" datetime="<?php echo get_comment_time('c'); ?>"><?php printf(__('%1$s前', 'zen'), human_time_diff(get_comment_time('U'), current_time('timestamp'))); ?></time>
                </div>
                <!-- 
                    间距修复：
                    1. 内容区域 mb-3 -> mb-2 (缩小底部间距)
                    2. 回复按钮 mt-5 -> mt-1 (缩小顶部间距)
                -->
                <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed prose dark:prose-invert max-w-none break-words mb-2"><?php comment_text(); ?></div>
                <div class="text-xs mt-1">
                    <?php 
                    $reply_args = array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '回复', 'add_below' => 'div-comment', 'aria_label' => '回复给 ' . get_comment_author()));
                    $reply_link = get_comment_reply_link($reply_args);
                    echo str_replace("class='comment-reply-link'", "class='inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 dark:hover:text-white transition-colors cursor-pointer font-medium'", $reply_link);
                    ?>
                </div>
            </div>
        </article>
    <?php
}
?>