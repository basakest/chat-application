$(document).ready(function(){
    // if user wants to end session
	$("#exit").click(function(){
		var exit = confirm("Are you sure to logout?");
		if(exit==true) {
            window.location = '/chat/logout';
        }		
    });
    
    // when the user submits the form
    $('#submitmsg').on('click', function() {	
		let clientmsg = $("#usermsg").val();
        $.post("/chat/post", {text: clientmsg});
        $('#usermsg').val('');				
        return false;
    });
	
	function loadLog(){		
		let oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
		$.ajax({
            url: "/chat/getJsonContents",
			cache: false,
			success: function(html) {
                // replace chat log withe the newer
				//$("#chatbox").html(html); 
                // auto-scroll
                $form = $("<form acrion='chat/index' method='post'>");
                //$form.append('<input type="button" value="button">');
                $('body').append($form);	
                var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
                $('<input>').attr({
                    type: 'hidden',
                    name: 'contents',
                    value: html
                }).appendTo('form');
                $form.submit();
                if(newscrollHeight > oldscrollHeight){
					$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
                }			
		  	},
		});
    }
    
    setInterval(loadLog, 500);
    

});