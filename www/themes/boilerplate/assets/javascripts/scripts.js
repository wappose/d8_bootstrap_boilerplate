(function ($) {
	$(function () {
/* navigation mobile toggle icons */
		$(".collapse-toggle").on("click", function(e) {
			$icon = $(this).find(".glyphicon").toggleClass('glyphicon-plus-sign').toggleClass('glyphicon-minus-sign');
		});
		$(".navbar-toggle").on("click", function(e) { $(this).toggleClass('open'); });		

		/* smooth scrolling to anchors */
		$('a[href*="#"]:not([href="#"]):not(.carousel-control)').on("click", function() {
		    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
		      var target = $(this.hash);
		      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
		      if (target.length) {
		        $('html, body').animate({
		          scrollTop: target.offset().top
		        }, 500);
		        return false;
		      }
		    }
  		});

  		$(".clickable-layer").not("a").on("click", function(el) {
  			document.location = $(this).find('.views-field a').attr("href");
  			return false;
  		});
  		
  		/* general purpose print button */
  		$(".print-page").on("click", function() {
  			window.print();
  		});
	});
})(jQuery);