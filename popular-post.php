<?php
   /*
   Plugin Name: Widget Pack
   Plugin URI: http://www.ideaboxthemes.com
   Description: A plugin to count post views by an author and display popular posts using a sidebar widget or shortcode
   Version: 1.0
   Author: Purva Jain
   Author URI: http://ideaboxthemes.com
   License: GPL2 or later
   License URI: http://www.gnu.org/licenses/gpl-2.0.html
   */
?>
<?php 
    add_action( 'wp_head','asc_add_view');
    add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );
  
 


function prefix_add_my_stylesheet(){
        wp_register_style('prefix-style', plugins_url('style.css',__FILE__));
        wp_enqueue_style('prefix-style');
}
 



function asc_add_view(){
    if(is_single()){
        global $post;    
        $current_views=get_post_meta($post->ID, "asc_views", true);
        if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
            $current_views = 0;
        }
        $new_views = $current_views + 1;
        update_post_meta($post->ID, "asc_views", $new_views);
        return $new_views;
    }
}


function asc_get_view_count() {
    global $post;            
    $current_views = get_post_meta($post->ID, "asc_views", true);
    if(!isset($current_views) OR empty($current_views) OR !is_numeric($current_views) ) {
        $current_views = 0;
    }
    return $current_views;
}

    function asc_get_author_id( $post_id = 0 ){
        
        $post = get_post( $post_id );
        $auth_id=$post->post_author;

            echo '<a href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'">'.get_the_author_meta( 'user_firstname', $auth_id)." ".get_the_author_meta( 'user_lastname', $auth_id).'</a>';
         }
  function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "http://upload.wikimedia.org/wikipedia/commons/d/d7/Post-It.jpg"; 
  
    
  }
  return $first_img;
}
   
    function asc_get_author_details($post_id=0){
        $post = get_post( $post_id );
           $auth_id=$post->post_author;
           echo get_the_author_meta( 'description', $auth_id);
        }


function asc_show_views($singular = "view", $plural = "views", $before = "This post has: ") {
    global $post;
    
    echo"<div class='post-views'>";
        $current_views = get_post_meta($post->ID, "asc_views", true);  
        $views_text = $before . $current_views . " ";
        if ($current_views == 1) {
            $views_text .= $singular;
        }
        else {
            $views_text .= $plural;
        }
        echo $views_text;
    echo"</div>";
}



function asc_post_popularity_list_views($post_count) {
    $args = array(
        "posts_per_page" => $post_count,
        "post_type" => "post",
        "post_status" => "publish",
        "meta_key" => "asc_views",
        "orderby" => "meta_value_num",
        "order" => "DESC"
    );
    global $post;
    $asc_list = new WP_Query($args);
   
    if($asc_list->have_posts()) 
        echo"<div class='popular-post-list'>";
        { echo "<ul>"; }   
        while ( $asc_list->have_posts() ) : $asc_list->the_post();  
            
            echo '<li><a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a></li>';
            echo "Author: ";
            asc_get_author_id();
            echo "<br>";
            if (has_post_thumbnail()){
                the_post_thumbnail('featured-thumb');
            }
            else{
                   echo '<img src="';
                   echo catch_that_image();
                   echo '"alt="Image Not Found"';
                   echo the_title();
            echo '/>';
            }
            
            echo "<div class='post-count'>";
                /*echo "This post has: "; 
                comments_number();*/
            echo "</div>";
           echo"</div>";
            asc_show_views();
           echo "</div>";
           echo"</div>";
        endwhile;
	if($asc_list->have_posts()) { echo "</ul>";}
        echo"</div>";
}
function asc_post_popularity_list_comments($post_count) {
    $args = array(
        "posts_per_page" => $post_count,
        "post_type" => "post",
        "post_status" => "publish",
        "orderby" => "comment_count",
        "order" => "DESC"
    );
    global $post;
    $asc_list = new WP_Query($args);
   
    if($asc_list->have_posts()) 
        echo"<div class='popular-post-list'>";
        { echo "<ul>"; }   
        while ( $asc_list->have_posts() ) : $asc_list->the_post();  
            
            echo '<li><a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a></li>';
            echo "Author: ";
            asc_get_author_id();
            echo "<br>";
            if (has_post_thumbnail()){
                the_post_thumbnail('featured-thumb');
            }
            else{
                   echo '<img src="';
                   echo catch_that_image();
                   echo '"alt="Image Not Found"';
                   echo the_title();
            echo '/>';
            }
            
            echo "<div class='post-count'>";
               echo "This post has: "; 
                comments_number();
            
           echo "</div>";
           echo"</div>";
        endwhile;
	if($asc_list->have_posts()) { echo "</ul>";}
        echo"</div>";
}           
function asc_author_popularity_list_views($post_count,$post_id=0) {
    $post=get_post($post_id);
    $author_id=$post->post_author;
    $args = array(
        'author'=>$author_id,
        "posts_per_page" => $post_count,
	"post_type" => "post",
	"post_status" => "publish",
	"meta_key" => "asc_views",
	"orderby" => "meta_value_num",
	"order" => "DESC"
    );
    $asc_list = new WP_Query($args);
    if($asc_list->have_posts()) { echo "<ul>"; }
        while ( $asc_list->have_posts() ) : $asc_list->the_post();                                     
            echo '<li><a href="'.get_permalink($post->ID).'">'.the_title('', '', false).'</a></li>';
          
            
            echo "<div class='post-count'>";
               
            
            asc_show_views();
           echo "</div>";
           
	endwhile;
	if($asc_list->have_posts()) { echo "</ul>";}
}
function asc_author_popularity_list_comments($post_count,$post_id=0) {
  
    $post=get_post($post_id);
    $author_id=$post->post_author;
    $args = array(
        'author'=>$author_id,
        "posts_per_page" => $post_count,
	"post_type" => "post",
	"post_status" => "publish",
	"orderby" => "comment_count",
	"order" => "DESC"
    );
    $asc_list = new WP_Query($args);
    if($asc_list->have_posts()) { echo "<ul>"; }
        while ( $asc_list->have_posts() ) : $asc_list->the_post();                                     
            echo '<li><a href="'.get_permalink().'">'.the_title('', '', false).'</a></li>';
           
            
            echo "<div class='post-count'>";
               echo "This post has: "; 
                comments_number();
            
            
           echo "</div>";
          
	endwhile;
	if($asc_list->have_posts()) { echo "</ul>";}
}
function asc_featured_image(){
    
 if (has_post_thumbnail()){
                the_post_thumbnail('featured-thumb');
            }
            else{
                   echo '<img src="';
                   echo catch_that_image();
                   echo '"alt="Image Not Found"';
                   echo the_title();
            echo '/>';
            }
}
function custom_excerpt_length($length){
    return 15;
}
add_filter('excerpt_length', 'custom_excerpt_length',999);
function new_excerpt_more($more) {
     global $post;
     return '… <a href="'. get_permalink($post->ID) . '">' . 'Read More &raquo;' . '</a>';
    }

   add_filter('excerpt_more', 'new_excerpt_more');


include 'popular-post-stat-widget.php';
add_action('widgets_init',create_function('', 'return register_widget("Post_Stats_Counter");'));

?>
