<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package sparkling
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function sparkling_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
}
add_filter( 'wp_page_menu_args', 'sparkling_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function sparkling_body_classes( $classes ) {
  // Adds a class of group-blog to blogs with more than 1 published author.
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  }

  return $classes;
}
add_filter( 'body_class', 'sparkling_body_classes' );


if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
  /**
   * Filters wp_title to print a neat <title> tag based on what is being viewed.
   *
   * @param string $title Default title text for current view.
   * @param string $sep Optional separator.
   * @return string The filtered title.
   */
  function sparkling_wp_title( $title, $sep ) {
    if ( is_feed() ) {
      return $title;
    }
    global $page, $paged;
    // Add the blog name
    $title .= get_bloginfo( 'name', 'display' );
    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
      $title .= " $sep $site_description";
    }
    // Add a page number if necessary:
    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
      $title .= " $sep " . sprintf( esc_html__( 'Page %s', 'sparkling' ), max( $paged, $page ) );
    }
    return $title;
  }
  add_filter( 'wp_title', 'sparkling_wp_title', 10, 2 );
  /**
   * Title shim for sites older than WordPress 4.1.
   *
   * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
   * @todo Remove this function when WordPress 4.3 is released.
   */
  function sparkling_render_title() {
    ?>
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <?php
  }
  add_action( 'wp_head', 'sparkling_render_title' );
endif;


// Mark Posts/Pages as Untiled when no title is used
add_filter( 'the_title', 'sparkling_title' );

function sparkling_title( $title ) {
  if ( $title == '' ) {
    return 'Untitled';
  } else {
    return $title;
  }
}

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function sparkling_setup_author() {
  global $wp_query;

  if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
    $GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
  }
}
add_action( 'wp', 'sparkling_setup_author' );


/**
 * Password protected post form using Boostrap classes
 */
add_filter( 'the_password_form', 'custom_password_form' );

function custom_password_form() {
  global $post;
  $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
  $o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
  <div class="row">
    <div class="col-lg-10">
        ' . esc_html__( "<p>This post is password protected. To view it please enter your password below:</p>" ,'sparkling') . '
        <label for="' . $label . '">' . esc_html__( "Password:" ,'sparkling') . ' </label>
      <div class="input-group">
        <input class="form-control" value="' . get_search_query() . '" name="post_password" id="' . $label . '" type="password">
        <span class="input-group-btn"><button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="' . esc_attr__( "Submit",'sparkling' ) . '">' . esc_html__( "Submit" ,'sparkling') . '</button>
        </span>
      </div>
    </div>
  </div>
</form>';
  return $o;
}

// Add Bootstrap classes for table
add_filter( 'the_content', 'sparkling_add_custom_table_class' );
function sparkling_add_custom_table_class( $content ) {
    return str_replace( '<table>', '<table class="table table-hover">', $content );
}

if ( ! function_exists( 'sparkling_social' ) ) :
/**
 * Display social links in footer and widgets if enabled
 */
function sparkling_social(){
  $services = array (
    'facebook'   => 'Facebook',
    'twitter'    => 'Twitter',
    'googleplus' => 'Google+',
    'youtube'    => 'Youtube',
    'vimeo'      => 'Vimeo',
    'linkedin'   => 'LinkedIn',
    'pinterest'  => 'Pinterest',
    'rss'        => 'RSS',
    'tumblr'     => 'Tumblr',
    'flickr'     => 'Flickr',
    'instagram'  => 'Instagram',
    'dribbble'   => 'Dribbble',
    'skype'      => 'Skype',
    'foursquare' => 'Foursquare',
    'soundcloud' => 'SoundCloud',
    'github'     => 'GitHub',
    'spotify'    => 'Spotify'
    );

  echo '<div class="social-icons">';

  foreach ( $services as $service => $name ) :

      $active[ $service ] = of_get_option ( 'social_'.$service );
      if ( $active[$service] ) { echo '<a href="'. esc_url( $active[$service] ) .'" title="'. esc_html__('Follow us on ','sparkling').$name.'" class="'. $service .'" target="_blank"><i class="social_icon fa fa-'.$service.'"></i></a>';}

  endforeach;
  echo '</div>';

}
endif;

if ( ! function_exists( 'sparkling_header_menu' ) ) :
/**
 * Header menu (should you choose to use one)
 */
