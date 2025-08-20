<?php
if (!defined('ABSPATH')) exit;
/**
 * [holray_search layout="topbar|sidebar" results_page="/availability-results" features_source="api|static" features_static="1,2,3"]
 */
function holray_shortcode_search($atts) {
  $atts = shortcode_atts(['layout'=>'topbar','results_page'=>'','features_source'=>'api','features_static'=>''], $atts, 'holray_search');
  if (empty($atts['results_page'])) return '<p>'.esc_html__('Please configure results_page for [holray_search].','holray-units').'</p>';
  $action = esc_url($atts['results_page']);
  $locations = is_array($terms = get_terms(['taxonomy'=>'unit_location','hide_empty'=>false])) ? $terms : [];
  $features = [];
  if ($atts['features_source']==='api') { $fres = holray_api_features_list(); if (!is_wp_error($fres)) $features=$fres; }
  elseif (!empty($atts['features_static'])) { foreach (array_filter(array_map('absint', explode(',',$atts['features_static']))) as $id) $features[] = ['id'=>$id,'name'=>'Feature '.$id]; }
  ob_start(); $is_top = ($atts['layout']!=='sidebar'); ?>
  <form class="holray-search holray-<?php echo $is_top ? 'topbar':'sidebar'; ?>" action="<?php echo $action; ?>" method="get">
    <div class="holray-search-fields">
      <label><?php esc_html_e('Location','holray-units'); ?>
        <select name="location"><option value=""><?php esc_html_e('Any','holray-units'); ?></option>
        <?php foreach ($locations as $t): ?><option value="<?php echo esc_attr($t->slug); ?>"><?php echo esc_html($t->name); ?></option><?php endforeach; ?>
        </select>
      </label>
      <label><?php esc_html_e('Party Size','holray-units'); ?><input type="number" name="party" min="1" step="1" /></label>
      <label><?php esc_html_e('Features','holray-units'); ?>
        <select name="features[]" multiple size="3">
          <?php foreach ($features as $f): $fid = isset($f['id'])?(int)$f['id']:0; $name = isset($f['name'])?$f['name']:(is_string($f)?$f:'Feature'); ?>
            <option value="<?php echo esc_attr($fid ?: $name); ?>"><?php echo esc_html($name); ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label><?php esc_html_e('Start Date','holray-units'); ?><input type="date" name="fromdt" /></label>
      <label><?php esc_html_e('Duration (nights)','holray-units'); ?><input type="number" name="nights" min="1" step="1" /></label>
      <label><?php esc_html_e('Flexibility','holray-units'); ?>
        <select name="flex"><option value="0"><?php esc_html_e('Exact dates','holray-units'); ?></option><option value="1">±1</option><option value="2">±2</option><option value="3">±3</option><option value="7">±7</option></select>
      </label>
      <button type="submit" class="holray-button"><?php esc_html_e('Search','holray-units'); ?></button>
    </div>
  </form>
  <?php return ob_get_clean();
}
add_shortcode('holray_search', 'holray_shortcode_search');

