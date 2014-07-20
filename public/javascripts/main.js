
$(document).ready(function() {

	fitVids();
	contactForm();

	if($(".all").length != "") {
		infiniteScroll();
	}


});


/**
* Contact form
*/
function contactForm() {
	$('#contact').ajaxForm({
		target: '#status',
		success: function(data) {
			var json = JSON.parse(data);
			$('#status').html(json.message);
			$('#status').fadeIn('slow');
			if(json.status=="succes") {
				$("#contact").hide();
			}
		}
	});

}

/**
* Infinite scroll
*/
function infiniteScroll() {
	var req=1, done=false, lp = $('div#lastPostsLoader'), pathArray = window.location.pathname.split( '/' ), page = pathArray[2];
	$(window).scroll(function(){
		if( document.documentElement.clientHeight +
			$(document).scrollTop() >= document.body.offsetHeight ){
				if (!done){
					var loc = window.location.href.split("#")[1];
					lp.html('<p class="lead"><i class="fa fa-cog fa-spin fa-2x"></i><br>Laden</p>').show();
					$.ajax({
						type: "GET",
						data: {
							'hash': loc,
							'page' : page,
							'req' : req++
						},
						url: "/ajax",
						success: function(html){
							if(jQuery.trim(html)){
								$(".all").append(html);
							}else{
								done = true;
								lp.delay(500).html('<p class="lead"><a href="#"><i class="fa fa-angle-up fa-2x"></i><br>Naar boven</a></p>');
							}
						}
					});
				}
			}
		});
	}

	/**
	* Fit videos in responsive layouts
	*/
	function fitVids() {
		$(".vid").fitVids();
	}

	/**
	* Google maps
	*/
	function maps() {
		var myLatlng = new google.maps.LatLng(51.9256766, 4.4766061);
		function initialize() {
			var mapOptions = {
				zoom: 14,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var marker = new google.maps.Marker({
				position: myLatlng,
				map: map,
				title: 'Music Studio'
			});
		}
		google.maps.event.addDomListener(window, 'load', initialize);
	}