function sparkling_header_menu() {
  // display the WordPress Custom Menu if available
  wp_nav_menu(array(
    'menu'              => 'primary',
    'theme_location'    => 'primary',
    'depth'             => 2,
    'container'         => 'div',
    'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
    'menu_class'        => 'nav navbar-nav',
    'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
    'walker'            => new wp_bootstrap_navwalker()
  ));
} /* end header menu */
endif;

if ( ! function_exists( 'sparkling_footer_links' ) ) :
/**
 * Footer menu (should you choose to use one)
 */
function sparkling_footer_links() {
  // display the WordPress Custom Menu if available
  wp_nav_menu(array(
    'container'       => '',                              // remove nav container
    'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
    'menu'            => esc_html__( 'Footer Links', 'sparkling' ),   // nav name
    'menu_class'      => 'nav footer-nav clearfix',      // adding custom nav class
    'theme_location'  => 'footer-links',             // where it's located in the theme
    'before'          => '',                                 // before the menu
    'after'           => '',                                  // after the menu
    'link_before'     => '',                            // before each link
    'link_after'      => '',                             // after each link
    'depth'           => 0,                                   // limit the depth of the nav
    'fallback_cb'     => 'sparkling_footer_links_fallback'  // fallback function
  ));
} /* end sparkling footer link */
endif;


if ( ! function_exists( 'sparkling_call_for_action' ) ) :
/**
 * Call for action text and button displayed above content
 */
function sparkling_call_for_action() {
  if ( is_front_page() && of_get_option( 'w2f_cfa_text' )!=''){
    echo '<div class="cfa">';
      echo '<div class="container">';
        echo '<div class="col-sm-8">';
          echo '<span class="cfa-text">'. of_get_option( 'w2f_cfa_text' ).'</span>';
          echo '</div>';
          echo '<div class="col-sm-4">';
          echo '<a class="btn btn-lg cfa-button" href="'. of_get_option( 'w2f_cfa_link' ). '">'. of_get_option( 'w2f_cfa_button' ). '</a>';
          echo '</div>';
      echo '</div>';
    echo '</div>';
  }
}
endif;

if ( ! function_exists( 'sparkling_featured_slider' ) ) :
/**
 * Featured image slider, displayed on front page for static page and blog
 */
function sparkling_featured_slider() {
  $useragent = $_SERVER['HTTP_USER_AGENT'];
  $mobile = false;
  if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
    $mobile = true;
  }
  if ( is_front_page() && of_get_option( 'sparkling_slider_checkbox' ) == 1 && $mobile == false) {
    echo '<div class="flexslider">';
      echo '<ul class="slides">';

        $count = of_get_option( 'sparkling_slide_number' );
        $slidecat =of_get_option( 'sparkling_slide_categories' );

        $query = new WP_Query( array( 'cat' =>$slidecat,'posts_per_page' =>$count ) );
        if ($query->have_posts()) :
          while ($query->have_posts()) : $query->the_post();

          echo '<li><a href="'. get_permalink() .'">';
            if ( (function_exists( 'has_post_thumbnail' )) && ( has_post_thumbnail() ) ) :
              echo the_post_thumbnail( 'sparkling-slider-non-crop', array());
            endif;

              echo '<div class="flex-caption">';
                  $categoryClass = '';
                  foreach (get_the_category() as $category) {
                    if($category->slug != "a-la-une"){
                      $categoryClass .= ' ' . $category->slug;
                    }
                  }
                  if ( get_the_title() != '' ) echo '<h2 class="entry-title ' . $categoryClass . '">'. get_the_title().'</h2>';
                  if ( get_the_excerpt() != '' ) echo '<div class="excerpt">' . get_the_excerpt() .'</div>';
              echo '</div>';

              endwhile;
            endif;

          echo '</a></li>';
      echo '</ul>';
    echo ' </div>';
  }
}
endif;

/**
 * function to show the footer info, copyright information
 */
function sparkling_footer_info() {
global $sparkling_footer_info;
  printf("");
}


