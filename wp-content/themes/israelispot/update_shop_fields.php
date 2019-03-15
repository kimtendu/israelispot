 <?php /* Template Name: update products list */
   get_header('activate');

           

            
            $query = array(
            'post_type' => 'product',            
            
        );



        $wp_query = new WP_Query($query);
            
              
            
            if ( have_posts() ) : while ( have_posts() ) : the_post();

            
                   if($post->price){
                         update_post_meta($post->ID, '_sale_price', $post->price );
                   }
                 

                   if($post->old_price and $post->old_price > $post->price){
                        update_post_meta($post->ID, '_regular_price', $post->old_price );
                    }
                    
                    
                    if($post->is_pro != 'pro'){
                        update_post_meta($post->ID, 'is_pro', 'regular' );
                    }





            endwhile; else:

            
           endif; ?>
        
</body>
</html>