<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo __("Sync Holray Units", "holray-units"); ?></h1>

    <div id="message" class="notice notice-error">
        <p><?php echo __("You need to enter your Holray URL and Holray API Key to be able to sync units with your WordPress site.", "holray-units"); ?></p>
    </div>

    <div class="metabox-holder">

        <div class="postbox">
            <div class="postbox-header">
                <h2 class="hndle"><?php echo __("API Settings", "holray-units"); ?></h2>
            </div>
            <div class="inside">
                <form action="admin-post.php" method="POST">
                    <?php echo \Holray\Plugin\Plugin::getInstance()->getActions()["save_api_settings"]->get_form(); ?>
                    <table class="form-table form-invalid" role="presentation">
                        <tr>
                            <th>
                                <label for="holray_url"><?php esc_html_e('Holray URL', 'holray-units'); ?></label>
                            </th>
                            <td>
                                <input type="url" class="regular-text code form-required" id="holray_url" name="holray_units_holray_url" value="<?php echo $holray_url; ?>" placeholder="https://YOUR.holray.co.uk/" />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="holray_units_api_key"><?php esc_html_e('Holray API Key', 'holray-units'); ?></label>
                            </th>
                            <td>
                                <input type="text" class="regular-text form-required" id="holray_units_api_key" name="holray_units_api_key" value="<?php echo $api_key; ?>" />
                            </td>
                        </tr>
                    </table>
                    <p>
                        <button type="submit" class="button button-primary"><?php esc_html_e('Save Settings', 'holray-units'); ?></button>
                    </p>
                </form>
            </div>
        </div>

    </div>

</div>