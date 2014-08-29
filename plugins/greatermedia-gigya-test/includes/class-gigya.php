<?php
class GMI_Gigya {

	public static function hooks() {
		add_action( 'wp_head', array( __CLASS__, 'gigya_script' ) );
	}

	public static function gigya_script() {
		?>
		<!-- socialize.js script should only be included once -->

		<script type='text/javascript' src='http://cdn.gigya.com/JS/socialize.js?apiKey=3_e_T7jWO0Vjsd9y0WJcjnsN6KaFUBv6r3VxMKqbitvw-qKfmaUWysQKa1fra5MTb6'>
			{
				enabledProviders: 'facebook,twitter,linkedin,yahoo,messenger'
			}
		</script>

		<script>

			// register for login event
			gigya.socialize.addEventHandlers({
				context: { str: 'congrats on your' }
				, onLogin: onLoginHandler
			});

			// onLogin Event handler
			function onLoginHandler(eventObj) {
				alert(eventObj.context.str + ' ' + eventObj.eventName + ' to ' + eventObj.provider
					+ '!\n' + eventObj.provider + ' user ID: ' +  eventObj.user.identities[eventObj.provider].providerUID);
				// verify the signature ...
				verifyTheSignature(eventObj.UID, eventObj.timestamp, eventObj.signature);

				// Check whether the user is new by searching if eventObj.UID exists in your database
				var newUser = true; // lets assume the user is new

				if (newUser) {
					// 1. Register user
					// 2. Store new user in DB
					// 3. link site account to social network identity
					//   3.1 first construct the linkAccounts parameters
					var dateStr = Math.round(new Date().getTime()/1000.0); // Current time in Unix format
					//(i.e. the number of seconds since Jan. 1st 1970)

					var siteUID = 'uTtCGqDTEtcZMGL08w'; // siteUID should be taken from the new user record
					// you have stored in your DB in the previous step
					var yourSig = createSignature(siteUID, dateStr);

					var params = {
						siteUID: siteUID,
						timestamp:dateStr,
						cid:'',
						signature:yourSig
					};

					//   3.1 call linkAccounts method:
					gigya.socialize.notifyRegistration(params);
				}

				// Success- hide register form and show Gravity Form
				document.getElementById('gigya-login-wrap').style.display = 'none';
				document.getElementsByClassName('hide')[0].className = '';

			}

			// Note: the actual signature calculation implementation should be on server side
			function createSignature(UID, timestamp) {
				encodedUID = encodeURIComponent(UID); // encode the UID parameter before sending it to the server.
				// On server side use decodeURIComponent() function to decode an encoded UID
				return '';
			}

			// Note: the actual signature calculation implementation should be on server side
			function verifyTheSignature(UID, timestamp, signature) {
				encodedUID = encodeURIComponent(UID); // encode the UID parameter before sending it to the server.
				// On server side use decodeURIComponent() function to decode an encoded UID
				alert('Your UID: ' + UID + '\n timestamp: ' + timestamp + '\n signature: ' + signature + '\n Your UID encoded: ' + encodedUID);
			}

		</script>

<?php
	}
}