<?php
get_header();
?>

<div class="holray-container">
    <div class="holray-unit-archive">
        <h1 class="holray-title"><?php echo __("Our Units", "holray_units"); ?></h1>

        <div class="holray-archive-layout">
            <?php while(have_posts()): the_post(); ?>
                <a href="<?php echo get_permalink(); ?>" class="holray-card">
                    <?php if(get_post_thumbnail_id()): ?>
                        <div class="holray-card-image-container">
                            <img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id(), 'original'); ?>" class="holray-card-image" alt="<?php echo esc_attr(get_post_meta(get_the_ID(), "holray_class", true) . " - " . get_the_title());?>" />
                        </div>
                    <?php else: ?>
                        <div class="holray-card-image-placeholder"></div>
                    <?php endif; ?>
                    <div class="holray-card-inner">
                        <h3><?php echo esc_html(get_the_title()); ?></h3>
                        <div class="holray-card-class"><?php echo esc_html(get_post_meta(get_the_ID(), "holray_class", true)); ?></div>
                        <div class="holray-card-excerpt">
                            <?php echo strip_tags(get_the_content());?>
                        </div>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>