var print_error_return=false;

function print_invoice(obj_json, url){

if (typeof(url)==="undefined") {url=0;}
if ("WebSocket" in window){

		// Let us open a web socket

		if (location.protocol === 'https:') {
			var ws = new WebSocket("wss://localhost:8181/Printers/printers");
		  }else{
			var ws = new WebSocket("ws://127.0.0.1:8080/Printers/printers");
			}

		ws.onopen = function()
		{
			// Web Socket is connected, send data using send()
			ws.send(obj_json);print_error_return=false;
			if (url!=0) {

				window.location.href = url;
			}
		};

		ws.onmessage = function (evt)
		{
			print_error_return=true;
			endLoad();
			var received_msg = evt.data;
			Swal.fire( lang['print_error_print']+received_msg );

		};

		ws.onerror = function()
		{
			print_error_return=true;
			// websocket is closed.
			endLoad();
			Swal.fire( lang['print_error_print_con']  );
		};

		ws.onclose = function()
		{
			print_error_return=true;
			// websocket is closed.
			endLoad();
			Swal.fire( lang['print_error_print_soft_start']  );
		};

}
else{
	print_error_return=true;
	// The browser doesn't support WebSocket
	endLoad();
	Swal.fire( lang['print_error_print_browser']  );
}





}


function print_invoice2(obj_json, url){
if (typeof(url)==="undefined") {url=0;}
if ("WebSocket" in window){

		// Let us open a web socket
		if (location.protocol === 'https:') {
			var ws2 = new WebSocket("wss://localhost:8181/Printers/printers");
		  }else{
			var ws2 = new WebSocket("ws://127.0.0.1:8080/Printers/printers");
			}

		ws2.onopen = function()
		{
			// Web Socket is connected, send data using send()
			ws2.send(obj_json);
			if (url!=0) {

				window.location.href = url;
			}

		};

		ws2.onmessage = function (evt)
		{

			var received_msg = evt.data;
			Swal.fire( lang['print_error_print']+received_msg );
		};

		ws2.onerror = function()
		{
			// websocket is closed.
			Swal.fire( lang['print_error_print_con']  );
		};

		ws2.onclose = function()
		{
			// websocket is closed.
			Swal.fire( lang['print_error_print_soft_start']  );
		};

}
else{
	// The browser doesn't support WebSocket
	Swal.fire( lang['print_error_print_browser']  );
}





}
