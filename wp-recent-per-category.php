<?php
/*
Plugin Name: Recent Per Category
Plugin URI: http://falkus.co
Description: Heavily based on the built in Recent Posts plugin, but using specific categories
Version: 1.0
Author: Martin Falkus
Author URI: http://falkus.co
License: GPL2
*/

/**
 * Recent posts, based on a given category
 *
 * Based on the class used to implement a Recent Posts widget.
 */
class WP_Recent_Per_Category extends WP_Widget {

	/**
	 * Sets up a new Recent Per Category Posts widget instance.
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_per_cat_entries', 'description' => __( "Your site&#8217;s most recent Posts in a given category.") );
		parent::__construct('recent-per-category', __('Recent Posts Per Category'), $widget_ops);
		$this->alt_option_name = 'widget_recent_per_cat_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts Per Category widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$cat_id = ( ! empty( $instance['cat_id'] ) ) ? absint( $instance['cat_id'] ) : 0;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
            'cat'                 => $cat_id
		) ) );

		if ($r->have_posts()) :
		?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
			<?php if ( $show_date ) : ?>
				<span class="post-date"><?php echo get_the_date(); ?></span>
			<?php endif; ?>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
		<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;
	}

	/**
	 * Handles updating the settings for the widget
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']      = sanitize_text_field( $new_instance['title'] );
		$instance['number']     = (int) $new_instance['number'];
		$instance['show_date']  = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['cat_id']     = (int) $new_instance['cat_id'];
		return $instance;
	}

	/**
	 * Outputs the settings form
	 */
	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$cat_id    = isset( $instance['cat_id'] ) ? absint( $instance['cat_id'] ) : 0;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
		<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

		<p><label for="<?php echo $this->get_field_id( 'cat_id' ); ?>"><?php _e( 'Category:' ); ?></label>
        <?php wp_dropdown_categories( array(
            'hide_empty'   => 0,
            'name'         => 'cat_id',
            'id'           => 'cat_id',
            'hierarchical' => true
        ) ); ?></p>

		<p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
<?php
	}
}


/**
 * Register our widget, hooked on to widgets_init
 */
function recent_per_cat_widget_register() {
    register_widget('WP_Recent_Per_Category');
}
add_action('widgets_init', 'recent_per_cat_widget_register');
