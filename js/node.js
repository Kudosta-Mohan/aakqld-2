/* Localhost */
var socketio = io.connect("http://118.127.52.214:3000");

function notificationViaNode(data){
	console.log(data);
  	socketio.emit("notifyUser",data);
}
function notificationpmsViaNode(){
	console.log('calledpms');
  	socketio.emit("notifyUserpms");
}

socketio.on('notifyUser', function(data){
		console.log('asd');
		var curusrid = $('.notificationLink').data('currentuserid');
			data = 'action=notiautoupdate&userid='+curusrid;
			$.ajax({
				type: 'POST',
				cache: false,
				url: 'http://ablworks.com.au/elsjobs/class/class.ajax.php',
				data: data, 
				success: function(html) {
				var json = $.parseJSON(html);
				if(json.conutnoti >= 1)
					{
						console.log('awer'+json.conutnoti);
						$('.notificationcountbox').html('<span id="notification_count" class="notification_count">'+ json.conutnoti +'</span>');
					}
				$('.notificationsBody').html(json.appenddata);
				}
		 });	
});

socketio.on('notifyUserpms', function(){
		console.log('PMS');
		var curusrid = $('.equinotificationLink').data('currentuserid');
		data = 'action=equinotiautoupdate&userid='+curusrid;
		$.ajax({
			type: 'POST',
			cache: false,
			url: 'http://ablworks.com.au/elsjobs/class/class.ajax.php',
			data: data, 
			success: function(html) {
			var json = $.parseJSON(html);
			console.log('pms'+json.appenddata);
			if(json.conutnoti > 0){
				console.log('pms'+json.conutnoti);
				$('.equinotificationcountbox').html('<span id="equinotification_count" class="equinotification_count a">'+ json.conutnoti +'</span>');
				}
			$('.equinotificationsBody').html(json.appenddata);
			}
		});	
});