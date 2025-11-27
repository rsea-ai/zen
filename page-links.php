<?php
/*
Template Name: Links Template
*/
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<header class="mb-10 text-center">
		<h1 class="text-3xl font-bold mb-4 serif"><?php the_title(); ?></h1>
		<div class="text-gray-500 dark:text-gray-400 max-w-lg mx-auto">
			<?php the_content(); ?>
		</div>
	</header>

	<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-zen mx-auto">
		<?php
		// 获取所有链接分类
		$bookmarks = get_bookmarks(array(
			'orderby' => 'name',
			'order'   => 'ASC'
		));

		if ($bookmarks) {
			foreach ($bookmarks as $bookmark) {
				?>
				<a href="<?php echo esc_url($bookmark->link_url); ?>" target="<?php echo esc_attr($bookmark->link_target); ?>" class="group flex items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 hover:shadow-sm transition-all bg-white dark:bg-[#1a1a1a]">
					<?php if ($bookmark->link_image) : ?>
						<img src="<?php echo esc_url($bookmark->link_image); ?>" alt="<?php echo esc_attr($bookmark->link_name); ?>" class="w-14 h-14 rounded-full object-cover mr-4 grayscale group-hover:grayscale-0 transition-all duration-300">
					<?php else : ?>
						<div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xl font-serif text-gray-400 mr-4">
							<?php echo mb_substr($bookmark->link_name, 0, 1); ?>
						</div>
					<?php endif; ?>

					<div class="flex-grow min-w-0">
						<h3 class="font-bold text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
							<?php echo esc_html($bookmark->link_name); ?>
						</h3>
						<p class="text-sm text-gray-500 truncate"><?php echo esc_html($bookmark->link_description); ?></p>
					</div>
					<i class="ph ph-arrow-up-right text-gray-300 group-hover:text-gray-600 dark:text-gray-600 dark:group-hover:text-gray-300 transition-colors"></i>
				</a>
				<?php
			}
		} else {
			echo '<p class="text-center w-full text-gray-500">暂无友链，请在后台添加。</p>';
		}
		?>
	</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>