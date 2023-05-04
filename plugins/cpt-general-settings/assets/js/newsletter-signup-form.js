(function ($) {
	var $nsf_document = $(document);
	$nsf_document.ready(function () {

        $nsf_document.on('click', '.nsf-container #nsf-form .nsf-form-submit', function(event) {

            event.preventDefault();

            var name 						= $(".nsf-first-name").val();
            var email 						= $(".nsf-email").val();
            var nsf_last_name 				= $("#nsf-last-name").val();
            var nsf_subscription_attributes = $("#nsf_subscription_attributes").val();
            var nsf_subscription_ID 		= $("#nsf_subscription_ID").val();
            var nsf_mailing_list_name 		= $("#nsf_mailing_list_name").val();
            var nsf_mailing_list_description = $("#nsf_mailing_list_description").val();
            var nsf_template_token 			= $("#nsf_template_token").val();

            if (name == "") {
                $(".nsf-first-name").focus();
                return false;
            }

            if (email != "") {
                var pattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
                if (!pattern.test(email)) {
                    $('.nsf-email-error-msg').text('invalid email');
                    $(".nsf-email").focus();
                    $(this).val("");
                    $(this).focus();
                    return false;
                }
            }else{
                $(".nsf-email").focus();
                return false;
            }

            $(this).prop('disabled', true).css('opacity', 0.5).css('cursor', 'not-allowed');

            $.ajax({
				type : 'POST',
                dataType: 'json',
				url : nsf_ajax_object.url,
				data : {
					action: 'newsletter_signup_form_data_submit',
                    nonce: nsf_ajax_object.nonce,
                    name : name,
                    email : email,
                    nsf_last_name : nsf_last_name,
                    nsf_subscription_attributes : nsf_subscription_attributes,
                    nsf_subscription_ID : nsf_subscription_ID,
                    nsf_mailing_list_name : nsf_mailing_list_name,
                    nsf_mailing_list_description : nsf_mailing_list_description,
                    nsf_template_token : nsf_template_token,
				},
                beforeSend: function() {
                    $('.nsf-container #nsf-form .nsf-form-submit').prop('disabled', true).css('opacity', 0.5).css('cursor', 'not-allowed');
                },
				success : function( response ) {
					console.log(response.body);
				},
				error : function( error ) {
					console.log(error);
				},
                complete: function() {
                    $('.nsf-container #nsf-form .nsf-form-submit').prop('disabled', false).css('opacity', 1).css('cursor', 'pointer');
                }
			});

        });

    });
})(jQuery);
