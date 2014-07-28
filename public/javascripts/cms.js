
$(document).ready(function() {
  sortImages();
  accordion();
  $(window).resize(function() {
    accordion();
    $( "#accordion" ).accordion( "refresh" );
  });
  //typFold();
  dropDown();

  $("#installer").steps({
    onFinished: function (event, currentIndex) {
      console.log("I set this in the constructor -- all finished");

      $.post( "installer-process-db", $("#installerform").serialize(), function( json ) {
        console.log("done: "+data);
      }, "json");

      //document.location.href='/cms/login';

    }
  });
});
/**
* File upload
* @type {[type]}
*/
// get existing files
function getImages() {
  var path = $(location).attr('pathname');
  path.indexOf(1);
  var typ =  path.split("/")[2];
  id = path.split("/")[3];
  console.log("baseURL:"+baseURL);
  var url = baseURL+"/cms/"+typ+"/"+id+"/imgs";
  console.log("URL:"+url);
  jQuery.get(url,function(data){
    console.log("data?:"+data);
    $(".images").html(data);
  });
}
getImages();
var images = $('tbody.images');
$('#drop a').click(function(){
  console.log("click");
  // Simulate a click on the file input button
  // to show the file browser dialog
  $(this).parent().find('input').click();
});
// Initialize the jQuery File Upload plugin
$('#upload').fileupload({
  // This element will accept file drag/drop uploading
  dropZone: $('#drop'),
  // This function is called when a file is added to the queue;
  // either via the browse button, or via drag/drop:
  add: function (e, data) {
    e.preventDefault();
    var tpl = $('<tr class="working"><td class="msg"></td><td><input type="text" value="0" data-width="48" data-height="48"'+
    ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /></td><td class="name"></td><td class="size"></td></tr>');
    // Append the file name and file size
    tpl.find('td.size').text(formatFileSize(data.files[0].size));
    //append name
    tpl.find('td.name').text(data.files[0].name);
    // Add the HTML to the UL element
    data.context = tpl.appendTo(images);
    // Initialize the knob plugin
    //tpl.find('input').knob();
    // Listen for clicks on the cancel icon
    tpl.find('span').click(function(){
      if(tpl.hasClass('working')){
        jqXHR.abort();
      }
      tpl.fadeOut(function(){
        tpl.remove();
      });
    });
    // Automatically upload the file once it is added to the queue
    var jqXHR = data.submit().success(function(result, textStatus, jqXHR){
      //          alert(data.files[0]['type']);
      if(data.files[0]['type'] != 'image/png' && data.files[0]['type'] != 'image/jpg' && data.files[0]['type'] != 'image/jpeg' && data.files[0]['type'] != 'application/pdf' && data.files[0]['type'] != 'application/msword' && data.files[0]['type'] != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' && data.files[0]['type'] != 'application/zip'){
        tpl.find('td.msg').text("Alleen .png, .jpg, .jpeg, .pdf, .doc, en .zip zijn toegestaan.");
        data.context.addClass('error');
        data.context.delay(2000).fadeOut('slow');
        return;
      }
      var json = JSON.parse(result);
      var status = json['status'];
      if(status == 'error'){
        tpl.find('td.msg').text("Fout");
        data.context.addClass('error');
        data.context.delay(2000).fadeOut('slow');
        return;
      }
      // refresh image list
      getImages();
      setTimeout(function(){
        //data.context.delay(2000).fadeOut('slow');
      },3000);
    });
  },
  progress: function(e, data){
    // Calculate the completion percentage of the upload
    var progress = parseInt(data.loaded / data.total * 100, 10);
    // Update the hidden input field and trigger a change
    // so that the jQuery knob plugin knows to update the dial
    data.context.find('input').val(progress).change();
    if(progress == 100){
      data.context.removeClass('working');
    }
  },
  fail:function(e, data){
    // Something has gone wrong!
    //tpl.find('td.msg').text("Fout");
    data.context.addClass('error');
    data.context.delay(2000).fadeOut('slow');
  }
});
// Prevent the default action when a file is dropped on the window
$(document).on('drop dragover', function (e) {
  e.preventDefault();
});
// Helper function that formats the file sizes
function formatFileSize(bytes) {
  if (typeof bytes !== 'number') {
    return '';
  }
  if (bytes >= 1000000000) {
    return (bytes / 1000000000).toFixed(2) + ' GB';
  }
  if (bytes >= 1000000) {
    return (bytes / 1000000).toFixed(2) + ' MB';
  }
  return (bytes / 1000).toFixed(2) + ' KB';
}
/**
* Functions
*/
$(window).resize(function() {
  sort();
});
function dropDown() {
  $(document).bind('click', function (e) {
    $('.dropdown ul').removeClass('visible');
  });
  $('.dropdown span.clickable').bind('click', function(e) {
    e.stopPropagation();
    var w = $(this).width;
    $(this).next('.dropdown ul').toggleClass('visible');
    $(this).next('.dropdown a').css('width',w);
  });
  /**
  * Toolbar text type (paragraph, header)
  * @return {[type]} [description]
  */
  $('#toolbar .dropdown ul li a').on("click", function() {
    $('#toolbar .dropdown ul').toggleClass('visible');
    var curTxt = $(this).text();
    if(curTxt!="") {
      $('#toolbar .dropdown span a span').html(curTxt);
    }
  });
}
function accordion() {
  var h = $(window).height()-66;
  $("#accordion").height(h);
  $( "#accordion" ).accordion({
    heightStyle: "fill"
  });
}
function typFold() {
  $('.lst').hide();
  $('.lst:first').slideDown();
  $('h1').click(function() {
    $(this).next('.lst').slideToggle();
  })
}
function resizeTextArea($element) {
  $element.height($element[0].scrollHeight);
}
function sortImages() {
  $(".images").sortable({
    handle : '.handle',
    update : function () {
      var order = $('.images').sortable('toArray').toString();
      console.log("order:"+order);
      var path = $(location).attr('pathname');
      path.indexOf(1);
      utt = path.split("/")[3];
      console.log("utt:"+utt);
      $("#info").load(baseURL+"/cms/"+utt+"/img/sort/"+order);
    }
  });
}