if ( ! function_exists( 'get_sparkling_theme_options' ) ) {
/**
 * Get information from Theme Options and add it into wp_head
 */
    function get_sparkling_theme_options(){

      echo '<style type="text/css">';

      if ( of_get_option('link_color')) {
        echo 'a, #infinite-handle span, #secondary .widget .post-content a {color:' . of_get_option('link_color') . '}';
      }
      if ( of_get_option('link_hover_color')) {
        echo 'a:hover, a:active, #secondary .widget .post-content a:hover {color: '.of_get_option('link_hover_color').';}';
      }
      if ( of_get_option('element_color')) {
        echo '.btn-default, .label-default, .flex-caption h2, .btn.btn-default.read-more, button {background-color: '.of_get_option('element_color').'; border-color: '.of_get_option('element_color').';} .site-main [class*="navigation"] a, .more-link { color: '.of_get_option('element_color').'}';
      }
      if ( of_get_option('element_color_hover')) {
        echo '.btn-default:hover, .label-default[href]:hover, .tagcloud a:hover, button, .main-content [class*="navigation"] a:hover, .label-default[href]:focus, #infinite-handle span:hover, .btn.btn-default.read-more:hover, .btn-default:hover, .scroll-to-top:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .site-main [class*="navigation"] a:hover, .more-link:hover, #image-navigation .nav-previous a:hover, #image-navigation .nav-next a:hover, .cfa-button:hover { background-color: '.of_get_option('element_color_hover').'; border-color: '.of_get_option('element_color_hover').'; }';
      }
      if ( of_get_option('cfa_bg_color')) {
        echo '.cfa { background-color: '.of_get_option('cfa_bg_color').'; } .cfa-button:hover a {color: '.of_get_option('cfa_bg_color').';}';
      }
      if ( of_get_option('cfa_color')) {
        echo '.cfa-text { color: '.of_get_option('cfa_color').';}';
      }
      if ( of_get_option('cfa_btn_color') || of_get_option('cfa_btn_txt_color') ) {
        echo '.cfa-button {border-color: '.of_get_option('cfa_btn_color').'; color: '.of_get_option('cfa_btn_txt_color').';}';
      }
      if ( of_get_option('heading_color')) {
        echo 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .entry-title {color: '.of_get_option('heading_color').';}';
      }
      if ( of_get_option('nav_bg_color')) {
        echo '.navbar.navbar-default {background-color: '.of_get_option('nav_bg_color').';}';
      }
      if ( of_get_option('nav_link_color')) {
        echo '.navbar-default .navbar-nav > li > a, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus { color: '.of_get_option('nav_link_color').';}';
      }
      if ( of_get_option('nav_item_hover_color')) {
        echo '.navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .entry-title a:hover {color: '.of_get_option('nav_item_hover_color').';}';
      }
      if ( of_get_option('nav_dropdown_bg')) {
        echo '.dropdown-menu {background-color: '.of_get_option('nav_dropdown_bg').';}';
      }
      if ( of_get_option('nav_dropdown_item')) {
        echo '.navbar-default .navbar-nav .open .dropdown-menu > li > a, .dropdown-menu > li > a { color: '.of_get_option('nav_dropdown_item').';}';
      }
      if ( of_get_option('nav_dropdown_bg_hover') || of_get_option('nav_dropdown_item_hover') ) {
        echo '.dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus, .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {background-color: '.of_get_option('nav_dropdown_bg_hover').'; color:'.of_get_option('nav_dropdown_item_hover').'}';
      }
      if ( of_get_option('footer_bg_color')) {
        echo '#colophon {background-color: '.of_get_option('footer_bg_color').';}';
      }
      if ( of_get_option('footer_text_color')) {
        echo '#footer-area, .site-info {color: '.of_get_option('footer_text_color').';}';
      }
      if ( of_get_option('footer_widget_bg_color')) {
        echo '#footer-area {background-color: '.of_get_option('footer_widget_bg_color').';}';
      }
      if ( of_get_option('footer_link_color')) {
        echo '.site-info a, #footer-area a {color: '.of_get_option('footer_link_color').';}';
      }
      if ( of_get_option('social_color')) {
        echo '.well .social-icons a {background-color: '.of_get_option('social_color').' !important ;}';
      }
      if ( of_get_option('social_footer_color')) {
        echo '#footer-area .social-icons a {background-color: '.of_get_option('social_footer_color').' ;}';
      }
      $typography = of_get_option('main_body_typography');
      if ( $typography ) {
        echo '.entry-content {font-family: ' . $typography['face'] . '; font-size:' . $typography['size'] . '; font-weight: ' . $typography['style'] . '; color:'.$typography['color'] . ';}';
      }
      if ( of_get_option('custom_css')) {
        echo of_get_option( 'custom_css', 'no entry' );
      }
        echo '</style>';
    }
}
add_action( 'wp_head', 'get_sparkling_theme_options', 10 );

// Theme Options sidebar
add_action( 'optionsframework_after', 'sparkling_options_display_sidebar' );

