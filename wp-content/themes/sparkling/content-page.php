<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package sparkling
 */
?>

<?php the_post_thumbnail( 'sparkling-featured', array( 'class' => 'single-featured' )); ?>

<div class="post-inner-content">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
      // Checks if this is homepage to enable homepage widgets
	if ( !is_front_page() ) : ?>
	      
		<header class="entry-header page-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->
	<?php endif; ?>
	<div class="entry-content" <?php if ( is_front_page() ) echo "style=\"margin-top:0;\"" ?>>
		<?php the_content(); ?>
		<?php if (is_front_page()) { ?>
			<div class="entry-content">
				<h1><?php the_title(); ?></h1>
				<ul class="home-news">
				<?php
				global $post;
				$args = array(
				    'post_type' => 'post',
				    'post_status'   => 'publish',
				    'date_query'    => array(
				        'column'  => 'post_date',
				        'after'   => '- 30 days'
				    )
				);

				$myposts = get_posts( $args );

				if(count($myposts) < 8){
					$myposts = get_posts('numberposts=8');
				}

				foreach($myposts as $post) :
					setup_postdata($post);
				?>
					<li>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="home-news-img">
						 	<?php if (has_post_thumbnail()){
						 		the_post_thumbnail( 'sparkling-home-thumbnail', array( 'class' => 'home-news-featured' )); 
						 	}else{ ?>
								<div class="home-news-no-featured"></div>
					 		<?php } ?>
						</a>
						<div class="home-news-content">
							<?php $categoryClass = '';
							foreach (get_the_category() as $category) {
								if($category->slug != "a-la-une"){
									$categoryClass .= ' ' . $category->slug;
								}
							}?>
							<h1 class="entry-title <?php echo $categoryClass; ?>"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
							<div class="entry-content">
								<?php
								if ( get_theme_mod( 'sparkling_excerpts' ) == 1 ) :
									the_excerpt();
								else :
									the_content();
								endif;
								 ?>

								<p><span class="posted-on"><i class="fa fa-calendar"></i> <?php echo get_the_date(); ?></span><a class="btn btn-default read-more" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Read More', 'sparkling' ); ?></a></p>
							</div>
						</div>
					</li>
			<?php endforeach;?>
			</ul></div>
			<script type="text/javascript" src="<?php get_template_directory_uri(); ?>/wp-content/themes/sparkling/inc/js/liScroll.js"></script>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					<?php 
						$my_id = 548;
						$postFlash = get_post($my_id);
						$content = $postFlash->post_content;
						$content = apply_filters('the_content', $content);
						$content = preg_replace( "/\r|\n/", "", $content);
						$content = str_replace("<ul>", "<ul id=\"flash-info\">", $content);
						if($content != ""){
					?>
					jQuery('<?php echo $content ?>').insertBefore('.container.main-content-area');
					jQuery(function(){
					    jQuery("ul#flash-info").liScroll();
					});	
					<?php } ?>	
				});
			</script>
		<?php } ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sparkling' ),
				'after'  => '</div>',
			) );
		?>
    <?php
      // Checks if this is homepage to enable homepage widgets
      //if ( is_front_page() ) :
        //get_sidebar( 'home' );
      //endif;
    ?>
	</div><!-- .entry-content -->
	<?php edit_post_link( esc_html__( 'Edit', 'sparkling' ), '<footer class="entry-footer"><i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->
</div>