<?php
/**
 * Extends the WordPress widget class by specifying our own custom Scholarship Browser Widget
 *
 * @version 1.3
 * @since 1.0
 */
if(!function_exists('sb_allow_show_widget'))
	require_once plugin_dir_path(__FILE__).'functions.php';
	
class SbWidget extends WP_Widget {
	
	/**
	 * The widget constructor. Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary scripts and styles.
	 *
	 * @version 1.0
	 * @since 1.0
	 */
	function SbWidget() {
		$widget_opts = array(
			'classname'   => 'sb-widget',
			'description' => __( 'Scholarship Browser Widget', 'scholarship-browser' )
		);
		$this->WP_Widget( 'SbWidget', __('Scholarship Browser', 'scholarship-browser' ), $widget_opts  );
	}
	
	
	/**
	 * Outputs the content of the form in the widgets page.
	 * @param  Array $args      The array of form elements
	 * @param  Array $instance
	 *
	 * @version 1.0
	 * @since 1.0
	 */
	function widget( $args, $instance ) {
	 	extract($args);
		if(!sb_allow_show_widget($instance)) return;
		
		//Options
		$apiQueryString = '';
		$arrApiQueryString = array();
		if( isset($instance['full_country_list']) ) {
		 $arrApiQueryString['full_country_list'] = 1;
		}
		if( isset($instance['hide_place']) ) {
		 $arrApiQueryString['hide_place'] = 1;
		}
		if( isset($instance['hide_year']) ) {
		 $arrApiQueryString['hide_year'] = 1;
		}
		if( isset($instance['hide_statistics']) ) {
		 $arrApiQueryString['hide_statistics'] = 1;
		}
		if( count($arrApiQueryString) > 0 ) {
		 $apiQueryString = http_build_query($arrApiQueryString);
		}
		
		echo '<div id="stipWidget" jqdata="'.$apiQueryString.'">';
		wp_register_style( 'style', plugins_url((strlen($instance['style'])>0 ? $instance['style'] : 'style.css'), __FILE__) );
		wp_enqueue_style( 'style' );

		echo $before_title.(isset($instance['title'])&&!empty($instance['title'])?$instance['title']:__('Scholarship Browser')).$after_title;
		echo '<div id="waitloading" class="waitloading"></div>';
		echo '<div id="stipContainer">';
		$url = 'http://www.ausgetauscht.de/api/wp-plugin/data.php?'.$apiQueryString;
		$transport = new WP_Http();
		$data = $transport->request($url);
		$data = $data['body'];
		$data = substr($data, 1, strlen($data)-3);
		$data = json_decode($data);
		echo $data->nav;
		echo '</div>';
		echo '<script type="text/javascript"><!-- var apiquerystring = "'.$apiQueryString.'"; //--></script>';
		echo '</div>';
	}

	/**
	 * Processes the widget's options to be saved.
	 * @param  Array $new_instance  The new instance of values to be generated via the update.
	 * @param  [type] $old_instance The previous instance of values before the update.
	 *
	 * @version 1.0
	 * @since 1.0
	 */	
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['categories'] = serialize($new_instance['categories']);
		
		if(!is_null($new_instance['title']))
			$instance['title'] = $new_instance['title'];		
		if(!is_null($new_instance['default']))
			$instance['default'] = $new_instance['default'];
		if(!is_null($new_instance['include_subcats']))
			$instance['include_subcats'] = $new_instance['include_subcats'];
		if(!is_null($new_instance['full_country_list']))
			$instance['full_country_list'] = $new_instance['full_country_list'];
		if(!is_null($new_instance['hide_place']))
			$instance['hide_place'] = $new_instance['hide_place'];
		if(!is_null($new_instance['hide_year']))
			$instance['hide_year'] = $new_instance['hide_year'];
		if(!is_null($new_instance['hide_statistics']))
			$instance['hide_statistics'] = $new_instance['hide_statistics'];
		if(!is_null($new_instance['style']))
			$instance['style'] = $new_instance['style'];
		return $instance;
	}

	/**
	 * Generates the administration form for the widget.
	 * @param  Array $instance The array of keys and values for the widget.
	 *
	 * @version 1.0
	 * @since 1.0
	 */	
	function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Scholarship Browser', 'scholarship-browser' );
		}
		$categories = sb_get_categories();		
		
		$widget_categories = unserialize($instance['categories']);
