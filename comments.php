<?php
if (post_password_required()) return;
?>

<section id="comments" class="mt-20 pt-10 border-t border-gray-100 dark:border-gray-800 max-w-zen mx-auto">
    
    <div class="flex items-center justify-between mb-10">
        <!-- 标题层级修复：H2 -->
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white serif">
            <?php
            $comments_number = get_comments_number();
            if ( $comments_number === 0 ) {
                echo '评论';
            } else {
                echo $comments_number . ' 条评论';
            }
            ?>
        </h2>
    </div>

    <!-- 评论表单 -->
    <div class="mb-8">
        <?php
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ($req ? " aria-required='true'" : '');
        
        $fields = array(
            'author' => '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">' .
                        '<div><label for="author" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wider">称呼 <span class="text-red-500">*</span></label>' .
                        '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" ' . $aria_req . ' class="w-full bg-gray-50 dark:bg-gray-800 border-0 border-b-2 border-gray-200 dark:border-gray-700 focus:border-gray-900 dark:focus:border-white focus:ring-0 py-3 px-4 text-sm transition-colors placeholder-gray-400 rounded-t-md" /></div>',
            
            'email'  => '<div><label for="email" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wider">邮箱 <span class="text-red-500">*</span></label>' .
                        '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" ' . $aria_req . ' class="w-full bg-gray-50 dark:bg-gray-800 border-0 border-b-2 border-gray-200 dark:border-gray-700 focus:border-gray-900 dark:focus:border-white focus:ring-0 py-3 px-4 text-sm transition-colors placeholder-gray-400 rounded-t-md" /></div></div>',
            
            'cookies' => '', 
        );

        $args = array(
            'fields' => $fields,
            'comment_field' => '<div class="mb-6"><label for="comment" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wider">你的想法 <span class="text-red-500">*</span></label><textarea id="comment" name="comment" rows="4" class="w-full bg-gray-50 dark:bg-gray-800 border-0 border-b-2 border-gray-200 dark:border-gray-700 focus:border-gray-900 dark:focus:border-white focus:ring-0 p-4 text-sm transition-colors resize-y placeholder-gray-400 rounded-t-md" aria-required="true"></textarea></div>',
            'class_submit' => 'px-6 py-2 rounded text-sm font-bold tracking-wide shadow-sm', 
            'label_submit' => '发表评论',
            'title_reply' => '',
            'title_reply_to' => '回复给 %s',
            'cancel_reply_link' => '取消',
            'comment_notes_before' => '',
            'logged_in_as' => '<p class="text-xs text-gray-600 mb-6">已登录为 <a href="' . admin_url( 'profile.php' ) . '" class="underline font-medium text-gray-900 dark:text-white">' . $user_identity . '</a>。 <a href="' . wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) . '" class="text-red-500 hover:underline">注销?</a></p>',
            'class_form' => 'comment-form animate-fade-in',
        );

        comment_form($args);
        ?>
    </div>

    <!-- 评论列表 -->
    <div class="comment-list-wrapper">
        <?php if ( have_comments() ) : ?>
            <!-- 结构修复：确保 ol 只包含 li (由 wp_list_comments 生成) -->
            <ol class="comment-list">
                <?php
                wp_list_comments(array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 48,
                    'callback'    => 'zen_comment_callback'
                ));
                ?>
            </ol>
            
            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
            <nav class="mt-12 flex justify-between text-sm font-medium border-t border-gray-100 dark:border-gray-800 pt-8" aria-label="评论分页">
                <div class="nav-previous"><?php previous_comments_link( '&larr; 较早的评论' ); ?></div>
                <div class="nav-next"><?php next_comments_link( '较新的评论 &rarr;' ); ?></div>
            </nav>
            <?php endif; ?>
            
        <?php else : ?>
            <?php if ( ! comments_open() ) : ?>
                <p class="text-sm text-gray-600 italic text-center py-8">评论已关闭。</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</section>