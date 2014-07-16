
jQuery(function(){
	jQuery("#crs, #vnm, #nam, #str, #hsn, #psc, #pls, #tel, #eml, #geb, #nam2, #str2, #hsn2, #psc2, #pls2, #tel2, #eml2, #geb2, #naml, #vnml, #strl, #hsnl, #pscl, #plsl, #tell, #emll, #gebl, #als, #dls").validate({
		expression: "if (VAL) return true; else return false;",
		message: "Dit is een verplicht veld"
	});
	jQuery("#eml, #eml2, #emll").validate({
		expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
		message: "Vul een geldig e-mailadres in"
	});
	jQuery("#mv, #mv2, #mvl").validate({
		expression: "if (isChecked(SelfID)) return true; else return false;",
		message: "Dit is een verplicht veld"
	});

});
$(document).ready(function() {

	//masonry();
	//	draw();
	//	maps();
	// $("input").change(function() {
	// 	applicationForm();
	// });

$('.aanbod').bind('input', function(){
	applicationForm();
});

fitVids();

if($(".all").length != "") {
	infiniteScroll();
}

contactForm();
});

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
function fitVids() {
	$(".vid").fitVids();
}
function discount21Under(bdr) {
	// korting onder 21
	var geb = $(".geb").val();
	var dob = Date.parse(geb);
	if (dob != null) {
		if (dob.addYears(21) > Date.today() && geb.length == 10) {
			return 36;
		} else {
			return 44;
		}
	}
}
function applicationForm() {
	// bedrag
	var bdr = 44;
	bdr = discount21Under(bdr);
	// korting voor 2 personen
	var ant = $(".ant").val();
	if (ant >= 2) {
		bdr = (bdr * 0.75) * ant;
	}
	// per minuut
	bdr = bdr / 60;
	var dls = $(".dls").val(); // duur lessen
	if (dls.length) {
		bdr = bdr * dls;
	} else {
		bdr = bdr * 60;
	}
	var als = $(".als").val(); // aantal lessen
	if (als.length) {
		bdr = bdr * als;
	}
	// bedrag invullen in pagina
	if (bdr > 0) {
		// rounded
		rnd = (Math.round(bdr * 10) / 10).toFixed(2);
		rnd = rnd.replace(".", ",")
		var euro = htmlEncode("€");
		$(".bdr-preview").html(euro+" "+rnd);
		$(".bdr").val(euro+" "+rnd);

	}
	// aantal cursisten
	var ant = $(".ant").val();
	if (ant <= 1) {
		$(".cr2").hide();
	} else if (ant == 2) {
		$(".cr2").removeClass("hidden").show();
	}
	// toon lesgeldplichtige als cursist < 18 jaar
	var geb = $(".geb").val();
	var dob = Date.parse(geb);
	if (dob != null) {
		if (dob.addYears(18) > Date.today() && (geb.length == 10)) {
			$(".lgp").removeClass("hidden").show();
		} else {
			$(".lgp").hide();
		}
	}
}
function htmlEncode(value){
	if (value) {
		return jQuery('<div />').text(value).html();
	} else {
		return '';
	}
}
function masonry() {
	var container = document.querySelector('.items');
	var msnry = new Masonry(container, {
		// options
		columnWidth: '.g2',
		gutter: ".gutter-sizer",
		itemSelector: '.item',
		isResizeBound: true
	});
}
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
