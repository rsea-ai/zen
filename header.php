<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO: Meta Description -->
    <meta name="description" content="<?php 
        if ( is_single() || is_page() ) {
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $post = get_post();
                $excerpt = wp_trim_words($post->post_content, 30);
            }
            echo esc_attr(strip_tags($excerpt)); 
        } elseif ( is_category() || is_tag() ) {
            echo esc_attr(strip_tags(term_description()));
        } else {
            $name = get_bloginfo('name');
            $description = get_bloginfo('description');
            if ( ! empty($description) ) {
                echo esc_attr($name . ' - ' . $description);
            } else {
                echo esc_attr($name);
            }
        }
    ?>">

    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-white text-gray-900 dark:bg-[#121212] dark:text-gray-200 transition-colors duration-300 min-h-screen flex flex-col relative'); ?>>

<!-- A11y: Skip Link -->
<a href="#main-content" class="skip-link">跳至主要内容</a>

<!-- Reading Progress Bar -->
<div id="reading-progress" aria-hidden="true" class="fixed top-0 left-0 h-1 bg-gray-900 dark:bg-white z-50 transition-all duration-100 ease-out w-0"></div>

<!-- Header -->
<header role="banner" class="w-full border-b border-gray-100 dark:border-gray-800 transition-colors bg-white/80 dark:bg-[#121212]/80 backdrop-blur-md sticky top-0 z-40">
    <div class="max-w-zen mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
        
        <!-- Logo / Avatar -->
        <div class="flex items-center gap-4">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="block shrink-0 group" aria-label="<?php bloginfo('name'); ?> - 首页">
                <?php 
                $avatar_url = get_avatar_url(get_option('admin_email'));
                ?>
                <img src="<?php echo esc_url($avatar_url); ?>" 
                     alt="" 
                     class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 dark:ring-gray-700 group-hover:ring-gray-300 dark:group-hover:ring-gray-500 transition-all">
            </a>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-xl font-bold tracking-tight text-gray-900 dark:text-white font-serif hover:opacity-80 transition-opacity">
                <?php bloginfo('name'); ?>
            </a>
        </div>

        <!-- Desktop Navigation -->
        <nav role="navigation" aria-label="主菜单" class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600 dark:text-gray-400">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex items-center gap-6 list-none m-0 p-0',
                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'fallback_cb'    => false,
                'depth'          => 1,
            ));
            ?>
        </nav>

        <!-- Mobile Menu Button -->
        <!-- A11y Fix: 使用 screen-reader-text 明确按钮名称，并加上 aria-expanded -->
        <button id="mobile-menu-btn" 
                class="md:hidden text-gray-900 dark:text-white p-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-400"
                aria-expanded="false" 
                aria-controls="mobile-menu">
            <span class="screen-reader-text">打开/关闭菜单</span>
            <i class="ph ph-list text-2xl" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="hidden md:hidden absolute top-20 left-0 w-full bg-white dark:bg-[#121212] border-b border-gray-100 dark:border-gray-800 p-4 shadow-lg animate-fade-in z-40">
        <nav aria-label="移动端菜单" class="flex flex-col gap-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex flex-col gap-4 list-none m-0 p-0',
                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                'fallback_cb'    => false,
                'depth'          => 1,
            ));
            ?>
        </nav>
    </div>
</header>

<main id="main-content" class="flex-grow w-full max-w-zen mx-auto px-4 sm:px-6 py-10 transition-all relative">