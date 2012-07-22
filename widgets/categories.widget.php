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
		?>
		<!-- BEGIN WIDGET -->
		<div class="widget">
			<h3 class="widget-title"><?php echo $instance['title'] ?></h3>
			<ul>

			</ul>
		</div>
		<!-- END WIDGET -->
		<?php
	}

	function update ($new_instance, $old_instance)
	{

		if ( ! isset($new_instance['cats_to_show_up']) || ! is_numeric($new_instance['cats_to_show_up']))
			$new_instance['cats_to_show_up'] = 10;

		if ( ! isset($new_instance['title']) || empty($new_instance['title']))
			$new_instance['title'] = 'popular <span>categories</span>';

		 return $new_instance;
	}

	function form ($instance)
	{

		if ( ! isset($instance['cats_to_show_up']) || ! is_numeric($instance['cats_to_show_up']))
			$instance['cats_to_show_up'] = 10;

		if ( ! isset($instance['title']) || empty($instance['title']))
			$instance['title'] = 'popular <span>categories</span>';

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cats_to_show_up'); ?>">Number of categories to show: </label>
				<input id="<?php echo $this->get_field_id('cats_to_show_up'); ?>" name="<?php echo $this->get_field_name('cats_to_show_up'); ?>" type="text" size="3" value="<?php echo $instance['cats_to_show_up']; ?>" />
			</label>
		</p>
		<?php
	}
}