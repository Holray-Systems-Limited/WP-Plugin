<?php
get_header(); ?>
<main class="holray-unit-single container">
<?php while (have_posts()) : the_post();
  $id = get_the_ID();
  $class = esc_html(get_post_meta($id, 'class', true));
  $berths_min = intval(get_post_meta($id, 'min_berth', true));
  $berths_max = intval(get_post_meta($id, 'max_berth', true));
  $pets = get_post_meta($id, 'pets', true);
  $min_nights = intval(get_post_meta($id, 'min_nights', true));
  $book_url = esc_url(get_post_meta($id, 'external_booking_url', true));
  $terms = get_the_terms($id, 'unit_location');
  $location_names = $terms && !is_wp_error($terms) ? wp_list_pluck($terms, 'name') : [];
?>
  <article <?php post_class('holray-unit'); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php if ($location_names): ?><p class="holray-unit-locations"><?php echo esc_html(implode(', ', $location_names)); ?></p><?php endif; ?>
    </header>
    <div class="holray-unit-media"><?php if (has_post_thumbnail()) { the_post_thumbnail('large'); } ?></div>
    <div class="holray-unit-meta">
      <?php if ($class) echo '<p><strong>'.esc_html__('Class:','holray-units').'</strong> ' . $class . '</p>'; ?>
      <?php if ($berths_min || $berths_max) echo '<p><strong>'.esc_html__('Berths:','holray-units').'</strong> ' . intval($berths_min) . '&ndash;' . intval($berths_max) . '</p>'; ?>
      <?php if ($pets !== '') echo '<p><strong>'.esc_html__('Pets:','holray-units').'</strong> ' . esc_html($pets) . '</p>'; ?>
      <?php if ($min_nights) echo '<p><strong>'.esc_html__('Min nights:','holray-units').'</strong> ' . intval($min_nights) . '</p>'; ?>
    </div>
    <div class="entry-content"><?php the_content(); ?></div>
    <?php if ($book_url): ?><p><a class="button holray-button" href="<?php echo $book_url; ?>"><?php esc_html_e('Book now','holray-units'); ?></a></p><?php endif; ?>
  </article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
