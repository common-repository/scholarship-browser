var $ = jQuery;

$(document).ready(function(){
	
 $(".waitloading").ajaxStart(function(){
  $(this).show();
 }).ajaxStop(function(){
  $(this).hide();
 });
	
 $.fn.exists = function(){
  return this.length>0;
 }

 $(".waitloading").hide();

 sb_loader_init();

})   
		
var postMarker = "";
var postTag;
var postClass;
var titleTag;
var titleClass;
var textClass;
var descPre;
var descAfter;

function jlink(query) {

 $(".waitloading").show();

 $.ajax({
  dataType: 'jsonp',
  jsonp: 'jsonp_callback',
  crossDomain: true,
  url: 'http://www.ausgetauscht.de' + query + '&' + $("#stipWidget").attr('jqdata'),
  success: function (data) {

   //Update Navigation
   $('#stipContainer').empty();
   $('#stipContainer').append(data.nav);

   //Find Content Area
   if( !$('#post-stipResult').exists() ) {
	if( $('*[id^="post"]:first').exists() ) {
	 postMarker = '*[id^="post"]:first';
	}
	else if( $('.post').exists() ) {
	 postMarker = '.post';
	}
	postTag = $(postMarker).get(0).nodeName.toLowerCase();
	postClass = $(postMarker).get(0).className;
	titleTag = $(postMarker+' h1, '+postMarker+' h2, '+postMarker+' h3').get(0).nodeName.toLowerCase();
	titleClass = $(postMarker+' h1, '+postMarker+' h2, '+postMarker+' h3').get(0).className;

	$(postMarker).before('<'+postTag+' id="post-stipResult" class="'+postClass+'"></'+postTag+'>');//<div id="stipResult"></div>
   }
   $('#post-stipResult').empty();
   
   //Insert Content/Results
   if( !data.desc ) {
	data.desc = "";
   }
   else {
	data.desc = "<p>"+data.desc+"</p>";
   }
 
   $('#post-stipResult').prepend('<'+titleTag+' class="'+titleClass+'">'+$(data.title).text()+'</'+titleTag+'>'+data.desc+'<p>'+data.rs+'</p>');
			
   $(".waitloading").hide();
   
   sb_loader_init();
  }
 });
			

}

function sb_loader_init() {
  $("#widgetFormCountryTopShowFull").click( function(e) {
  $("#widgetFormCountryTop").hide(200);
  $("#widgetFormCountryFull").show(400);
 });

 $(".widgetFormCountryTitle").click( function(e) {
  if( $(".widgetFormCountryTitle+.widgetFormCountry").is(":visible") ) {
   $(".widgetFormCountryTitle+.widgetFormCountry").hide(200);
  }
  else {
   $(".widgetFormCountryTitle+.widgetFormCountry").show(400);
  }
 });

 $(".widgetFormRequTitle").click( function(e) {
  if( $(".widgetFormRequTitle+.widgetFormCountry.widgetFormRequ").is(":visible") ) {
   $(".widgetFormRequTitle+.widgetFormCountry.widgetFormRequ").hide(200);
  }
  else {
   $(".widgetFormRequTitle+.widgetFormCountry.widgetFormRequ").show(400);
  }
 });

 $(".widgetFormVolumeTitle").click( function(e) {
  if( $(".widgetFormVolumeTitle+.widgetFormCountry.widgetFormRequ").is(":visible") ) {
   $(".widgetFormVolumeTitle+.widgetFormCountry.widgetFormRequ").hide(200);
  }
  else {
   $(".widgetFormVolumeTitle+.widgetFormCountry.widgetFormRequ").show(400);
  }
 });

 $(".widgetFormStateTitle").click( function(e) {
  if( $(".widgetFormStateTitle+.widgetFormCountry.widgetFormRequ").is(":visible") ) {
   $(".widgetFormStateTitle+.widgetFormCountry.widgetFormRequ").hide(200);
  }
  else {
   $(".widgetFormStateTitle+.widgetFormCountry.widgetFormRequ").show(400);
  }
 });

 $(".widgetFormYearTitle").click( function(e) {
  if( $(".widgetFormYearTitle+.widgetFormCountry.widgetFormRequ").is(":visible") ) {
   $(".widgetFormYearTitle+.widgetFormCountry.widgetFormRequ").hide(200);
  }
  else {
   $(".widgetFormYearTitle+.widgetFormCountry.widgetFormRequ").show(400);
  }
 });
}