?>
<table cellpadding=2>
<?php
	wp_nonce_field( plugin_basename( __FILE__ ), 'sb_noncename' );
	echo '<tr><td colspan=2><h3>'.__('Scholarship Browser','scholarship-browser').'</h3</td></tr>';
	echo '<tr><td colspan=2><label for="'.$this->get_field_id( 'title' ).'">'.translate('Title','scholarship-browser').'</label>';
	echo '<input type="text" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.(isset($instance['title'])?$instance['title']:'').'"  />';	
	echo '</td></tr>';
	if(!empty($categories)){
		echo '<tr><td colspan=2><label for="'.$this->get_field_id( 'categories' ).'">'.translate('Widget visible for categories:','scholarship-browser').'</label></td></tr>';
		foreach($categories as $index => $category){
			echo '<tr>';
			echo '<td><input type="checkbox" id="'.$this->get_field_id( 'categories' ).$category->term_id.'" name="'.$this->get_field_name( 'categories' ).'[]" value="'.$category->term_id.'" '.($widget_categories&&in_array($category->term_id,$widget_categories)?'checked=checked':'').'  /></td>';
			echo '<td><label for="'. $this->get_field_id( 'categories' ).$category->term_id .'">'.$category->name.'</label></td>';
			echo '</tr>';
		}
		echo '</label> ';
	}

?>		
	<tr><td></td></tr>
	<tr>
		<td><input type="checkbox" name="<?php echo $this->get_field_name( 'default' ); ?>" id="<?php echo $this->get_field_id( 'default' ); ?>" <?php echo (isset($instance['default'])?'checked=checked':'')?> /></td>
		<td><label for="<?php echo $this->get_field_id( 'default' ); ?>"><?php _e('If no category available','scholarship-browser')?></label></td>		
	</tr>
	<tr><td colspan=2><h3><?php _e('Other Settings','scholarship-browser'); ?></h3></td></tr>
	<tr>
		<td><input type="checkbox" name="<?php echo $this->get_field_name( 'include_subcats' ); ?>" id="<?php echo $this->get_field_id( 'include_subcats' ); ?>" <?php echo (isset($instance['include_subcats'])?'checked=checked':'')?> /></td>
		<td><label for="<?php echo $this->get_field_id( 'include_subcats' ); ?>"><?php _e('Include subcategories','scholarship-browser')?></label></td>		
	</tr>	
	<tr>
		<td><input type="checkbox" name="<?php echo $this->get_field_name( 'full_country_list' ); ?>" id="<?php echo $this->get_field_id( 'full_country_list' ); ?>" <?php echo (isset($instance['full_country_list'])?'checked=checked':'')?> /></td>
		<td><label for="<?php echo $this->get_field_id( 'full_country_list' ); ?>"><?php _e('Complete Country List','scholarship-browser')?></label></td>		
	</tr>		
	<tr>
		<td><input type="checkbox" name="<?php echo $this->get_field_name( 'hide_place' ); ?>" id="<?php echo $this->get_field_id( 'hide_place' ); ?>" <?php echo (isset($instance['hide_place'])?'checked=checked':'')?> /></td>
		<td><label for="<?php echo $this->get_field_id( 'hide_place' ); ?>"><?php _e('Hide place selection','scholarship-browser')?></label></td>		
	</tr>		
	<tr>
		<td><input type="checkbox" name="<?php echo $this->get_field_name( 'hide_year' ); ?>" id="<?php echo $this->get_field_id( 'hide_year' ); ?>" <?php echo (isset($instance['hide_year'])?'checked=checked':'')?> /></td>
		<td><label for="<?php echo $this->get_field_id( 'hide_year' ); ?>"><?php _e('Hide year selection','scholarship-browser')?></label></td>		
	</tr>		
	<tr>
		<td><input type="checkbox" name="<?php echo $this->get_field_name( 'hide_statistics' ); ?>" id="<?php echo  $this->get_field_id( 'hide_statistics' ); ?>" <?php echo (isset($instance['hide_statistics'])?'checked=checked':'')?> /></td>
		<td><label for="<?php echo $this->get_field_id( 'hide_statistics' ); ?>"><?php _e('Hide statistics','scholarship-browser')?></label></td>		
	</tr>		
	<tr>
	 <td colspan=2><label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php  _e('Style','scholarship-browser'); ?></label> <select name="<?php echo $this->get_field_name( 'style' ); ?>" id="<?php echo  $this->get_field_id( 'style' ); ?>">
		 <?php
		 
		 $style_files = glob(plugin_dir_path(__FILE__).'style*.css');
		 while (list($key, $file) = each($style_files)) {
		  $file = pathinfo($file, PATHINFO_FILENAME ).'.'.pathinfo($file, PATHINFO_EXTENSION );
		  $selected = false;
		  if( strlen($instance['style']) > 0 && $instance['style'] == $file ) {
		   $selected = true;
		  }
		  elseif( strlen($instance['style']) == 0 && $file == 'style.css' ) {
		   $selected = true;
		  }
		  echo '<option name="'.$file.'"'.($selected ? ' selected=selected' : '').'>'.$file.'</option>';
		 }
		 ?>
	  </select>
	 </td>		
	</tr>		
</table>
<?php
	}
}


// registering widget
function sb_register_widget() {
		register_widget( 'SbWidget' );
}

add_action( 'widgets_init', 'sb_register_widget' );