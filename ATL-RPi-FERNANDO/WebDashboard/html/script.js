//Extended from TheFreeElectron 2015, http://www.instructables.com/member/TheFreeElectron/
//BenShorey 2016, http://www.instructables.com/member/BenS226/
//Uses gpio.php to change pin modes and values
//Also uses gpio.php to get pin status and update buttons
//Associated with index.php to display table of pin modes and values

// following array used to convert output of wiringPi's getAlt() funtion into text for pinMode
pinModes = ["IN", "OUT", "ALT5", "ALT4", "ALT0", "ALT1", "ALT2", "ALT3"];

get_status();

function toggle_update() {
	if (document.getElementById("update_checkbox").checked) {
		// if checkbox ticked then run update
		get_status();
	} else {
		// if checkbox not ticked then clear any running Timeouts and change status
		window.clearTimeout(timerId);
		document.getElementById("workspace").innerHTML = "Static table";
	}
}

function change_pin_value (pin, value) {
	console.log("Pin: "+ pin+ " value: " + value);
	var data = 0;
	//send the pin number to gpio.php for changes
	//this is the http request
	var request = new XMLHttpRequest();

	//receiving information
	request.onreadystatechange = function () {
		//successful response
		if (request.readyState == 4 && request.status == 200) {
			data = request.responseText;
			console.log(data);
			// gpio.php returns "success" if pin changed successfully
			if ( !data.localeCompare("success") ){
				// run get_status() to update table
				get_status();
				return ("success");
			} else {
				return ("fail"); 
			}
		}
		//test if server fail
		else if (request.readyState == 4 && request.status == 500) {
			alert ("server error");
			return ("fail");
		}
		//else 
		else if (request.readyState == 4 && request.status != 200 && request.status != 500 ) { 
			alert ("Something went wrong!");
			return ("fail");
		}
	}
	request.open( "GET" , "gpio.php?pin=" + pin + "&value=" + value, true);
	request.send();
	
return 0;
}

function change_pin_mode (pin, mode) {
	console.log("Pin: "+ pin+ " mode: " + mode);
	var data = 0;
	//send the pin number to gpio.php for changes
	//this is the http request
	var request = new XMLHttpRequest();

	//receiving information
	request.onreadystatechange = function () {
		if (request.readyState == 4 && request.status == 200) {
			data = request.responseText;
			console.log(data);
			// gpio.php returns "success" if mode changed successfully
			if ( !data.localeCompare("success") ){
				// run get_status() to update table
				get_status();
				return ("success");
			} else {
				return ("fail"); 
			}
		}
		//test if server fail
		else if (request.readyState == 4 && request.status == 500) {
			alert ("server error");
			return ("fail");
		}
		//else 
		else if (request.readyState == 4 && request.status != 200 && request.status != 500 ) { 
			alert ("Something went wrong!");
			return ("fail");
		}
	}
	request.open( "GET" , "gpio.php?pin=" + pin + "&mode=" + mode, true);
	request.send();
	
return 0;
}

// holds id of timeOut timer. Used to cancel when stopping update
var timerId = 0;
var startup_flag = true;

function get_status() {
	var data = 0;
	// start timer to see how long process
	t1 = (new Date()).getTime();
	document.getElementById("workspace").innerHTML = "Updating...";
		//this is the http request to get full status
		var request = new XMLHttpRequest();

		//receiving informations
		request.onreadystatechange = function () {
			if (request.readyState == 4 && request.status == 200) {
				data = request.responseText;
				// response in this case is a space delimited string
				// values 1 - 28 (BCM) are values of each pin
				// values 30 - 57 (BCM) are values for each pins mode
				// see pinmodes array for how this value correlates to the mode of the pin
				data_arr = data.split(" ");
				for (n = 0; n < 28; n++) {
					// for each pin
					value_button = document.getElementById("value" + n);
					if (value_button != null) {
						// set value button text
						value_button.value = (data_arr[n+1] == 1 ? "HIGH" : "LOW");
						// set class for high/low pin
						value_button.className = data_arr[n+1] == 1 ? "buttonHigh" : "buttonLow";
						// change function of button
						newvalue = 1 - data_arr[n+1]; // new value is opposite to current value
						value_button.onclick = new Function("change_pin_value("+n+", "+newvalue+")");
					}
					mode_button = document.getElementById("mode" + n);
					if (mode_button != null) {
						// set mode button text
						mode_button.value = pinModes[data_arr[n+30]];
						// change function of button
						// not sure about safety of changing pins to ALT modes on the fly so at present just toggles between input and output
						newvalue = data_arr[n+30] > 0 ? 0 : 1; 
						mode_button.onclick = new Function("change_pin_mode("+n+", "+newvalue+")");
					}
					// end timer
					t2 = (new Date()).getTime();
					// display how long request took
					document.getElementById("workspace").innerHTML = "Done in " + (t2 - t1) + " milliseconds";	
				}
				request = null;

				if (document.getElementById("update_checkbox").checked) {
					// if auto update check box is checked, clear existing timer and start a new one
					window.clearTimeout(timerId);
					timerId = window.setTimeout(get_status, 500);
				}
			}
			//test if server fail
			else if (request.readyState == 4 && request.status == 500) {
				//alert ("server error");
				dElementById("mode" + n);
					if (mode_button != null) ocument.getElementById("workspace").innerHTML = "Server error (500 status)";	

				return ("fail");
			}
			//else 
			else if (request.readyState == 4 && request.status != 200 && request.status != 500 ) { 
				//alert ("Something went wrong!");
				document.getElementById("workspace").innerHTML = "General error";	
				return ("fail");
			}
		}	
		request.open( "GET" , "gpio.php?t=" + Math.random(), true);
		request.send();
	return 0;	
}