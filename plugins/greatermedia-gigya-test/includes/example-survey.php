<?php

/**
 * If shortcode has been included, then load up required js
 */
function print_my_script() {
	global $add_my_script;
	if ( ! $add_my_script ) {
		return;
	}
	?>
	<script>
		gigya.accounts.showScreenSet({
			screenSet  : "simple-screen-set",
			startScreen: "registration-screen",
			containerID: "gigya-controls"
		})
	</script>
<?php
}
add_action( 'wp_footer', 'print_my_script' );

/**
 * [gmr-survey-form] shortcode - display basic Gigya signup form w GMR custom fields
 */
function gmr_survey_form() {

	global $add_my_script;
	$add_my_script = true;

	$gigya_form = '<div class="gigya-screen-set" id="simple-screen-set" data-width="100%" data-height="600" style="display: none" data-on-pending-registration-screen="edit-profile">
						<div class="gigya-screen" id="registration-screen" data-width="100%" data-height="735">
							<div class="right-col">
								<h2>Signup for a Gigya Account:</h2>
								<form class="gigya-register-form" data-on-success-screen="edit-profile">
									<p>First Name:</p>
									<input type="text" name="firstName" style="width:300">
									<div class="gigya-error-msg" data-bound-to="firstName"></div>

									<p >Last Name:</p>
									<input type="text" name="lastName" style="width:300">
									<div class="gigya-error-msg" data-bound-to="lastName"></div>

									<p >Email address:</p>
									<input type="text" name="email" style=" width:180">&nbsp;&nbsp;<div class="gigya-loginID-availability" style="display: inline-block"></div>
									</br><span class="gigya-error-msg" data-bound-to="email"></span>
									</br>
									<p >Password:</p>
									<input type="password" name="password" style=" width:180">&nbsp;&nbsp;<div class="gigya-password-strength" data-bound-to="password" data-on-focus-bubble="true"></div>
									<span class="gigya-error-msg" data-bound-to="password"></span>
									</br>
									<p>Retype Password:</p>
									<input type="password" name="passwordRetype" style=" width:180">
									</br><span class="gigya-error-msg" data-bound-to="passwordRetype"></span>
									</br></br>
									<hr>
									<h2>About your favorite radio station:</h2>
									<p>What is your listening loyalty?</p>
									<input type="radio" name="data.listeningLoyalty" value="Only this station">Only this station<br>
									<input type="radio" name="data.listeningLoyalty" value="This and 2-3 other stations">I listen to this and 2-3 other stations<br>
									<input type="radio" name="data.listeningLoyalty" value="More than 3 other stations">I listen to more than 3 other stations<br>
									<div class="gigya-error-msg" data-bound-to="data.listeningLoyalty"></div>
									<p>How often do you listen to this station?</p>
									<input type="radio" name="data.listeningFrequency" value="Several times daily">Several times daily<br>
									<input type="radio" name="data.listeningFrequency" value="Once per day">Once per day<br>
									<input type="radio" name="data.listeningFrequency" value="Several times per week">Several times per week<br>
									<input type="radio" name="data.listeningFrequency" value="Once per week or less">Once per week or less<br>
									<div class="gigya-error-msg" data-bound-to="data.listeningFrequency"></div>
									</br></br>
									<div class="gigya-captcha" style="">
									</div>
									</br>
									<input type="submit" value="Register"></br>
								</form>
								<span class="gigya-error-display" data-bound-to="gigya-register-form" data-scope="all-errors" >
									<span class="gigya-error-msg" data-scope="all-errors" data-bound-to="gigya-register-form"></span>
								</span>
						</div>
				</div>
			</div>
			<div id="gigya-controls"></div>';
	return $gigya_form;
}
add_shortcode( 'gmr-survey-form', 'gmr_survey_form' );

