<?php isset($featured) or die('sadfasf');

global $post;

foreach ($featured as $post):  setup_postdata($post) ?>
<div id="coupon_<?php the_ID() ?>" class="coupon featured">

	<div class="coupon-contents">

		<div class="wrapper-image">
			<?php if (has_post_thumbnail())
				the_post_thumbnail(array(124, 124)) ?>
		</div>

		<div class="wrapper-metadata">
			<h4><?php echo ucfirst(get_the_title()) ?></h4>
			<?php the_excerpt() ?>
		</div>

		<div class="clear"></div>

		<div class="actions">

			<div class="get-it">
				<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
			</div>

		</div>

	</div>


	<div class="coupon-footer">
		<div class="register">
		</div>
	</div>
</div>

<div id="coupons-banner-wrapper">
	<p>Active <?php the_category() ?> 2012 and discounts</p>
</div>

<?php wp_reset_postdata();  endforeach ?>