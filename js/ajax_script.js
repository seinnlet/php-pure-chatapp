const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

function fetchMessages() {
	$.ajax({
		type: "post",
		url: `db_chat.php?r_id=${urlParams.get('r_id')}`,
		data: {action: 'fetchMsg'},
		success: function (response) {
			$('#chat-wrapper').html(response);
		}
	});
}

function fetchRoomList() {
	$.ajax({
		type: "post",
		url: `db_chat.php?r_id=${urlParams.get('r_id')}`,
		data: {action: 'fetchRoomList'},
		success: function (response) {
			$('#aside-room').html(response);
		}
	});
}

function scrollChatToBottom() {
	let chatBox = $('#chat-wrapper');
	chatBox.scrollTop(chatBox.prop("scrollHeight"));
}

$(function() {

	scrollChatToBottom();

	setInterval(fetchRoomList, 5000);
	
	if (urlParams.has('r_id')) {
		
		setInterval(fetchMessages, 3000);

		$('#btnSend').click(function (e) { 
			e.preventDefault();
			let message = $('#taMessage').val();

			if (message) {
				$.ajax({
					type: "post",
					url: "db_chat.php",
					data: {roomId: urlParams.get('r_id'), message: message, action: 'createMsg'},
					success: function () {
						$('#taMessage').val('');
						fetchMessages();
						fetchRoomList();
						scrollChatToBottom();
					}
				});
			}
			
		});
	}
});