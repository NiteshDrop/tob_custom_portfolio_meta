<?php

if ( ! class_exists( 'CustomPortfolioMetabox' ) ) :
	class CustomPortfolioMetabox {
		public static function add_avada_portfolio_meta_box() {
			add_meta_box(
				'avada_portfolio_meta',            // Unique ID
				'Maintenance Mode',                   // Box title
				[ self::class, 'avada_portfolio_meta_box_html'],   // Content callback, must be of type callable
				'avada_portfolio',                 // Post type
				'side',                            // Context (normal, advanced, side)
				'high'                          // Priority (default, low, high)
			);
		}

		public static function avada_portfolio_meta_box_html($post) {
			$value = get_post_meta($post->ID, '_avada_portfolio_featured', true);
			?>
				<input type="checkbox" id="avada_portfolio_featured_field" name="avada_portfolio_featured_field" value="1" <?php checked($value, '1'); ?> />
				<label for="avada_portfolio_featured_field">Maintenance Mode On</label>
			<?php
			wp_nonce_field('avada_portfolio_meta_box', 'avada_portfolio_meta_box_nonce');
		}

		public static function save_avada_portfolio_meta_box_data($post_id) {
			// Check if nonce is set
			if (!isset($_POST['avada_portfolio_meta_box_nonce'])) {
				return;
			}

			// Verify nonce
			if (!wp_verify_nonce($_POST['avada_portfolio_meta_box_nonce'], 'avada_portfolio_meta_box')) {
				return;
			}

			// Check if not an autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}

			// Sanitize and save the data
			$is_underconstruction = isset($_POST['avada_portfolio_featured_field']) ? '1' : '0';
			update_post_meta($post_id, '_avada_portfolio_featured', $is_underconstruction);
		}
	}

	add_action('add_meta_boxes', ['CustomPortfolioMetabox','add_avada_portfolio_meta_box']);
	// Hook to save the meta box data
	add_action('save_post', ['CustomPortfolioMetabox','save_avada_portfolio_meta_box_data']);
endif;