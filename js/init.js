//const GOOGLE_API_KEY 	= "AIzaSyAjZfqkHbNlw-hzABMT3WUTC-9dWYEJ6j8";

$(document).ready(function(){
		
		const GOOGLE_SERVICE 	= "https://www.googleapis.com/books/v1/volumes";
		const GOOGLE_API_KEY 	= "AIzaSyCs3CQfYdyUH8m8hHH0P3oZ-kJ3HPKOJZ0";
		const FIELDS			= "fields=items(id,volumeInfo/title,volumeInfo/subtitle)";
		const FIELDS_FULL		= "fields=items(id,volumeInfo/title,volumeInfo/subtitle,volumeInfo/publisher,volumeInfo/imageLinks)";
		
		var i = 0;
		
		$("input[name=q]").live("keydown", function(e){	
			switch ( e.keyCode ){
				// ignore
				case 91 : /* apple key */ return true; break;
				case 40	: 
					$(".search-response li:nth-child("+(i-1)+")").css({"background": "#eee", "color": "#000"});
					$(".search-response li:nth-child("+i+")").css({"background": "#1d57d6", "color": "#fff"});
					i++;
				return false;
				break; 
				case 38	: 
					$(".search-response li:nth-child("+(i+1)+")").css({"background": "#eee", "color": "#000"});
					$(".search-response li:nth-child("+i+")").css({"background": "#1d57d6", "color": "#fff"});
					i--;
					return false;
				break;
			}
		});		
		
		$("form").live("submit",function(e){
			// add selected book to library
			
			var saveID = $("input#selected").val();
			$.getJSON(
				GOOGLE_SERVICE+"/"+saveID+"?country=UK", 
				function(data) {
			  		var items = [];
					var j = 0;
				
			  		$.each(data.items, function(key, itemsData) {
						var bookID = data.items[key].id;
						
						
						$.each(data.items[key].volumeInfo, function(key2, ele) {
							$(".added-book").html('<div class="image"><img src="'+data.items[key].volumeInfo.imageLinks.medium+'" width="180px" /></div><div class="content"><h2>'+data.items[key].volumeInfo.title+'<br/><small>'+data.items[key].volumeInfo.subtitle+'</small></h2><p>This is a description of the book</p></div><div class="clear"></div>' );
						});

			  			$('<div/>', {
			    			'class': 'added-book',
			    			html: items.join('')
			  			}).appendTo('#content');
					});
			});
			return false;
		});
		
		$("input[name=q]").live("keyup", function(e){
			switch ( e.keyCode ){
				// ignore
				case 91 : /* apple key */ return true; break;
				case 13	: // Enter
					$("input[name=q]").val( $(".search-response li:nth-child("+i+")").text() );
					$("input#selected").val($(".search-response li:nth-child("+i+")").attr("id"));
					$(".search-response").remove();
					return false;	
				break;
				default : 
					$(".search-response").remove();

					$(this).off();				
					var searchTerm = $(this).val();
					// get total items
					$.getJSON(
						GOOGLE_SERVICE
						+"?q="+searchTerm
						+"+intitle:"+searchTerm
						+"&country=UK&fields=totalItems&key="+GOOGLE_API_KEY, 
						function(data) {
							
	// send to books API
	$.getJSON( GOOGLE_SERVICE
				+"?q="+searchTerm
				+"+intitle:"+searchTerm
				+"&country=UK&"+FIELDS
				+"&key="+GOOGLE_API_KEY, 
				function(data){
					var items = [];
					var j = 0;
					$.each(data.items, 
						function(key, itemsData) {
							var bookID = data.items[key].id;
							$.each(data.items[key].volumeInfo, 
								function(key2, ele) {
									items.push('<li id="' + bookID + '">' + data.items[key].volumeInfo.title+'<br /><em>' +data.items[key].volumeInfo.subtitle + '</em></li>');
								}
							);
						}
					);

				$('<ul/>', {
					'class'	: 'search-response',
					html	: items.join('')
					}).appendTo('#content');
				});
			});
			$(this).on();
				
				
				break;
			}
			
		
		});			
	})
