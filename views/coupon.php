<?php isset($coupons) or die('No coupons');

if ($coupons->have_posts()):  while ($coupons->have_posts()):  $coupons->the_post(); ?>
<?php
	
	/**
	 * Date validation
	 */
	$expiration = get_post_meta(get_the_ID(), '_expiration_d', true);
	$current = time();

	if ($expiration && (time() >= $expiration))
		continue;



?>
<div id="coupon_<?php the_ID() ?>" class="coupon">

	<div class="coupon-contents">

		<div class="wrapper-image">
			<?php if (has_post_thumbnail())
				the_post_thumbnail(array(124, 124)) ?>
		</div>

		<div class="wrapper-metadata">
			<h4><?php echo ucfirst(get_the_title()) ?></h4>
			<p class="time">
				Added : <span class="added"><?php the_date('j F Y') ?></span>
				| Expires: <span class="expires"><?php echo date('j F Y', $expiration) ?></span>
			</p>
		</div>

		<div class="actions">

			<div class="get-it">
				<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
			</div>
			
			<div class="rate-it">
				<p>Does it work?</p>
				<a class="yes">Yes</a>
				<a class="no">No</a>
			</div>

		</div>

	</div>


	<div class="coupon-footer">
		<a href="#share_coupon" class="share">Share</a>
	</div>
</div>
<?php endwhile;  endif; ?>