<?php
get_header();

$search = \Holray\Plugin\Services\SearchResultsService::do_search();
/**
 * Is there an error with the search?
 * @var boolean
 */
$hasError = $search["hasError"];
/**
 * Error messages
 * @var array<int, string>
 */
$errors = $search["errors"];
/**
 * An array of all the units directly from the Holray API
 * @var array<int, mixed>
 */
$units = $search["units"];
/**
 * All fields and their values
 * @var array<int, string>
 */
$fields = $search["fields"];
?>

<main>
    <div class="container">
        <?php the_content(); ?>
    </div>
    <section id="search-results">
        <div class="holray-container">
            <div class="holray-unit-results">
                <h1 class="holray-title"><?php echo __("Available Units", "holray_units"); ?></h1>
        
                <div class="holray-results-layout">
                    <?php do_action('holray_results_before_render', $fields); ?>
                    <?php if($hasError): ?>
                        <div class="holray-no-units">
                            <h2><?php echo __("Failed to search", "holray_units"); ?></h2>
                            <div><?php echo __("The following errors occured when searching for availablity:", "holray_units"); ?></div>
                            <ul>
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo esc_html($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php elseif (count($units) < 1): ?>
                        <div class="holray-no-units">
                            <h2><?php echo __("There's no availablity", "holray_units"); ?></h2>
                            <div style="margin-bottom: 10px"><?php echo __("Sadly there are no available units for your chosen dates, please try adjusting your search and trying again.", "holray_units"); ?></div>
                            <?php echo do_shortcode("[holray_search]"); ?>
                        </div>
                    <?php else: ?>
                            <?php foreach($units as $result): ?>
                
                                <div class="holray-card holray-result-card">
                                    <?php if(get_post_thumbnail_id($result["wp_unit"]->ID)): ?>
                                        <div class="holray-card-image-container">
                                            <img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($result["wp_unit"]->ID), 'original'); ?>" class="holray-card-image" alt="<?php echo esc_attr($result["meta"]["class"] . " - " . get_the_title($result["wp_unit"]->ID));?>" />
                                        </div>
                                    <?php else: ?>
                                        <div class="holray-card-image-placeholder"></div>
                                    <?php endif; ?>
                                    <div class="holray-card-inner">
                                        <div class="holray-card-class"><?php echo esc_html($result["meta"]["class"]); ?></div>
                                        <h3><?php echo esc_html(get_the_title($result["wp_unit"]->ID)); ?></h3>
                
                                        <div class="holray-card-icon-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                                            </svg>
                                            <span>
                                                <?php echo __("Min berths", "holray_units"); ?> <?php echo esc_html($result["meta"]["min_berth"]); ?>
                                                &bull;
                                                <?php echo __("Max berths", "holray_units"); ?> <?php echo esc_html($result["meta"]["max_berth"]); ?>
                                            </span>
                                        </div>
                
                                        <div class="holray-card-icon-info">
                                            <svg width="16" height="16" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                                                <g transform="matrix(0.025,0,0,0.025,0,24)">
                                                    <path stroke="currentColor" d="M180,-475C152,-475 128.333,-484.667 109,-504C89.667,-523.333 80,-547 80,-575C80,-603 89.667,-626.667 109,-646C128.333,-665.333 152,-675 180,-675C208,-675 231.667,-665.333 251,-646C270.333,-626.667 280,-603 280,-575C280,-547 270.333,-523.333 251,-504C231.667,-484.667 208,-475 180,-475ZM360,-635C332,-635 308.333,-644.667 289,-664C269.667,-683.333 260,-707 260,-735C260,-763 269.667,-786.667 289,-806C308.333,-825.333 332,-835 360,-835C388,-835 411.667,-825.333 431,-806C450.333,-786.667 460,-763 460,-735C460,-707 450.333,-683.333 431,-664C411.667,-644.667 388,-635 360,-635ZM600,-635C572,-635 548.333,-644.667 529,-664C509.667,-683.333 500,-707 500,-735C500,-763 509.667,-786.667 529,-806C548.333,-825.333 572,-835 600,-835C628,-835 651.667,-825.333 671,-806C690.333,-786.667 700,-763 700,-735C700,-707 690.333,-683.333 671,-664C651.667,-644.667 628,-635 600,-635ZM780,-475C752,-475 728.333,-484.667 709,-504C689.667,-523.333 680,-547 680,-575C680,-603 689.667,-626.667 709,-646C728.333,-665.333 752,-675 780,-675C808,-675 831.667,-665.333 851,-646C870.333,-626.667 880,-603 880,-575C880,-547 870.333,-523.333 851,-504C831.667,-484.667 808,-475 780,-475ZM266,-75C236,-75 210.833,-86.5 190.5,-109.5C170.167,-132.5 160,-159.667 160,-191C160,-225.667 171.833,-256 195.5,-282C219.167,-308 242.667,-333.667 266,-359C285.333,-379.667 302,-402.167 316,-426.5C330,-450.833 346.667,-473.667 366,-495C380.667,-512.333 397.667,-526.667 417,-538C436.333,-549.333 457.333,-555 480,-555C502.667,-555 523.667,-549.667 543,-539C562.333,-528.333 579.333,-514.333 594,-497C612.667,-475.667 629.167,-452.667 643.5,-428C657.833,-403.333 674.667,-380.333 694,-359C717.333,-333.667 740.833,-308 764.5,-282C788.167,-256 800,-225.667 800,-191C800,-159.667 789.833,-132.5 769.5,-109.5C749.167,-86.5 724,-75 694,-75C658,-75 622.333,-78 587,-84C551.667,-90 516,-93 480,-93C444,-93 408.333,-90 373,-84C337.667,-78 302,-75 266,-75Z" style="fill:none;fill-rule:nonzero;stroke-width:50px;"/>
                                                </g>
                                            </svg>
                                            <span>
                                                <?php echo __("Max Pets", "holray_units"); ?> <?php echo esc_html($result["meta"]["max_pets"]); ?>
                                            </span>
                                        </div>
                
                                        <div class="holray-card-icon-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z"/>
                                            </svg>
                                            <span>
                                                <?php echo date("jS M Y", strtotime($result["api"]->fromdt)); ?> - <?php echo date("jS M Y", strtotime($result["api"]->todt)); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="holray-card-price-column">
                                        <div class="holray-price-wrapper">
                                            <div class="holray-price-label"><?php echo __("Book now for", "holray_units"); ?></div>
                                            <div class="holray-price">
                                                <?php 
                                                    echo \Holray\Plugin\Plugin::getOption("currency_position", "left") == "left" ? esc_html(\Holray\Plugin\Plugin::getOption("currency_symbol", "£")) : "";
                                                    echo number_format(
                                                        $result["api"]->price->totaltopay,
                                                        intval(\Holray\Plugin\Plugin::getOption("decimals", "0")),
                                                        \Holray\Plugin\Plugin::getOption("decimal_sep", "."),
                                                        \Holray\Plugin\Plugin::getOption("thousand_sep", ",")
                                                    );
                                                    echo \Holray\Plugin\Plugin::getOption("currency_position", "left") == "right" ? esc_html(\Holray\Plugin\Plugin::getOption("currency_symbol", "£")) : "";
                                                ?>
                                            </div>
                                        </div>
                
                                        <div class="holray-booknow">
                                            <a href="<?php echo esc_url(\Holray\Plugin\Util\Unit::bookNowLinkFromResult($result["api"])); ?>" class="holray-btn holray-btn-primary"><?php echo __("Book Now", "holray_units"); ?></a>
                                        </div>
                                    </div>
                                </div>
                
                            <?php endforeach; ?>
                    <?php endif; ?>
                    <?php do_action('holray_results_after_render', $fields, count($units)); ?>
        
                </div>
            </div>
        </div>
    </section>
</main>



<?php get_footer(); ?>