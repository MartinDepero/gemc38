<?php
/*
Template Name: Resultats
*/
get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

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
						<!-- DEBUT archive resulats -->
						
								
				    	<?php
						$previous_year = $year = 0;
						$previous_month = $month = 0;
						$ul_open = false;

						if ($_GET['y']){
							$postYear = $_GET['y'];
						}else{
							$postYear = date("Y");
						}
						$idPostCategory = 0;
						if(in_array('competition', explode('/', $_SERVER['REQUEST_URI']))){
							$idPostCategory = 5;
						}elseif(in_array('formation', explode('/', $_SERVER['REQUEST_URI']))){
							$idPostCategory = 6;
						}
						
						$query = "
							SELECT * FROM $wpdb->posts
							LEFT JOIN $wpdb->term_relationships ON
							($wpdb->posts.ID = $wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy ON
							($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
							WHERE $wpdb->posts.post_status = 'publish'
							AND $wpdb->term_taxonomy.taxonomy = 'category'
							AND $wpdb->term_taxonomy.term_id = " . $idPostCategory . "
							AND YEAR(post_date) = '" . $postYear . "' 
							AND post_type = 'post'
							ORDER BY post_date
							";

						$myposts = $wpdb->get_results($query);
						
						$current_url = explode("?", $_SERVER['REQUEST_URI']);
						?>

						<div class="resulats-filter-year">
							<a class="btn btn-default resulats<?php if($postYear == date('Y')){ echo ' selected';}?>" href="<?php echo $current_url[0] ?>?y=<?php echo date('Y') ?>" title="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></a>
							<a class="btn btn-default resulats<?php if($postYear == date('Y',strtotime('-1 year'))){ echo ' selected';}?>" href="<?php echo $current_url[0]?>?y=<?php echo date('Y',strtotime('-1 year')) ?>" title="<?php echo date('Y',strtotime('-1 year')) ?>"><?php echo date("Y",strtotime("-1 year")) ?></a><br/>
						</div>

						<div class="resulats-filter-month">
							<a id="month-filter-01" class="btn btn-default resulats month-filter" href="#" attr-month="01">Janvier</a>
							<a id="month-filter-02" class="btn btn-default resulats month-filter" href="#" attr-month="02">Fevrier</a>
							<a id="month-filter-03" class="btn btn-default resulats month-filter" href="#" attr-month="03">Mars</a>
							<a id="month-filter-04" class="btn btn-default resulats month-filter" href="#" attr-month="04">Avril</a>
							<a id="month-filter-05" class="btn btn-default resulats month-filter" href="#" attr-month="05">Mai</a>
							<a id="month-filter-06" class="btn btn-default resulats month-filter" href="#" attr-month="06">Juin</a>
							<a id="month-filter-07" class="btn btn-default resulats month-filter" href="#" attr-month="07">Juillet</a><br/>
							<a id="month-filter-08" class="btn btn-default resulats month-filter" href="#" attr-month="08">Aout</a>
							<a id="month-filter-09" class="btn btn-default resulats month-filter" href="#" attr-month="09">Septembre</a>
							<a id="month-filter-10" class="btn btn-default resulats month-filter" href="#" attr-month="10">Octobre</a>
							<a id="month-filter-11" class="btn btn-default resulats month-filter" href="#" attr-month="11">Novembre</a>
							<a id="month-filter-12" class="btn btn-default resulats month-filter" href="#" attr-month="12">Decembre</a>
						</div>

						<?php foreach($myposts as $post) : ?>	
							<?php

							setup_postdata($post);

							$year = mysql2date('Y', $post->post_date);
							$month = mysql2date('n', $post->post_date);
							$day = mysql2date('j', $post->post_date);

							?>

							<?php if($year != $previous_year || $month != $previous_month) : ?>

								<?php if($ul_open == true) : ?>
								</ul>
								<?php endif; ?>

								<ul id="month-<?php the_time('m'); ?>" class="reusltats-list-month">

								<?php $ul_open = true; ?>

							<?php endif; ?>

							<?php $previous_year = $year; $previous_month = $month; ?>

							<li onclick="window.location = '<?php the_permalink(); ?>';"><span class="titre-archive"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span><span class="date-archive"><?php the_time('j F'); ?></span> </li>

						<?php endforeach; ?>
						</ul>
						<script type="text/javascript">
						jQuery(document).ready(function(){
							var d = new Date();
							var month = new Array();
							month[0] = "01";
							month[1] = "02";
							month[2] = "03";
							month[3] = "04";
							month[4] = "05";
							month[5] = "06";
							month[6] = "07";
							month[7] = "08";
							month[8] = "09";
							month[9] = "10";
							month[10] = "11";
							month[11] = "12";
							var m = month[d.getMonth()];
							jQuery('#month-'+m).fadeIn();
							jQuery('#month-filter-'+m).addClass('selected');
						});
							jQuery('.month-filter').on('click',function(){
								jQuery('.reusltats-list-month').css('display', 'none');
								jQuery('#month-'+jQuery(this).attr('attr-month')).fadeIn();
								jQuery('.month-filter').removeClass('selected');
								jQuery(this).addClass('selected');
							});
						</script>
						<!-- FIN archive resulats -->
					</div><!-- .entry-content -->
					<?php edit_post_link( esc_html__( 'Edit', 'sparkling' ), '<footer class="entry-footer"><i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span></footer>' ); ?>
				</article><!-- #post-## -->
				</div>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>