<?php
get_header(); ?>
<main class="holray-archive container">
  <h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
  <?php if (have_posts()): ?>
    <div class="holray-unit-grid">
      <?php while (have_posts()): the_post(); ?>
        <article <?php post_class('holray-unit-card'); ?>>
          <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) the_post_thumbnail('medium'); ?>
            <h2><?php the_title(); ?></h2>
            <?php $cls = get_post_meta(get_the_ID(), 'class', true); if ($cls): ?>
              <p class="holray-unit-class"><?php echo esc_html($cls); ?></p>
            <?php endif; ?>
            <p class="holray-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
          </a>
        </article>
      <?php endwhile; ?>
    </div>
    <div class="holray-pagination"><?php the_posts_pagination(); ?></div>
  <?php else: ?>
    <p><?php esc_html_e('No units available.','holray-units'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
