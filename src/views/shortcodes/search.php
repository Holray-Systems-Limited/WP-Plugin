<div class="holray-searchform holray-placement-<?php echo esc_attr($args["placement"]); ?>">
    <div class="holray-searchform-inner">
        <form action="<?php echo esc_url($args["results_page"]); ?>" method="GET">
            <div class="holray-fromgroup">
                <label for="holray_location"><?php echo __("Location", "holray_units"); ?></label>
                <select name="location" id="holray_location" class="holray-formcontrol" aria-label="Location" required>
                    <option selected disabled><?php echo __("Select location", "holray_units"); ?></option>
                    <?php foreach($locations as $location): ?>
                        <?php $location_external_id = get_term_meta($location->term_id, 'holray_external_id', true); ?>
                        <option value="<?php echo $location_external_id; ?>" <?php echo $values["location"] == $location_external_id ? "selected" : ""; ?>><?php echo esc_html($location->name); ?></option>
                        <?php unset($location_external_id); ?>
                    <?php endforeach; ?>
                </select>
            </div>
    
            <div class="holray-fromgroup">
                <label for="holray_partysize"><?php echo __("Party size", "holray_units"); ?></label>
                <input type="number" name="partysize" id="holray_partysize" class="holray-formcontrol" min="1" step="1" value="<?php echo esc_attr($values["partysize"]); ?>" required />
            </div>
    
            <div class="holray-fromgroup">
                <label for="holray_features"><?php echo __("Features", "holray_units"); ?></label>
                <select name="features[]" id="holray_features" class="holray-formcontrol" aria-label="Features" multiple>
                    <?php foreach($features->data->features as $feature): ?>
                        <option value="<?php echo $feature->id; ?>" <?php echo in_array($feature->id, $values["features"]) ? "selected" : ""; ?>><?php echo esc_html($feature->feature); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
    
            <div class="holray-fromgroup">
                <label for="holray_fromdt"><?php echo __("Start date", "holray_units"); ?></label>
                <input type="date" name="fromdt" id="holray_fromdt" class="holray-formcontrol" min="<?php echo date("Y-m-d", strtotime("tomorrow")); ?>" value="<?php echo esc_attr($values["fromdt"]); ?>" />
            </div>
    
            <div class="holray-fromgroup">
                <label for="holray_nights"><?php echo __("Nights", "holray_units"); ?></label>
                <input type="number" name="nights" id="holray_nights" class="holray-formcontrol" min="1" step="1" required value="<?php echo esc_attr($values["nights"]); ?>" />
            </div>
    
            <div class="holray-fromgroup">
                <label for="holray_flex"><?php echo __("Flex nights", "holray_units"); ?></label>
                <select name="flex" id="holray_flex" class="holray-formcontrol" aria-label="Flexible on nights?">
                    <option value=""  <?php echo $values["flex"] == "" ? "selected" : ""; ?>><?php echo __("Exact Dates", "holray_units"); ?></option>
                    <option value="3" <?php echo $values["flex"] == 3 ? "selected" : ""; ?>><?php echo vsprintf(__("+/- %s nights", "holray_units"), [ '3' ]); ?></option>
                    <option value="5" <?php echo $values["flex"] == 5 ? "selected" : ""; ?>><?php echo vsprintf(__("+/- %s nights", "holray_units"), [ '5' ]); ?></option>
                    <option value="7" <?php echo $values["flex"] == 7 ? "selected" : ""; ?>><?php echo vsprintf(__("+/- %s nights", "holray_units"), [ '7' ]); ?></option>
                </select>
            </div>
    
            <div class="holray-formgroup holray-btnformgroup">
                <button type="submit" class="holray-btn holray-btn-primary"><?php echo __("Search", "holray_units"); ?></button>
            </div>
    
        </form>
    </div>
    <?php if($args["placement"] == "topbar"): ?>
        <div class="holray-searchform-mobile">
            <button type="button" class="holray-btn holray-btn-primary"><?php echo __("Find my holiday", "holray_units"); ?></button>
        </div>
    <?php endif; ?>
</div>