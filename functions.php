<?php
/**
 *  Lisa's Kitchen Custom Theme
 * 
 *  Parent Style: Story
 * 
 */


function lk_enqueue_styles() {
    $parenthandle = 'story'; // 
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css',
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}
add_action( 'wp_enqueue_scripts', 'lk_enqueue_styles' );

//breadcrumb menu 
function lk_custom_new_menu() {
    register_nav_menu('menu-bread-crumbs',__( 'Menu-Bread-Crumbs' ));
}
add_action( 'init', 'lk_custom_new_menu' );


// Breadcrumbs
function lk_custom_breadcrumbs() {
       
    // Settings
    $separator          = '&gt;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    //$home_title         = 'Homepage';
    $home_title = get_bloginfo( 'name' );
      
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
       
    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) {
       
        // Build the breadcrums
        _e( '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">' , 'lisaskitchen' );
           
        // Home page
        _e( '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>' , 'lisaskitchen' );
        _e( '<li class="separator separator-home"> ' . $separator . ' </li>' , 'lisaskitchen' );
           
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
              
            _e( '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>' , 'lisaskitchen' );
              
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                _e( '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>' , 'lisaskitchen' );
                _e( '<li class="separator"> ' . $separator . ' </li>' , 'lisaskitchen' );
              
            }
              
            $custom_tax_name = get_queried_object()->name;
            _e( '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>' , 'lisaskitchen' );
              
        } else if ( is_single() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                _e( '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>' , 'lisaskitchen' );
                _e( '<li class="separator"> ' . $separator . ' </li>' , 'lisaskitchen' );
              
            }
              
            // Get post category info
            $category = get_the_category();
             
            if(!empty($category)) {
              
                // Get last category post is in
                $last_category = end(array_values($category));
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                   
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
               
            }
              
            // Check if the post is in a category
            if(!empty($last_category)) {
                _e ( $cat_display );
                _e( '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>' , 'lisaskitchen' );
                  
            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                  
                _e( '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>' , 'lisaskitchen' );
                _e( '<li class="separator"> ' . $separator . ' </li>' , 'lisaskitchen' );
                _e( '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>' , 'lisaskitchen' );
              
            } else {
                  
                _e( '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>' , 'lisaskitchen' );
                  
            }
              
        } else if ( is_category() ) {
               
            // Category page
            _e( '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>' , 'lisaskitchen' );
               
        } else if ( is_page() ) {
               
            // Standard page
            if( $post->post_parent ){
                
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse($anc);
                   
                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }
                   
                // Display parent pages
                _e( $parents );
                   
                // Current page
    
                _e( '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>' , 'lisaskitchen' );
                   
            } else {
                   
                // Just display current page if not parents
                _e( '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>' , 'lisaskitchen' );
                   
            }
               
        } else if ( is_tag() ) {
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
            _e( '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>' , 'lisaskitchen' );
           
        } elseif ( is_day() ) {
               
            // Day archive
               
            // Year link
            _e( '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>' );
            _e( '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>' , 'lisaskitchen' );
               
            // Month link
            _e( '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>' , 'lisaskitchen' );
            _e( '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>' , 'lisaskitchen' );
               
            // Day display
            _e( '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>' , 'lisaskitchen' );
               
        } else if ( is_month() ) {
               
            // Month Archive
               
            // Year link
            _e( '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>' , 'lisaskitchen' );
            _e( '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>' , 'lisaskitchen' );
               
            // Month display
            _e( '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>' , 'lisaskitchen' );
               
        } else if ( is_year() ) {
               
            // Display year archive
            _e( '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>' , 'lisaskitchen' );
               
        } else if ( is_author() ) {
               
            // Auhor archive
               
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
               
            // Display author name
            _e( '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>' , 'lisaskitchen' );
           
        } else if ( get_query_var('paged') ) {
               
            // Paginated archives
            _e( '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>' , 'lisaskitchen' );
               
        } else if ( is_search() ) {
           
            // Search results page
            _e( '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>' , 'lisaskitchen' );
           
        } elseif ( is_404() ) {
               
            // 404 page
            _e( '<li>' . 'Error 404' . '</li>' , 'lisaskitchen' );
        }
       
        _e( '</ul>' );      
    }      
}

//List Child pages
function lk_list_child_pages() { 
 
    global $post; 
     
    if ( is_page() && $post->post_parent )
     
        $childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0' );
    else
        $childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0' );
     
    if ( $childpages ) {
     
        $string = '<ul class="child_page_list">' . $childpages . '</ul>';
    } else { 
        $string = '<h3>There are no children pages to view</h3>';
    }
     
    return $string;
     
}
add_shortcode( 'list_childpages', 'lk_list_child_pages' );


function lk_add_header(){

    ?>
    <div id="announcement_block">
        <p>Did you know our food is safely delivered to your door!</p>
    </div>
    <?php
}
add_action( 'announcement-block', 'lk_add_header' );