<?php
class Author_Stats_Counter extends WP_Widget {
	// Controller
	function __construct() {
	$widget_ops = array('classname' => 'popular_post_widget', 'description' => __('Insert the plugin description here'));
	$control_ops = array('width' => 300, 'height' => 300);
	parent::WP_Widget(false, $name = __('Author Stats'), $widget_ops, $control_ops );

        }

function form($instance) { 
	$defaults = array( 'title' => __('Author Details'), 'Number of posts' => __('5'), 'checkbox_var' => __('0'), 'checkbox_variable' => ('0'),'radio-button' => ('0'));
	$instance = wp_parse_args( (array) $instance, $defaults ); 

	if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			$post_count=$instance['post_count'];
		}
	else {
			$title =$defaults['title'];
			$post_count=$defaults['post_count'];
		}?>
	<p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
                <input class="checkbox" type="checkbox" <?php checked($instance['chechkbox_var'], 'on'); ?> id="<?php echo $this->get_field_id('chechkbox_var'); ?>" name="<?php echo $this->get_field_name('chechkbox_var'); ?>" /> 
                <label for="<?php echo $this->get_field_id('chechkbox_var'); ?>">Show Top posts by author</label>
        </p>
        <p>
		<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('Number of Posts:', 'wp_widget_plugin'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="number" ><?php echo $post_count;?>
	</p>
        <p>
                <input class="checkbox" type="checkbox" <?php checked($instance['chechkbox_variable'], 'on'); ?> id="<?php echo $this->get_field_id('chechkbox_variable'); ?>" name="<?php echo $this->get_field_name('chechkbox_variable'); ?>" /> 
                <label for="<?php echo $this->get_field_id('chechkbox_variable'); ?>">Show Author Details</label>
        </p>
        <p>
            <input type="radio" id="<?php echo $this->get_field_id('your_radio'); ?>" 
                   name="<?php echo $this->get_field_name('your_radio'); ?>"
            <?php if (isset($instance['your_radio']) && $instance['your_radio']=="views") echo "checked";?>
                   value="views">Sort by Views <br>
            <input type="radio" id="<?php echo $this->get_field_id('your_radio'); ?>" name="<?php echo $this->get_field_name('your_radio'); ?>"
            <?php if (isset($instance['your_radio']) && $instance['your_radio']=="comments")echo "checked";?>
            value="comments">Sort by Comments
        </p>
        
<?php }
function update($new_instance,$old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['post_count'] = strip_tags( $new_instance['post_count'] );
    $instance['chechkbox_var']=strip_tags($new_instance['chechkbox_var']);
    $instance['chechkbox_variable']=strip_tags($new_instance['chechkbox_variable']);
    $instance['your_radio']=strip_tags($new_instance['your_radio']);
    return $instance;
}

function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        // Display the widget title
        echo "<div class='auth-widget'>";
        if(is_single()){
            if ( $title ){
                
                    echo "<h3 class='widget-title'>".$title."</h3>";
               
            }
        
            if(function_exists("asc_get_author_id")){
                echo "<div class = 'post-by'>";
                    echo"<div class='author-gravatar'>".'<a href=' .get_author_posts_url(get_the_author_meta('ID')).'">'.get_avatar(get_the_author_meta('ID'),100).'</a>'."</div>";
                    echo "By:"; 
                    asc_get_author_id();
                echo "</div>";
            }
            if($instance['chechkbox_var'])
            {
        	if (function_exists("asc_post_popularity_list_comments")) 
                    {
                        if($instance['post_count']){
                            $post_count = $instance['post_count']; 
                        }
                        else{
                            $post_count= 5;
                        }
                        echo "<div class='auth-details'>";
                            echo 'More posts by the author:<br><br>';
                        echo"</div>";
                        //show_author_details($post_count);
                        if($instance['your_radio']=="views")
                            asc_post_popularity_list_views($post_count);
                        else
                            asc_post_popularity_list_comments($post_count);
                    }
            }
            if($instance['chechkbox_variable']){
               echo"<div class='auth-desc'>";
                    echo"<h4>Author Description:</h4>";
                    asc_get_author_details();
                 echo"</div>";
            }
	}
        echo "</div>";
}
}