/** [holray_results] */
function holray_shortcode_results($atts) {
  $atts = shortcode_atts([], $atts, 'holray_results');
  $location_slug = isset($_GET['location'])? sanitize_title(wp_unslash($_GET['location'])):'';
  $party = isset($_GET['party'])? max(0, intval($_GET['party'])):0;
  $features_ids = array_values(array_filter(array_map('absint', isset($_GET['features'])? (array) $_GET['features']: [])));
  $fromdt = isset($_GET['fromdt'])? sanitize_text_field(wp_unslash($_GET['fromdt'])):'';
  $nights = isset($_GET['nights'])? max(1, intval($_GET['nights'])):7;
  $flex   = isset($_GET['flex'])? max(0, intval($_GET['flex'])):0;

  $locid = 0; if ($location_slug) { $term = get_term_by('slug',$location_slug,'unit_location'); if ($term) { $m = get_term_meta($term->term_id,'holray_location_id',true); if ($m) $locid = (int)$m; } }

  $api_args = ['fromdt'=> $fromdt ?: date('Y-m-d'),'nights'=> (string)$nights,'flexibility'=> (string)$flex,'Web'=>1,'allunits'=>1];
  if ($locid) $api_args['location'] = (string)$locid;
  if ($party>0) { $api_args['minparty']=(string)$party; $api_args['maxparty']=(string)$party; }
  if (!empty($features_ids)) $api_args['features'] = $features_ids;

  $cache_key = 'holray_results_' . md5(wp_json_encode($api_args));
  $results = get_transient($cache_key);
  if ($results === false) {
    $res = holray_api_availability($api_args);
    if (is_wp_error($res)) return '<p class="holray-error">'.esc_html($res->get_error_message()).'</p>';
    $results = isset($res['data'])? $res['data']:[];
    set_transient($cache_key, $results, 5*MINUTE_IN_SECONDS);
  }

  $enforce = (int) get_option('holray_units_enforce_online_cta', 1);
  $results = array_values(array_filter($results, function($row) use ($enforce){
    if (empty($row['available'])) return false;
    if ($enforce && empty($row['avonline'])) return false;
    return true;
  }));

  ob_start(); ?>
  <div class="holray-results">
    <div class="holray-results-hdr">
      <h2><?php esc_html_e('Availability Results','holray-units'); ?></h2>
      <p class="holray-results-summary">
        <?php $bits=[];
        if ($location_slug) $bits[] = sprintf(__('Location: %s','holray-units'), esc_html($location_slug));
        if ($party)         $bits[] = sprintf(__('Party: %d','holray-units'), $party);
        if ($fromdt)        $bits[] = sprintf(__('From: %s','holray-units'), esc_html($fromdt));
        if ($nights)        $bits[] = sprintf(__('Nights: %d','holray-units'), $nights);
        if ($flex)          $bits[] = sprintf(__('Flex: ±%d','holray-units'), $flex);
        echo esc_html(implode(' • ', $bits)); ?>
      </p>
    </div>
    <?php if (empty($results)): ?>
      <p><?php esc_html_e('No matching availability. Try adjusting dates or flexibility.','holray-units'); ?></p>
    <?php else: ?>
      <div class="holray-unit-grid">
        <?php foreach ($results as $row):
          $unit = isset($row['unit'])? $row['unit']:[];
          $unit_id = isset($unit['id'])? (string)$unit['id']:'';
          if (!$unit_id) continue;
          $posts = get_posts(['post_type'=>'holray_unit','posts_per_page'=>1,'fields'=>'ids','meta_key'=>'holray_external_id','meta_value'=>$unit_id]);
          if (empty($posts)) continue;
          $pid = $posts[0];
          $title = get_the_title($pid);
          $permalink = get_permalink($pid);
          $class = get_post_meta($pid,'class',true);
          $minb  = (int) get_post_meta($pid,'min_berth',true);
          $maxb  = (int) get_post_meta($pid,'max_berth',true);
          $pets  = get_post_meta($pid,'pets',true);
          $img   = get_the_post_thumbnail($pid,'medium',['loading'=>'lazy']);
          $from = isset($row['fromdt'])? $row['fromdt']:'';
          $to   = isset($row['todt'])?   $row['todt']:'';
          $price = isset($row['price']['totaltopay'])? $row['price']['totaltopay']:'';
          $price_fmt = holray_format_price($price);
          $checkout = holray_checkout_base();
          $cta = ($enforce && empty($row['avonline'])) ? '' : ($checkout? esc_url(add_query_arg(['unitid'=>$unit_id,'startdate'=>$from,'enddate'=>$to], $checkout)) : '');
          ?>
          <article class="holray-unit-card">
            <a href="<?php echo esc_url($permalink); ?>" class="holray-card-media"><?php echo $img; ?></a>
            <h3><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
            <p class="holray-unit-class"><?php echo esc_html($class); ?></p>
            <p class="holray-meta">
              <?php if ($minb || $maxb) echo esc_html(sprintf(__('Berths %d–%d','holray-units'), $minb, $maxb)); ?>
              <?php if ($pets!=='') echo ' • '.esc_html(sprintf(__('Pets %s','holray-units'), $pets)); ?>
            </p>
            <p class="holray-dates"><?php echo esc_html($from.' → '.$to); ?></p>
            <?php if ($price_fmt): ?><p class="holray-price"><?php echo $price_fmt; ?></p><?php endif; ?>
            <?php if ($cta): ?><p><a class="holray-button" href="<?php echo $cta; ?>"><?php esc_html_e('Book now','holray-units'); ?></a></p><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php return ob_get_clean();
}
add_shortcode('holray_results', 'holray_shortcode_results');