function sparkling_options_display_sidebar() { ?>
  <!-- Twitter -->
  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

  <!-- Facebook -->
    <div id="fb-root"></div>
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=328285627269392";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <div id="optionsframework-sidebar" class="metabox-holder">
    <div id="optionsframework" class="postbox">
        <h3><?php esc_html_e('Support and Documentation','sparkling') ?></h3>
        <div class="inside">
          <div id="social-share">
            <div class="fb-like" data-href="<?php echo esc_url( 'https://www.facebook.com/colorlib' ); ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true"></div>
            <div class="tw-follow" ><a href="https://twitter.com/colorlib" class="twitter-follow-button" data-show-count="false">Follow @colorlib</a></div>
          </div>
            <p><b><a href="<?php echo esc_url( 'http://colorlib.com/wp/support/sparkling' ); ?>"><?php esc_html_e('Sparkling Documentation','sparkling'); ?></a></b></p>
            <p><?php _e('The best way to contact us with <b>support questions</b> and <b>bug reports</b> is via','sparkling') ?> <a href="<?php echo esc_url( 'http://colorlib.com/wp/forums' ); ?>"><?php esc_html_e('Colorlib support forum','sparkling') ?></a>.</p>
            <p><?php esc_html_e('If you like this theme, I\'d appreciate any of the following:','sparkling') ?></p>
            <ul>
              <li><a class="button" href="<?php echo esc_url( 'http://wordpress.org/support/view/theme-reviews/sparkling?filter=5' ); ?>" title="<?php esc_attr_e('Rate this Theme', 'sparkling'); ?>" target="_blank"><?php printf(esc_html__('Rate this Theme','sparkling')); ?></a></li>
              <li><a class="button" href="<?php echo esc_url( 'http://www.facebook.com/colorlib' ); ?>" title="Like Colorlib on Facebook" target="_blank"><?php printf(esc_html__('Like on Facebook','sparkling')); ?></a></li>
              <li><a class="button" href="<?php echo esc_url( 'http://twitter.com/colorlib/' ); ?>" title="Follow Colrolib on Twitter" target="_blank"><?php printf(esc_html__('Follow on Twitter','sparkling')); ?></a></li>
            </ul>
        </div>
    </div>
  </div>
<?php }

/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function sparkling_caption($output, $attr, $content) {
  if (is_feed()) {
    return $output;
  }

  $defaults = array(
    'id'      => '',
    'align'   => 'alignnone',
    'width'   => '',
    'caption' => ''
  );

  $attr = shortcode_atts($defaults, $attr);

  // If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
  if ($attr['width'] < 1 || empty($attr['caption'])) {
    return $content;
  }

  // Set up the attributes for the caption <figure>
  $attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
  $attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
  $attributes .= ' style="width: ' . (esc_attr($attr['width']) + 10) . 'px"';

  $output  = '<figure' . $attributes .'>';
  $output .= do_shortcode($content);
  $output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
  $output .= '</figure>';

  return $output;
}
add_filter('img_caption_shortcode', 'sparkling_caption', 10, 3);

/**
 * Skype URI support for social media icons
 */
function sparkling_allow_skype_protocol( $protocols ){
    $protocols[] = 'skype';
    return $protocols;
}
add_filter( 'kses_allowed_protocols' , 'sparkling_allow_skype_protocol' );

/**
 * Add custom favicon displayed in WordPress dashboard and frontend
 */
function sparkling_add_favicon() {
  if ( of_get_option( 'custom_favicon' ) ) {
    echo '<link rel="shortcut icon" type="image/x-icon" href="' . of_get_option( 'custom_favicon' ) . '" />'. "\n";
  }
}
add_action( 'wp_head', 'sparkling_add_favicon', 0 );
add_action( 'admin_head', 'sparkling_add_favicon', 0 );

/*
 * This one shows/hides the an option when a checkbox is clicked.
 */
add_action( 'optionsframework_custom_scripts', 'optionsframework_custom_scripts' );

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

  jQuery('#sparkling_slider_checkbox').click(function() {
      jQuery('#section-sparkling_slide_categories').fadeToggle(400);
  });

  if (jQuery('#sparkling_slider_checkbox:checked').val() !== undefined) {
    jQuery('#section-sparkling_slide_categories').show();
  }

  jQuery('#sparkling_slider_checkbox').click(function() {
      jQuery('#section-sparkling_slide_number').fadeToggle(400);
  });

  if (jQuery('#sparkling_slider_checkbox:checked').val() !== undefined) {
    jQuery('#section-sparkling_slide_number').show();
  }

});
</script>

<?php
}
