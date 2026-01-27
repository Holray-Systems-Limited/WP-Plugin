<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo __("Settings & Sync", "holray-units"); ?></h1>
    <div class="metabox-holder">
        
        <?php if($holray_url === '' || $api_key === '') : ?>
            <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><?php echo __("API Details Required", "holray-units"); ?></h2>
                </div>
                <div class="inside">
                    <p>
                        <?php echo __("To sync your units, please enter your Holray API URL and your Holray API key.", "holray-units");?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><?php echo __("Sync Units", "holray-units"); ?></h2>
                </div>
                <div class="inside">
                    <form action="admin-post.php" method="POST">
                        <?php echo \Holray\Plugin\Plugin::getInstance()->getActions()["sync_with_holray"]->get_form(); ?>
                        <p>
                            <?php echo __("Click the button below to sync your units, locations and companies from Holray.", "holray-units"); ?>
                        </p>
                        <p>
                            <strong>
                                <?php echo __("Last synced: ", "holray-units"); ?>
                                <?php echo is_null($last_sync) ? __("Never", "holray-units") : $last_sync;?>
                            </strong>
                        </p>
                        <p>
                            <button type="submit" class="button button-primary"><?php echo __('Sync With Holray', 'holray-units'); ?></button>
                        </p>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php echo __("API Settings", "holray-units"); ?></h2>
            </div>
            <div class="inside">
                <form action="admin-post.php" method="POST">
                    <?php echo \Holray\Plugin\Plugin::getInstance()->getActions()["save_api_settings"]->get_form(); ?>
                    <table class="form-table" role="presentation">
                        <tr>
                            <th>
                                <label for="holray_url"><?php echo __('Holray URL', 'holray-units'); ?></label>
                            </th>
                            <td>
                                <input type="url" class="regular-text code" id="holray_url" name="holray_units_holray_url" value="<?php echo $holray_url; ?>" placeholder="https://YOUR.holray.co.uk/" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="holray_units_api_key"><?php echo __('Holray API Key', 'holray-units'); ?></label>
                            </th>
                            <td>
                                <input type="text" class="regular-text" id="holray_units_api_key" name="holray_units_api_key" value="<?php echo $api_key; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo __('Cache API', 'holray-units'); ?></th>
                            <td><label><input type="checkbox" name="holray_units_api_caching" value="1" <?php checked($api_caching, 1); ?> /> <?php echo __('Cache API requests to Holray (Recommended)', 'holray-units'); ?></label></td>
                        </tr>
                    </table>
                    <p>
                        <button type="submit" class="button button-primary"><?php echo __('Save Settings', 'holray-units'); ?></button>
                    </p>
                </form>
            </div>
        </div>

        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php echo __("Pricing", "holray-units"); ?></h2>
            </div>
            <div class="inside">
                <form action="admin-post.php" method="POST">
                    <?php echo \Holray\Plugin\Plugin::getInstance()->getActions()["save_pricing_settings"]->get_form(); ?>
                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row"><label for="holray_units_currency_symbol"><?php echo __('Currency symbol', 'holray-units'); ?></label></th>
                            <td><input type="text" id="holray_units_currency_symbol" name="holray_units_currency_symbol" value="<?php echo esc_attr($sym); ?>" class="regular-text" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="holray_units_currency_position"><?php echo __('Currency position', 'holray-units'); ?></label></th>
                            <td>
                                <select id="holray_units_currency_position" name="holray_units_currency_position">
                                    <option value="left" <?php selected($pos, 'left'); ?>><?php echo __('Left (e.g., £99)', 'holray-units'); ?></option>
                                    <option value="right" <?php selected($pos, 'right'); ?>><?php echo __('Right (e.g., 99€)', 'holray-units'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="holray_units_thousand_sep"><?php echo __('Thousands separator', 'holray-units'); ?></label></th>
                            <td><input type="text" id="holray_units_thousand_sep" name="holray_units_thousand_sep" value="<?php echo esc_attr($tsep); ?>" class="small-text" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="holray_units_decimal_sep"><?php echo __('Decimal separator', 'holray-units'); ?></label></th>
                            <td><input type="text" id="holray_units_decimal_sep" name="holray_units_decimal_sep" value="<?php echo esc_attr($dsep); ?>" class="small-text" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="holray_units_decimals"><?php echo __('Number of decimals', 'holray-units'); ?></label></th>
                            <td><input type="number" id="holray_units_decimals" name="holray_units_decimals" value="<?php echo esc_attr($decs); ?>" class="small-text" min="0" max="4" /></td>
                        </tr>
                    </table>
                    <p>
                        <button type="submit" class="button button-primary" name="save_settings" value="1"><?php echo __('Save Pricing Settings', 'holray-units'); ?></button>
                    </p>
                </form>
            </div>
        </div>

        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php echo __("Search Settings", "holray-units"); ?></h2>
            </div>
            <div class="inside">
                <form action="admin-post.php" method="POST">
                    <?php echo \Holray\Plugin\Plugin::getInstance()->getActions()["save_search_results_settings"]->get_form(); ?>
                    <table class="form-table" role="presentation">
                        <tr>
                            <th>
                                <label for="holray_search_results_page"><?php echo __('Search Results Page', 'holray-units'); ?></label>
                            </th>
                            <td>
                                <?php
                                    wp_dropdown_pages([
                                        "name" => "holray_units_search_results_page",
                                        "id" => "holray_search_results_page",
                                        "selected" => $search_results_page_id,
                                        "show_option_name" => __("Select search results page", "holray_units"),
                                        "option_none_value" => "0",
                                    ]);
                                ?>
                            </td>
                        </tr>


                        <tr>
                            <th>
                                <label for="holray_exclude_locations"><?php echo __('Exclude Locations', 'holray-units'); ?></label>
                                <div style="font-size:12px;margin-top:5px">Click and drag or hold CTRL+Click the locations you would like to exclude.</div>
                            </th>
                            <td>
                                <select multiple id="holray_exclude_locations" name="holray_units_exclude_locations[]" style="height: 200px">
                                    <?php $locations = get_terms([ "taxonomy" => "holray_unit_location", "hide_empty" => false ]); ?>
                                    <?php foreach($locations as $location): ?>
                                        <?php $location_external_id = get_term_meta($location->term_id, 'holray_external_id', true); ?>
                                        <option value="<?php echo $location_external_id; ?>" <?php echo in_array($location_external_id, $excluded_locations) ? "selected" : ""; ?>><?php echo esc_html($location->name); ?></option>
                                        <?php unset($location_external_id); ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <button type="submit" class="button button-primary" name="save_settings" value="1"><?php echo __('Save Search Settings', 'holray-units'); ?></button>
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>