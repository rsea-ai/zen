<?php get_header(); ?>

<header class="mb-12 text-center">
	<h1 class="text-3xl font-bold mb-2 serif"><?php the_archive_title(); ?></h1>
	<div class="text-gray-500"><?php the_archive_description(); ?></div>
</header>

<div class="space-y-12">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<article class="group flex flex-col sm:flex-row items-baseline justify-between border-b border-gray-100 dark:border-gray-800 pb-6 mb-6">
			<h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-0 leading-tight">
				<a href="<?php the_permalink(); ?>" class="hover:underline decoration-1 underline-offset-4 decoration-gray-400">
					<?php the_title(); ?>
				</a>
			</h2>
			<time class="text-sm text-gray-400 shrink-0 font-mono"><?php echo get_the_date('Y-m-d'); ?></time>
		</article>

	<?php endwhile; ?>

		<?php zen_pagination(); ?>

	<?php else : ?>
		<p class="text-center text-gray-500">没有找到相关文章。</p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
