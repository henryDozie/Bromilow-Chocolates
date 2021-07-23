<?php



// Add front-page body class.
add_filter( 'body_class', 'genesis_body_class' );


// Define front-page body class.
function genesis_body_class( $classes ) {

        $classes[] = 'front-page';

        return $classes;

}

function render_front(){

$hero= get_field('hero');


if( $hero ){
        echo '<div class="'.$hero['class_name'].'">
			         <div class="wrap">
                  <div class="swiper-container mainSwiper">
                  <!-- Additional required wrapper -->
                  <div class="swiper-wrapper">';
                		while(have_rows('hero')):
                			  the_row();
                	             while(have_rows('hero_slider')):
                					      the_row();
                                echo '<div style="background-image:url('.get_sub_field('background').')" class="swiper-slide">
                                        <img src="/content/uploads/2020/07/component.png" />
                                        <div class="hero-text">
                                          <h1>'.get_sub_field('heading').'</h1>
                                          <p>'.get_sub_field('text').'</p>
                                        </div>
                                        <div class="homeButton">
                                          <a href="'.get_sub_field('slide-url').'" class="button secondary-button">Shop now</a>
                                        </div>

                                      </div>';
                               endwhile;
                	  endwhile;

                   echo  '</div>
                        <div class="swiper-pagination">

                        </div>
                      </div>
                  </div>
                  </div>';

}



$seasonal_novelties= get_field('seasonal_novelties');


if( $seasonal_novelties ){
        echo '<div class="'.$seasonal_novelties['class_name'].'">
                   <div class="wrap">
                      <div class="easter_novlts">
                        <div class="easterN_Header mobile-lr-padding">
                          <h1>'.$seasonal_novelties['header'].'</h1>
                        </div>
                        <div class="easterN_Body mobile-lr-padding">
                          <p>'.$seasonal_novelties['text'].'</p>
                        </div>

						<a class="button primary-button" href="'. $seasonal_novelties['seasonal_novelties_button_link']  
						 .'">'.$seasonal_novelties['button'].'</a>

                        <div class="easterN_Images seasonal-novelties-images">
                          <a href="'. $seasonal_novelties['image1_link'] .'"><img src="'.$seasonal_novelties['image1'].'"/></a>
                          <a href="'. $seasonal_novelties['image2_link'] .'"><img src="'.$seasonal_novelties['image2'].'"/></a>
                        </div>
                      </div>
                </div>
              </div>';

}

$about_bromilow= get_field('about_bromilow');


if( $about_bromilow ){
        echo '<div class="'.$about_bromilow['class_name'].'">
                   <div class="wrap">
                      <div class="about_bromilow mobile-lr-padding">
                        <div class="aboutB">
                          <div class="aboutB_contents">
                          <div class="aboutBwords">
                              <div class="aboutB_Header">
                                <h1>'.$about_bromilow['header'].'</h1>
                              </div>
                              <div class="aboutB_Body">
                                <p>'.$about_bromilow['text'].'</p>
                                <p>'.$about_bromilow['text2'].'</p>
                              </div>

                              <div class="aboutButton">
                                <a class="button ghost-button" href="/about">More About Us</a>
                              </div>
                          </div>
                          <div class="aboutBimg">
                              <div class="aboutB_Image">
                                <img src="'.$about_bromilow['image1'].'"/>
                              </div>
                          </div>
                          </div>
                        </div>
                      </div>
                    </div>
              </div>';

}

$gift= get_field('gift');


if( $gift ){
        echo '<div class="'.$gift['class_name'].'">
                   <div class="wrap mobile-lr-padding">
                      <div class="gift">
                        <div class="giveGift">
                          <div class="brownGift_contents">
                          <div class="giftWords">
                              <div class="aboutB_Header">
                                <h1>'.$gift['header'].'</h1>
                              </div>
                              <div class="aboutB_Body">
                                <p>'.$gift['text'].'</p>
                                <p>'.$gift['text2'].'</p>
                              </div>
                              <div class="brownButton">
                                <a class="button primary-button" href="/product-category/gifts/">Shop Gifts</a>
                              </div>
                          </div>
                          <div class="giftImg">
                            <div class="aboutB_Image">
                              <img src="'.$gift['image1'].'"/>
                            </div>
                          </div>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>';

}


$testimonial= get_field('testimonial');


if( $testimonial ){
        // echo 
        echo '<div class="tstmnl" class="'.$testimonial['class_name'].'">
               <div class="wrap mobile-lr-padding" id="testimonial">
               <h1 class="singleTestimonialHeader">'.$testimonial['heading'].'</h1>
              <div class="testimonial-swiper-container">
              <!-- Additional required wrapper -->
              <div class="swiper-wrapper">';
                while(have_rows('testimonial')):
                    the_row();
                           while(have_rows('testimonial_slider')):
                            the_row();
                            echo '<div class="swiper-slide">
                                      <div class="testimonial">
                                        <img src="/content/uploads/2020/07/chocolate-piece.png" />
                                          <div class="testimonialCenterdiv">

                                              <p class="text">'.get_sub_field('text').'</p>
                                              <p class="name">'.get_sub_field('writer').'</p>
                                              <p class="source">'.get_sub_field('from').'</p>
                                        </div>
                                      </div>
                                  </div>';
                           endwhile;
                endwhile;

                echo  '</div>
                        <div class="swiper-pagination">

                        </div>
                      </div>
                    </div>
                  </div>';

                  echo "<div class='feedback-wrapper'><div class='feedbackMain'>
                          <div class='feedbackTopImage'>
                              <img src='/content/uploads/2020/06/Layer_2.svg'></img>
                          </div>
                          <div class='feedback'>
                            <div class='feedbackHeader'>
                              <h2>Want to share your own experience with Bromilow's?</h2>
                            </div>

                            <div class='feedbacktext'>
                              <p>Leave a review on <a target='_blank' href='https://www.google.com/search?biw=1920&bih=937&ei=WrIRX-vMCKO6ggeVxJmgAg&q=bromilows+chocolate+woodland+park&oq=bromilows+chocolate+woodland+park&gs_lcp=CgZwc3ktYWIQAzILCC4QxwEQrwEQkwIyAggmOgcIABBHELADOgYIABAWEB46BQghEKABOg0ILhDHARCvARANEJMCUKuwCliYxwpghsgKaANwAHgAgAFtiAGbC5IBBDE1LjKYAQCgAQGqAQdnd3Mtd2l6&sclient=psy-ab&ved=0ahUKEwjr4b-9vNTqAhUjneAKHRViBiQQ4dUDCAw&uact=5#lrd=0x89c2fe6e14524f73:0x56d8fe80732e43e4,1,,,' class='feedbackLink'>Google</a> or <a target='_blank' href='https://www.yelp.com/biz/bromilow-chocolates-woodland-park' class='feedbackLink'>Yelp</a></p>
                            </div>

                            <div class='feedbackIcons'>
                              <a target='_blank' href='https://www.google.com/search?biw=1920&bih=937&ei=WrIRX-vMCKO6ggeVxJmgAg&q=bromilows+chocolate+woodland+park&oq=bromilows+chocolate+woodland+park&gs_lcp=CgZwc3ktYWIQAzILCC4QxwEQrwEQkwIyAggmOgcIABBHELADOgYIABAWEB46BQghEKABOg0ILhDHARCvARANEJMCUKuwCliYxwpghsgKaANwAHgAgAFtiAGbC5IBBDE1LjKYAQCgAQGqAQdnd3Mtd2l6&sclient=psy-ab&ved=0ahUKEwjr4b-9vNTqAhUjneAKHRViBiQQ4dUDCAw&uact=5#lrd=0x89c2fe6e14524f73:0x56d8fe80732e43e4,1,,,'><img src='/content/uploads/2020/04/x31__stroke.png'></img></a>
                              <a target='_blank' href='https://www.yelp.com/biz/bromilow-chocolates-woodland-park'><img src='/content/uploads/2020/04/yelp.png'></img></a>
                            </div>
                          </div>
                        </div>";

                  echo "<div class='video-wrapper'><div class='videoPlaceHolder'><iframe src='https://www.youtube.com/embed/NcJQk_ePRQM' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
                        </div></div></div>";

}


$video= get_field('video');




$location= get_field('location');


if( $location ){
        echo '<div class="'.$location['class_name'].'">
                 <div class="wrap">
                    <div class="location">
                      <div class="locationDiv">
                        <div class="location_contents">
                          <div class="locationHeader mobile-lr-padding">
                            <h2>'.$location['header'].'</h2>
                          </div>
                          <div class="locationText mobile-lr-padding">
                            <p>'.$location['location_text'].'</p>
                          </div>
                          <div class="brownButton mobile-lr-padding">
                            <a href="#" id="locations" class="primary-button button">Our Locations</a>
                          </div>
                          <div class="locationImage">
                            <img src="'.$location['location_image'].'"/>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>';

}



}
add_action('genesis_loop', 'render_front');

// Run the Genesis loop.
genesis();
