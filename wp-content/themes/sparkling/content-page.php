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
				$myposts = get_posts('numberposts=5');
				foreach($myposts as $post) :
					setup_postdata($post);
				?>
					<li>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="home-news-img">
						 	<?php if (has_post_thumbnail()){
						 		the_post_thumbnail( 'sparkling-featured', array( 'class' => 'home-news-featured' )); 
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

								<?php
									/*wp_link_pages( array(
										'before'            => '<div class="page-links">'.esc_html__( 'Pages:', 'sparkling' ),
										'after'             => '</div>',
										'link_before'       => '<span>',
										'link_after'        => '</span>',
										'pagelink'          => '%',
										'echo'              => 1
						       		) );*/
						    	?>
							</div>
						</div>
					</li>
			<?php endforeach;?>
			</ul></div>
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