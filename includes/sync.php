<?php
if (!defined('ABSPATH')) { exit; }

function holray_units_admin_menu() {
    add_submenu_page('edit.php?post_type=holray_unit', __('Sync & Settings','holray-units'), __('Sync & Settings','holray-units'), 'manage_options', 'holray-sync', 'holray_units_render_sync_page');
}
add_action('admin_menu', 'holray_units_admin_menu');

function holray_units_render_sync_page() {
    if (!current_user_can('manage_options')) return;

    if (!empty($_POST) && check_admin_referer('holray_units_sync_nonce','holray_units_sync_nonce_field')) {
        if (isset($_POST['holray_units_api_url'])) update_option('holray_units_api_url', esc_url_raw(wp_unslash($_POST['holray_units_api_url'])));
        if (isset($_POST['holray_units_api_base'])) update_option('holray_units_api_base', esc_url_raw(wp_unslash($_POST['holray_units_api_base'])));
        if (isset($_POST['holray_units_api_key'])) update_option('holray_units_api_key', sanitize_text_field(wp_unslash($_POST['holray_units_api_key'])));
        if (isset($_POST['holray_units_checkout_base'])) update_option('holray_units_checkout_base', esc_url_raw(wp_unslash($_POST['holray_units_checkout_base'])));

        // Advanced options
        update_option('holray_units_enforce_online_cta', !empty($_POST['holray_units_enforce_online_cta']) ? 1 : 0);
        if (isset($_POST['holray_units_currency_symbol'])) update_option('holray_units_currency_symbol', sanitize_text_field(wp_unslash($_POST['holray_units_currency_symbol'])));
        if (isset($_POST['holray_units_currency_position'])) update_option('holray_units_currency_position', sanitize_text_field(wp_unslash($_POST['holray_units_currency_position'])));
        if (isset($_POST['holray_units_thousand_sep'])) update_option('holray_units_thousand_sep', sanitize_text_field(wp_unslash($_POST['holray_units_thousand_sep'])));
        if (isset($_POST['holray_units_decimal_sep'])) update_option('holray_units_decimal_sep', sanitize_text_field(wp_unslash($_POST['holray_units_decimal_sep'])));
        if (isset($_POST['holray_units_decimals'])) update_option('holray_units_decimals', max(0, intval($_POST['holray_units_decimals'])));

        if (isset($_POST['run_sync'])) {
            $results = holray_units_sync_units();
            if (is_wp_error($results)) add_settings_error('holray_units','sync_error',$results->get_error_message(),'error');
            else add_settings_error('holray_units','sync_ok', sprintf(__('Sync complete. Imported: %d, Updated: %d.','holray-units'), $results['imported'],$results['updated']),'updated');
        }
        settings_errors('holray_units');
    }

    $api_url  = esc_url(get_option('holray_units_api_url',''));
    $api_base = esc_url(get_option('holray_units_api_base',''));
    $api_key  = esc_attr(get_option('holray_units_api_key',''));
    $checkout = esc_url(get_option('holray_units_checkout_base',''));
    $enforce  = (int) get_option('holray_units_enforce_online_cta', 1);
    $sym      = get_option('holray_units_currency_symbol', '£');
    $pos      = get_option('holray_units_currency_position', 'left');
    $tsep     = get_option('holray_units_thousand_sep', ',');
    $dsep     = get_option('holray_units_decimal_sep', '.');
    $decs     = (int) get_option('holray_units_decimals', 2);
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Holray Units — Sync & Settings','holray-units'); ?></h1>
        <form method="post">
            <?php wp_nonce_field('holray_units_sync_nonce','holray_units_sync_nonce_field'); ?>
            <h2><?php esc_html_e('Holray API Settings','holray-units'); ?></h2>
            <table class="form-table" role="presentation">
                <tr><th><label for="holray_units_api_base"><?php esc_html_e('Holray API Base (api.php URL)','holray-units'); ?></label></th>
                    <td><input type="url" class="regular-text code" id="holray_units_api_base" name="holray_units_api_base" value="<?php echo $api_base; ?>" placeholder="https://YOUR.holray.co.uk/public/api.php" /></td></tr>
                <tr><th><label for="holray_units_api_key"><?php esc_html_e('Holray API Key','holray-units'); ?></label></th>
                    <td><input type="text" class="regular-text" id="holray_units_api_key" name="holray_units_api_key" value="<?php echo $api_key; ?>" /></td></tr>
                <tr><th><label for="holray_units_checkout_base"><?php esc_html_e('Checkout URL (optional)','holray-units'); ?></label></th>
                    <td><input type="url" class="regular-text code" id="holray_units_checkout_base" name="holray_units_checkout_base" value="<?php echo $checkout; ?>" placeholder="https://YOUR.holray.co.uk/public/custbook1.php" />
                        <p class="description"><?php esc_html_e('If blank, the plugin will infer this from the API base URL.','holray-units'); ?></p></td></tr>
            </table>

            <h2><?php esc_html_e('Availability & Pricing','holray-units'); ?></h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><?php esc_html_e('Only show “Book now” when online (avonline)', 'holray-units'); ?></th>
                    <td><label><input type="checkbox" name="holray_units_enforce_online_cta" value="1" <?php checked($enforce, 1); ?>/> <?php esc_html_e('Hide the CTA if a result is not online-bookable.', 'holray-units'); ?></label></td>
                </tr>
                <tr>
                    <th scope="row"><label for="holray_units_currency_symbol"><?php esc_html_e('Currency symbol', 'holray-units'); ?></label></th>
                    <td><input type="text" id="holray_units_currency_symbol" name="holray_units_currency_symbol" value="<?php echo esc_attr($sym); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="holray_units_currency_position"><?php esc_html_e('Currency position', 'holray-units'); ?></label></th>
                    <td>
                        <select id="holray_units_currency_position" name="holray_units_currency_position">
                            <option value="left" <?php selected($pos, 'left'); ?>><?php esc_html_e('Left (e.g., £99)', 'holray-units'); ?></option>
                            <option value="right" <?php selected($pos, 'right'); ?>><?php esc_html_e('Right (e.g., 99€)', 'holray-units'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="holray_units_thousand_sep"><?php esc_html_e('Thousands separator', 'holray-units'); ?></label></th>
                    <td><input type="text" id="holray_units_thousand_sep" name="holray_units_thousand_sep" value="<?php echo esc_attr($tsep); ?>" class="small-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="holray_units_decimal_sep"><?php esc_html_e('Decimal separator', 'holray-units'); ?></label></th>
                    <td><input type="text" id="holray_units_decimal_sep" name="holray_units_decimal_sep" value="<?php echo esc_attr($dsep); ?>" class="small-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="holray_units_decimals"><?php esc_html_e('Number of decimals', 'holray-units'); ?></label></th>
                    <td><input type="number" id="holray_units_decimals" name="holray_units_decimals" value="<?php echo esc_attr($decs); ?>" class="small-text" min="0" max="4" /></td>
                </tr>
            </table>

            <h2><?php esc_html_e('Legacy Demo Import (optional)','holray-units'); ?></h2>
            <table class="form-table" role="presentation">
                <tr><th><label for="holray_units_api_url"><?php esc_html_e('Legacy Units JSON URL (demo)','holray-units'); ?></label></th>
                    <td><input type="url" class="regular-text code" id="holray_units_api_url" name="holray_units_api_url" value="<?php echo $api_url; ?>" placeholder="https://example.com/api/units" />
                        <p class="description"><?php esc_html_e('If set, sync will import from this URL. If blank, sync uses the Holray API.', 'holray-units'); ?></p></td></tr>
            </table>

            <p>
                <button type="submit" class="button button-primary" name="run_sync" value="1"><?php esc_html_e('Run Sync','holray-units'); ?></button>
                <button type="submit" class="button" name="save_settings" value="1"><?php esc_html_e('Save Settings','holray-units'); ?></button>
            </p>
        </form>
    </div>
    <?php
}

function holray_units_sync_units() {
    require_once ABSPATH.'wp-admin/includes/image.php';
    require_once ABSPATH.'wp-admin/includes/file.php';
    require_once ABSPATH.'wp-admin/includes/media.php';

    // Back-compat path: legacy demo JSON if provided
    $legacy_url = get_option('holray_units_api_url','');
    if (!empty($legacy_url)) {
        $resp = wp_remote_get($legacy_url, ['timeout'=>20]);
        if (is_wp_error($resp)) return $resp;
        $code = wp_remote_retrieve_response_code($resp);
        if ($code < 200 || $code > 299) return new WP_Error('bad_status', sprintf(__('API returned HTTP %d','holray-units'), $code));
        $data = json_decode(wp_remote_retrieve_body($resp), true);
        if (!is_array($data)) return new WP_Error('bad_json', __('Invalid JSON from API.','holray-units'));
        return holray_units__import_array($data);
    }

    // Preferred: Holray API v2 "units" service
    if (!function_exists('holray_api_post')) {
        return new WP_Error('no_api', __('Holray API client missing.', 'holray-units'));
    }

    $res = holray_api_post('units', [
        'Web'      => 1,
        'allunits' => 1,
    ]);
    if (is_wp_error($res)) return $res;
    $items = isset($res['data']) && is_array($res['data']) ? $res['data'] : [];
    if (!$items) return ['imported'=>0, 'updated'=>0];

    // Normalize to importer array
    $normalized = array_map(function($u){
        $id          = isset($u['id']) ? (string)$u['id'] : '';
        $title       = isset($u['name']) ? $u['name'] : (isset($u['title']) ? $u['title'] : $id);
        $class       = isset($u['class']) ? $u['class'] : '';
        $max_berth   = isset($u['berths']) ? (int)$u['berths'] : (isset($u['maxberth']) ? (int)$u['maxberth'] : 0);
        $min_berth   = isset($u['minberth']) ? (int)$u['minberth'] : 0;
        $pets        = isset($u['pets']) ? $u['pets'] : '';
        $min_nights  = isset($u['minnights']) ? (int)$u['minnights'] : 0;
        $img_url     = '';
        if (!empty($u['images']) && is_array($u['images'])) {
            $first = reset($u['images']);
            if (is_array($first)) {
                $img_url = isset($first['url']) ? $first['url'] : (isset($first['src']) ? $first['src'] : '');
            }
        } elseif (!empty($u['image'])) {
            $img_url = is_array($u['image']) ? (isset($u['image']['url']) ? $u['image']['url'] : '') : $u['image'];
        }
        $loc_name = ''; $loc_slug = ''; $loc_id = 0;
        if (!empty($u['location']) && is_array($u['location'])) {
            $loc_name = isset($u['location']['name']) ? $u['location']['name'] : '';
            $loc_slug = sanitize_title($loc_name ?: (isset($u['location']['slug']) ? $u['location']['slug'] : ''));
            $loc_id   = isset($u['location']['id']) ? (int)$u['location']['id'] : 0;
        } elseif (!empty($u['locationname'])) {
            $loc_name = $u['locationname'];
            $loc_slug = sanitize_title($loc_name);
            $loc_id   = isset($u['locationid']) ? (int)$u['locationid'] : 0;
        }

        return [
            'external_id'         => $id,
            'title'               => $title,
            'content'             => isset($u['description']) ? $u['description'] : '',
            'class'               => $class,
            'min_berth'           => $min_berth,
            'max_berth'           => $max_berth,
            'pets'                => $pets,
            'min_nights'          => $min_nights,
            'external_booking_url'=> '',
            'image_url'           => $img_url,
            'location'            => $loc_name,
            'location_slug'       => $loc_slug,
            'holray_location_id'  => $loc_id,
        ];
    }, $items);

    return holray_units__import_array($normalized);
}

/** Shared importer */
function holray_units__import_array(array $items) {
    $imported = 0; $updated = 0;

    foreach ($items as $item) {
        $external_id = isset($item['external_id']) ? sanitize_text_field($item['external_id']) : '';
        if (empty($external_id)) continue;

        $existing = get_posts([
            'post_type' => 'holray_unit',
            'meta_key'  => 'holray_external_id',
            'meta_value'=> $external_id,
            'posts_per_page' => 1,
            'fields' => 'ids'
        ]);

        $postarr = [
            'post_title'   => isset($item['title']) ? wp_strip_all_tags($item['title']) : ('Unit ' . $external_id),
            'post_content' => isset($item['content']) ? wp_kses_post($item['content']) : '',
            'post_status'  => 'publish',
            'post_type'    => 'holray_unit',
        ];

        if (!empty($existing)) {
            $postarr['ID'] = (int) $existing[0];
            $pid = wp_update_post($postarr, true);
            if (is_wp_error($pid)) return $pid;
            $updated++;
        } else {
            $pid = wp_insert_post($postarr, true);
            if (is_wp_error($pid)) return $pid;
            $imported++;
            update_post_meta($pid, 'holray_external_id', $external_id);
        }

        foreach (['class','min_berth','max_berth','pets','min_nights','external_booking_url'] as $k) {
            if (isset($item[$k])) update_post_meta($pid, $k, is_string($item[$k]) ? sanitize_text_field($item[$k]) : $item[$k]);
        }

        $loc_name = isset($item['location']) ? sanitize_text_field($item['location']) : '';
        $loc_slug = isset($item['location_slug']) ? sanitize_title($item['location_slug']) : ($loc_name ? sanitize_title($loc_name) : '');
        $loc_id   = isset($item['holray_location_id']) ? (int)$item['holray_location_id'] : 0;

        if ($loc_name) {
            $term = term_exists($loc_slug, 'unit_location');
            if (!$term) $term = wp_insert_term($loc_name, 'unit_location', ['slug' => $loc_slug]);
            if (!is_wp_error($term)) {
                $tid = is_array($term) ? (int)$term['term_id'] : (int)$term;
                wp_set_object_terms($pid, $tid, 'unit_location', false);
                if ($loc_id) update_term_meta($tid, 'holray_location_id', $loc_id);
            }
        }

        if (!empty($item['image_url'])) {
            $img_id = media_sideload_image(esc_url_raw($item['image_url']), $pid, null, 'id');
            if (!is_wp_error($img_id)) set_post_thumbnail($pid, (int)$img_id);
        }
    }

    return ['imported' => $imported, 'updated' => $updated];
}
