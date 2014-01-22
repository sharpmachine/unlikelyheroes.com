
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '238807482810727', // App ID from the App Dashboard
      channelUrl : '', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });

    // Additional initialization code such as adding Event Listeners goes here

  };

  // Load the SDK's source Asynchronously
  // Note that the debug version is being actively developed and might 
  // contain some type checks that are overly strict. 
  // Please report such bugs using the bugs tool.
  (function(d, debug){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
     ref.parentNode.insertBefore(js, ref);
   }(document, /*debug*/ false));

/*function Publish(fb_app_id, product_id, text_to_share, plugin_url, main_url)
{
	//alert(fb_app_id);
	FB.init({
	  
    	//appId  : '115454544334343454',
		appId : fb_app_id,
    	status : true, // check login status
    	cookie : true, // enable cookies to allow the server to access the session
    	xfbml  : true,  // parse XFBML
		oauth : true	//OAuth set
  	});
	
	FB.login(function(response) {
	   if (response.authResponse) {
		   	//alert('user logged in successfully');
			//alert(response.email);
			console.log(response.authResponse.userID)
			FB.api('/me/feed', 'post', { message:  text_to_share }, function(response) {  
				if (!response || response.error) {
					//alert('Error occured');
				} else {
					//alert('Post ID: ' + response.id);
				}
			});
			
			var FBUserid = response.authResponse.userID;
			FB.api (
			{
				method: 'fql.query',
				query: 'SELECT email, pic_square FROM user WHERE uid = ' + response.authResponse.userID
			}, function(response) {
				console.log(response);
				
				//+ "&fbpic_saved_"+product_id+"=" + "true"
				jQuery.ajax({
					type: "POST",
					url: main_url+'/wp-admin/admin-ajax.php',
					data: "action=" + 'add_user_avatar'
						+ "&avatar_url=" + response[0].pic_square
						+ "&userid=" + FBUserid
						+ "&product_id=" + product_id
						+ "&fbpic_saved_"+product_id+"=" + "true"
					,
					success: function(html) {						
						//alert(jQuery.trim(html));
					}
				});
			});
		 
	   } else {
		   //alert("not logged in!");
	   }
	 }, {scope: 'email'});
	
	
  	/*FB.ui(
  	{
	  
		method: 'stream.publish',
		attachment: {
			name: 'IgnitionDeck',
		  	caption: 'IgnitionDeck Sharing',
			message: 'this is a test message',
		  	description: (
				text_to_share        		 
			),
      		href: 'http://fbrell.com/'
    	},
    	action_links: [
    		{ text: 'fbrell', href: 'http://fbrell.com/' }
    	]
  	},
		function(response) {
			if (response && response.post_id) {
			
			alert('Post was published.');
		} else {
		  alert("Post was not published.");
		  
		}
  	});*/
//}