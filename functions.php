<?php
/**
 * Getting all post categories
 * @return Array
 *
 * @version 1.0
 * @since 1.0
 */
function sb_get_categories(){
	global $wpdb, $table_prefix;

	$categories = $wpdb->get_results('SELECT t.* FROM '.$table_prefix.'terms t
											INNER JOIN '.$table_prefix.'term_taxonomy tt ON t.term_id=tt.term_id
									  WHERE tt.taxonomy="category" ORDER BY t.term_id ASC');
	return $categories;
}

/**
 * Function checkes whether widget is allowed to be displayed on current page
 * @return Boolean
 *
 * @version 1.0
 * @since 1.0
 */
function sb_allow_show_widget($options){
	$options['categories'] = unserialize($options['categories']);

	if( is_null($options['categories']) && !isset($options['default']) ) {
	 return false;
	}
	if(isset($options['default'])&&!is_category()&&!is_single()) // is set default, and it's not categories or post page => return true
		return true;

	if(!is_category()&&!is_single()) return false;	// default not set and it's not category or post page => return false
	
	$categories = get_the_category();
	if(!$categories) return false; // post does not belong to any category?
	
	// checking all categories the post belongs to
	foreach($categories as $category){
		$allow_show = true;
		// if include subcats is set => check if whether the category is inside a category from sidget settings
		if( !isset($options['include_subcats']) || is_null($options['include_subcats'])){
		  if(is_array($options['categories']) ) {
			if(!in_array($category->term_id,$options['categories'])) {
				$allow_show = false;
			}
		  }
		  
		}
		else{ // otherwise check if category belongs to widget categories list
			$parent_categories = sb_get_cat_parents($category->term_id);
			if(!array_intersect($parent_categories,$options['categories'])&&!in_array($category->term_id,$options['categories']))
				$allow_show = false;
		}
		
		if($allow_show) return true; // if any category from categories the post belongs to, corresponds the widget settings => widget is allowed to be displayed
	}
	
	
	return false;
}
 
/**
 * Recursivly getting array of all parent categories
 * @return Boolean
 * @param Int   $category_id  category id
 *
 * @version 1.0
 * @since 1.0
 */
function sb_get_cat_parents($category_id){
	$category=get_category($category_id);
	$parent_categories = array();
	if($category->category_parent!=0){
		return array_merge(array($category->category_parent),sb_get_cat_parents($category->category_parent));
	}
		else return array();
}
?>