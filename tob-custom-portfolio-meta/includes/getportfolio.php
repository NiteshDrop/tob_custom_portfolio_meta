<?php
if ( ! class_exists( 'GetPortfolioAndRemovePointer' ) ) :
	class GetPortfolioAndRemovePointer {
	public static function get_checked_posts() {
		$post_id = get_the_ID();
		$value = get_post_meta($post_id, '_avada_portfolio_featured', true);
		if($value==='1') {
			$taxonomies = get_object_taxonomies( get_post_type( $post_id ) );
			foreach ($taxonomies as $taxonomy) {
				$terms = wp_get_post_terms( $post_id, $taxonomy );
				if(!empty($terms)) {
					foreach ( $terms as $term ) {
						$term_slug = esc_html(trim($term->slug));
						$query = new WP_Query(array(
							'tax_query' => array(
								array(
									'taxonomy' => 'portfolio_category',
									'field'    => 'term_id',
									'terms'    => $term->term_id,
								),
							),
							'posts_per_page' => -1,
						));
						
						if ($query->have_posts()) {
							while ($query->have_posts()) { $query->the_post();
								// echo $term_slug."-".get_the_title()."-".get_the_Id()."<br>";
								self::show_in_search($post_id, $term_slug);
							}
							wp_reset_postdata();
						} else {
							echo 'No posts found.';
						}
					}
				}
				else {
					self::show_in_search($post_id, $term_slug);
				}
			}
		}
	}

	public static function show_in_search($post_id, $term_slug) {
		if(get_theme_mod( 'portfolio_show_in_search_setting' ) === "hide" ) {
			self::hide_from_search($post_id, $term_slug);
		} else {
			self::remove_pointer_event($post_id, $term_slug);
		}
	}
	
	public static function remove_pointer_event($post_id, $term_slug) {
		if(is_front_page()) {
		?>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				let p_id = "<?php echo $post_id; ?>";
				let elements = document.querySelector('.post-'+p_id);
				elements.classList.add("hidepointer");
			});
			</script> 
		<?php
		} else { ?>
			<script>
			document.addEventListener('DOMContentLoaded', function() {
				let str = "<?php echo $term_slug; ?>";
				let elements_search = document.querySelector('.portfolio_category-'+str);
				elements_search.classList.add("hidepointer");
			});
			</script>
		<?php
		}
	}
	
	public static function hide_from_search($post_id, $term_slug){
		if(is_front_page()) {
			?>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					let p_id = "<?php echo $post_id; ?>";
					let elements = document.querySelector('.post-'+p_id);
					elements.classList.add("hidepointer");
				});
				</script> 
			<?php
			} else { ?>
				<script>
				document.addEventListener('DOMContentLoaded', function() {
					var elements_search = document.querySelector('.portfolio_category-<?php echo $term_slug ?>');
					var elements = document.querySelector('.<?php echo $term_slug ?>');
					elements_search.style.display="none";
					elements.style.display="none";
				});
				</script>
			<?php
		}
	}
}
add_filter( 'fusion_portfolio_grid_content', ['GetPortfolioAndRemovePointer','get_checked_posts'] );
add_filter( 'avada_blog_post_content', ['GetPortfolioAndRemovePointer','get_checked_posts'] );
endif;