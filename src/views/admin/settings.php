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
                            <button type="submit" class="button button-primary"><?php esc_html_e('Sync With Holray', 'holray-units'); ?></button>
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
                                <label for="holray_url"><?php esc_html_e('Holray URL', 'holray-units'); ?></label>
                            </th>
                            <td>
                                <input type="url" class="regular-text code" id="holray_url" name="holray_units_holray_url" value="<?php echo $holray_url; ?>" placeholder="https://YOUR.holray.co.uk/" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="holray_units_api_key"><?php esc_html_e('Holray API Key', 'holray-units'); ?></label>
                            </th>
                            <td>
                                <input type="text" class="regular-text" id="holray_units_api_key" name="holray_units_api_key" value="<?php echo $api_key; ?>" />
                            </td>
                        </tr>
                    </table>
                    <p>
                        <button type="submit" class="button button-primary"><?php esc_html_e('Save Settings', 'holray-units'); ?></button>
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
                            <th scope="row"><?php esc_html_e('Only show “Book now” when online (avonline)', 'holray-units'); ?></th>
                            <td><label><input type="checkbox" name="holray_units_enforce_online_cta" value="1" <?php checked($enforce, 1); ?> /> <?php esc_html_e('Hide the CTA if a result is not online-bookable.', 'holray-units'); ?></label></td>
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
                    <p>
                        <button type="submit" class="button button-primary" name="save_settings" value="1"><?php esc_html_e('Save Pricing Settings', 'holray-units'); ?></button>
                    </p>
                </form>
            </div>
        </div>

        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php echo __("Legacy Demo Import (optional)", "holray-units"); ?></h2>
            </div>
            <div class="inside">
                <form action="admin-post.php" method="POST">
                    <?php echo \Holray\Plugin\Plugin::getInstance()->getActions()["save_legacy_settings"]->get_form(); ?>
                    <table class="form-table" role="presentation">
                        <tr>
                            <th>
                                <label for="holray_units_api_url"><?php esc_html_e('Legacy Units JSON URL (demo)', 'holray-units'); ?></label>
                            </th>
                            <td>
                                <input type="url" class="regular-text code" id="holray_units_api_url" name="holray_units_api_url" value="<?php echo $api_url; ?>" placeholder="https://example.com/api/units" />
                                <p class="description"><?php esc_html_e('If set, sync will import from this URL. If blank, sync uses the Holray API.', 'holray-units'); ?></p>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <button type="submit" class="button button-primary" name="save_settings" value="1"><?php esc_html_e('Save Legacy Settings', 'holray-units'); ?></button>
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>