<?php defined('ABSPATH') or die("Cannot access pages directly.");

class Categories_Widget extends WP_Widget
{
	function Categories_Widget ()
	{
		parent::__construct('ecc_categories_widget', 'The coupons categories', array(
			'description' => 'fjnds ljnlkjn djlkn lk'
		));
	}

	function widget ($args, $instance)
	{

		$cats = get_categories(array(
			'type'                     => 'post',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'count',
			'order'                    => 'ASC',
			'hide_empty'               => true,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => $instance['cats_to_display'],
			'taxonomy'                 => 'coupons',
			'pad_counts'               => false
		));
		// $cats = get_terms('coupons');

		if ( ! $cats || empty($cats))
			return;

		?>

		<!-- BEGIN WIDGET -->
		<?php echo $args['before_widget'] ?>
			<?php echo $args['before_title'] ?><?php echo $instance['title'] ?><?php echo $args['after_title'] ?>
			<ul id="list-coupon_category">
				
				<?php foreach ($cats as $cat): ?>
				
				<li id="ecc_category_<?php echo $cat->term_taxonomy_id ?>" class="category category-<?php echo $cat->slug ?>">
					<a href="<?php echo get_term_link($cat->slug, 'coupons') ?>"><?php echo $cat->name ?></a>
				</li>
				
				<?php endforeach ?>

			</ul>
		<?php echo $args['after_widget'] ?>
		<!-- END WIDGET -->

		<?php
	}

	function update ($new_instance, $old_instance)
	{

		if ( ! isset($new_instance['cats_to_display']) || ! is_numeric($new_instance['cats_to_display']))
			$new_instance['cats_to_display'] = 10;

		if ( ! isset($new_instance['title']) || empty($new_instance['title']))
			$new_instance['title'] = 'popular <span>categories</span>';

		 return $new_instance;
	}

	function form ($instance)
	{

		if ( ! isset($instance['cats_to_display']) || ! is_numeric($instance['cats_to_display']))
			$instance['cats_to_display'] = 10;

		if ( ! isset($instance['title']) || empty($instance['title']))
			$instance['title'] = 'popular <span>categories</span>';

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title') ?>">Title: </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo $instance['title'] ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cats_to_display') ?>">Number of categories to show: </label>
				<input id="<?php echo $this->get_field_id('cats_to_display') ?>" name="<?php echo $this->get_field_name('cats_to_display'); ?>" type="text" size="3" value="<?php echo $instance['cats_to_display'] ?>" />
			</label>
		</p>
		<?php
	}
}