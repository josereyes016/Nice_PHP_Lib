console.log("feed.js loaded");

var feedT1 = $("#feed").load(feed.php);

var feedT2 = $.getJSON("feed.php")
    .done(function(data, textStatus, jqXHR) {
	console.log("ajax complete");
        var posts = "";
        
        for( var i = 0; i < data.length; i++)
        {
            // So with just text
            if( data[i]['photo'] == ""){
              posts += "<div>\
              <h3>name</h3>\
              <div>" + data[i]['time'] + "</div>\
              <div>" + data[i]['text'] + "</div>\
              </div>";
            }
            // with photo
            else{
              posts += "<div>\
              <h3>name</h3>\
              <div>" + data[i]['time'] + "</div>\
              <div>" + data[i]['photo'] + "</div>\
              <div>" + data[i]['text'] + "</div>\
              </div>";
            }

        }
        
        $('#feed').html(posts);
     })
     .fail(function(jqXHR, textStatus, errorThrown) {

         // log error to browser's console
         console.log("Could not load");
     });

$(function() {

	feedT2();


})
