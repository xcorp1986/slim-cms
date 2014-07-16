$(document).ready(function() {
	masonry();
});



function masonry() {
	$('.item').each(function() {
		$(this).width(n);
	})


	var $container = $('.items');
	// initialize
	$container.masonry({
	  columnWidth: 10,
	  itemSelector: '.item'
	});
	
	

}