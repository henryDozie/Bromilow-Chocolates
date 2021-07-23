jQuery('.site-footer p:empty').detach();


var mySwiper = new Swiper ('.swiper-container', {
	// Optional parameters
	direction: 'horizontal',
	loop: true,

	// If we need pagination
	pagination: {
	  el: '.swiper-pagination', 
	  clickable: true,
	},
  	autoplay: {
      delay: 4000,
  	},
})

var mySwiper1 = new Swiper ('.testimonial-swiper-container', {
	// Optional parameters
	direction: 'horizontal',
	loop: true,
	spaceBetween: 50,
	breakpoints: {
	320: {
	  slidesPerView: 1,
	},
	900: {
	  slidesPerView: 2,
	  spaceBetween: 30,
	}
	},
	pagination: {
	  el: '.swiper-pagination', 
	  clickable: true,
	},
  })

jQuery('.responsive-menu-search-form').append('<input type="image" class="responsive-submit" src="/content/uploads/2020/07/search-1.svg">');

function min900(x) {
  if (x.matches) { // If media query matches
    jQuery('.testimonial-swiper-container .swiper-slide img').attr('src', '/content/uploads/2020/07/chocolate-piece-1.png');
  } else {
        jQuery('.testimonial-swiper-container .swiper-slide img').attr('src', '/content/uploads/2020/07/chocolate-piece.png');
  }
}

var x = window.matchMedia("(min-width: 900px)")
min900(x) // Call listener function at run time
x.addListener(min900) // Attach listener function on state changes


function min1024(x) {
  if (x.matches) { // If media query matches
		jQuery('.cart-quantity').detach().appendTo('.wpmenucart-display-standard');  
  }
  else{
  		jQuery('.cart-quantity').detach().insertAfter('.mobile-bag');
  } 
}

setTimeout(function(){
	jQuery('.cart-quantity').css('display','flex');
}, 500)

var x = window.matchMedia("(min-width: 1024px)")
min1024(x) // Call listener function at run time
x.addListener(min1024) // Attach listener function on state changes


jQuery('#locations').click(function(event){
	event.preventDefault() 
	jQuery('html, body').animate({
	    scrollTop: jQuery(".footer-widgets .wrap").offset().top
	}, 1000);
})

jQuery('.page-description').detach().prependTo('.site-inner');

jQuery('.woocommerce-perpage, .woocommerce-ordering').wrapAll('<div class="woocommerce-options"></div>');


jQuery( ".woocommerce-options select" ).change(function () {
	if (this.value != "?perpage=none") { 
		window.location.href=this.value;
	}
});

jQuery('.site-container >.woocommerce-product-search').prepend('<h2>Search Products</h2>');

jQuery('#wp-megamenu-item-32878').click(function(){
	if(jQuery('.site-container > .woocommerce-product-search').css('opacity') == 0){
		jQuery('.site-container > .woocommerce-product-search').css({'opacity':'1','transition':'all .3s ease-in-out','pointer-events':'auto'});
	}
	else{
		jQuery('.site-container > .woocommerce-product-search').css({'opacity':'0','transition':'all .3s ease-in-out','pointer-events':'none'});
	}
})
