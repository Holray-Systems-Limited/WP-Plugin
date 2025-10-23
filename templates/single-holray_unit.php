<?php
get_header();
?>

<div class="holray-container">
    <div class="holray-unit-single">
        <?php if(get_post_thumbnail_id()): ?>
            <div class="holray-unit-image-container">
                <img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id(), 'original'); ?>" class="holray-unit-image" alt="<?php echo esc_attr(get_post_meta(get_the_ID(), "holray_class", true) . " - " . get_the_title());?>" />
            </div>
        <?php endif; ?>

        <div class="holray-unit-details">
            <div class="holray-unit-header">
                <h1 class="holray-unit-title"><?php echo esc_html(get_post_meta(get_the_ID(), "holray_class", true) . " " . get_the_title()); ?></h1>

                <?php if(get_post_meta(get_the_ID(), "holray_external_booking_url", true)): ?>
                    <div class="holray-unit-btn-wrapper holray-unit-btn-desktop">
                        <a href="<?php echo esc_url(get_post_meta(get_the_ID(), "holray_external_booking_url", true)); ?>" class="holray-unit-btn holray-unit-btn-primary"><?php echo __("Check Availability", "holray-units"); ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <?php $locations = get_the_terms(get_the_ID(), "holray_unit_location"); ?>
            <?php if(count($locations) > 0): ?>
                <ul class="holray-unit-locations">
                    <?php foreach($locations as $location): ?>
                        <li class="holray-unit-location"><?php echo esc_html($location->name); ?></li>     
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="holray-unit-features">
                <?php if(get_post_meta(get_the_ID(), "holray_class", true)): ?>
                    <div class="holray-unit-feature">
                        <div class="holray-unit-feature--name"><?php echo __("Class", "holray-units"); ?></div>
                        <div class="holray-unit-feature--value"><?php echo esc_html(get_post_meta(get_the_ID(), "holray_class", true)); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if(get_post_meta(get_the_ID(), "holray_layout", true)): ?>
                    <div class="holray-unit-feature">
                        <div class="holray-unit-feature--name"><?php echo __("Layout", "holray-units"); ?></div>
                        <div class="holray-unit-feature--value"><?php echo esc_html(get_post_meta(get_the_ID(), "holray_layout", true)); ?></div>
                    </div>
                <?php endif; ?>

                <?php if(get_post_meta(get_the_ID(), "holray_min_berth", true)): ?>
                    <div class="holray-unit-feature">
                        <div class="holray-unit-feature--name"><?php echo __("Min Berths", "holray-units"); ?></div>
                        <div class="holray-unit-feature--value"><?php echo esc_html(get_post_meta(get_the_ID(), "holray_min_berth", true)); ?></div>
                    </div>
                <?php endif; ?>

                <?php if(get_post_meta(get_the_ID(), "holray_max_berth", true)): ?>
                    <div class="holray-unit-feature">
                        <div class="holray-unit-feature--name"><?php echo __("Max Berths", "holray-units"); ?></div>
                        <div class="holray-unit-feature--value"><?php echo esc_html(get_post_meta(get_the_ID(), "holray_max_berth", true)); ?></div>
                    </div>
                <?php endif; ?>

                <?php if(get_post_meta(get_the_ID(), "holray_max_pets", true)): ?>
                    <div class="holray-unit-feature">
                        <div class="holray-unit-feature--name"><?php echo __("Max Pets", "holray-units"); ?></div>
                        <div class="holray-unit-feature--value"><?php echo esc_html(get_post_meta(get_the_ID(), "holray_max_pets", true)); ?></div>
                    </div>
                <?php endif; ?>

                <?php if(get_post_meta(get_the_ID(), "holray_min_nights", true)): ?>
                    <div class="holray-unit-feature">
                        <div class="holray-unit-feature--name"><?php echo __("Min Nights", "holray-units"); ?></div>
                        <div class="holray-unit-feature--value"><?php echo esc_html(get_post_meta(get_the_ID(), "holray_min_nights", true)); ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if(get_post_meta(get_the_ID(), "holray_external_booking_url", true)): ?>
                <div class="holray-unit-btn-wrapper holray-unit-btn-mobile">
                    <a href="<?php echo esc_url(get_post_meta(get_the_ID(), "holray_external_booking_url", true)); ?>" class="holray-unit-btn holray-unit-btn-primary"><?php echo __("Check Availability", "holray-units"); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>