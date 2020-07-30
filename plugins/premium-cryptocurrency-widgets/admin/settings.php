<?php defined('PCW_ROOT_DIR') or die('Direct access is not allowed');?>
<div id="pcw_admin" class="wrap">
  <?php if($settingsSaved):?>
    <div class="notice notice-success">
      <p>
        <?php esc_html_e('Settings are successfully saved.', 'premium-cryptocurrency-widgets')?>
      </p>
    </div>
  <?php endif;?>

  <h2><?php print self::NAME?> <span class="title-count "><?php print self::VERSION?></span></h2>
  <h3><?php esc_html_e('Plugin settings', 'premium-cryptocurrency-widgets')?></h3>

  <div class="pcw-wrapper">
    <form method="post" action="<?php print $_SERVER['REQUEST_URI']?>">
      <table class="form-table">
        <tbody>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('CryptoCompare API key', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <input type="text" name="cryptocompare_api_key" value="<?php print isset($this->config['cryptocompare_api_key']) ? $this->config['cryptocompare_api_key'] : ''?>" class="regular-text">
            <p class="description">
              <a href="https://min-api.cryptocompare.com" target="_blank">
                <?php esc_html_e('Get a free API key', 'premium-cryptocurrency-widgets')?>
              </a>
            </p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('Locale', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <input type="text" name="locale" value="<?php print $this->config['locale']?>" class="regular-text">
            <p class="description">
              <?php esc_html_e('This setting affects how dates are formatted.', 'premium-cryptocurrency-widgets')?>
            </p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('Thousands separator', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <input type="text" name="thousands_separator" value="<?php print $this->config['thousands_separator']?>" class="regular-text">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('Decimal separator', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <input type="text" name="decimal_separator" value="<?php print $this->config['decimal_separator']?>" class="regular-text">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('Price margin (%)', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <input type="number" name="price_margin" value="<?php print isset($this->config['price_margin']) ? $this->config['price_margin'] : 0?>" step="0.01" class="regular-text">
            <p class="description">
              <?php esc_html_e('Price margin in percent to automatically apply to the pulled quotes.', 'premium-cryptocurrency-widgets')?>
            </p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('Google Maps API key', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <input type="text" name="google_maps_api_key" value="<?php print isset($this->config['google_maps_api_key']) ? $this->config['google_maps_api_key'] : ''?>" class="regular-text">
            <p class="description">
              <?php esc_html_e('Required for geo heatmap, optional for gauge and treemap widgets', 'premium-cryptocurrency-widgets')?>
            </p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('Asset recognition regexp', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <input type="text" name="asset_recognition_regexp" value="<?php print isset($this->config['asset_recognition_regexp']) ? $this->config['asset_recognition_regexp'] : ''?>" class="regular-text" placeholder="^coin-page-([a-zA-Z0-9-\*]+)-([a-zA-Z0-9-\*]+)/?$">
            <p class="description">
              <?php esc_html_e('Please check the documentation for further instructions (expand the Help section in the top right corner).', 'premium-cryptocurrency-widgets')?>
            </p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('Virtual asset page regexp', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <input type="text" name="asset_page_regexp" value="<?php print isset($this->config['asset_page_regexp']) ? $this->config['asset_page_regexp'] : ''?>" class="regular-text" placeholder="^crypto/([a-zA-Z0-9-\*]+)/([a-zA-Z0-9-\*]+)/?$">
            <p class="description">
              <?php esc_html_e('Please check the documentation for further instructions (expand the Help section in the top right corner).', 'premium-cryptocurrency-widgets')?>
            </p>
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label><?php esc_html_e('Virtual asset page content', 'premium-cryptocurrency-widgets')?></label>
          </th>
          <td>
            <!--<textarea name="asset_page_content" rows="20" cols="100"><?php /*print $this->config['asset_page_content']*/?></textarea>-->
            <?php wp_editor($this->config['asset_page_content'], self::CODE . '-asset-page-content', ['textarea_name' => 'asset_page_content']); ?>
          </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php esc_html_e('Enqueue priority', 'premium-cryptocurrency-widgets')?></label>
            </th>
            <td>
                <input type="number" name="enqueue_priority" value="<?php print $this->config['enqueue_priority']?>" class="regular-text">
            </td>
        </tr>
        </tbody>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
</div>