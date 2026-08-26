	// @CONTACT FORM - TRANSLATE OR EDIT
	_agree_name				= '개인정보 수집에 동의해 주세요.',
	_email_name				= '이름을 입력해 주세요.',
	_email_required			= '이메일을 입력해 주세요.',
	_phone_required			= '핸드폰번호를 입력해 주세요.',
	_subject_required		= '제목을 입력해 주세요.',
	_email_message			= '내용을 입력해 주세요.',
	_email_all				= '입력하신 내용을 확인해 주세요.',
	_email_invalid			= '이메일 주소가 잘못되었습니다.',
	_email_sent				= '이메일이 발송되었습니다.';


	/**	CONTACT FORM
	*************************************************** **/
	jQuery("#contact_submit").bind("click", function(e) {
		e.preventDefault();

		var agree 			= jQuery("#agree").is(':checked'),					// required
			contact_name 	= jQuery("#contact_name").val(),			// required
			contact_email 	= jQuery("#contact_email").val(),			// required
			contact_phone 	= jQuery("#contact_phone").val(),			// required
			contact_subject = jQuery("#contact_subject").val(),			// required
			contact_message = jQuery("#contact_message").val(),			// required
			_action			= jQuery("#contactForm").attr('action'),	// form action URL
			_method			= jQuery("#contactForm").attr('method'),	// form method
			_err			= false;									// status

		// Remove error tooltips
		//jQuery("#contactForm span.tooltip_error").remove();

		// Agree Check
		if(!agree) {
			jQuery("#alertErrResponse").empty().append(_agree_name);
			jQuery("#alertErr").show();
			alert('개인정보 수집 및 이용에 관한 동의해주세요.');
			return false;
		}

		// Name Check
		if(contact_name == '') {
			jQuery("#alertErrResponse").empty().append(_email_name);
			jQuery("#alertErr").show();

			return false;
		}

		// Email Check
		if(contact_email == '') {
			jQuery("#alertErrResponse").empty().append(_email_required);
			jQuery("#alertErr").show();
			return false;
		}

		// Phone Check
		if(contact_phone == '') {
			jQuery("#alertErrResponse").empty().append(_phone_required);
			jQuery("#alertErr").show();
			return false;
		}

		// Subject Check
		if(contact_subject == '') {
			jQuery("#alertErrResponse").empty().append(_subject_required);
			jQuery("#alertErr").show();
			return false;
		}

		// Comment Check
		if(contact_message == '') {
			jQuery("#alertErrResponse").empty().append(_email_message);
			jQuery("#alertErr").show();
			return false;
		}


		// SEND MAIL VIA AJAX
		$.ajax({
			url: 	_action,
			data: 	{ajax:"true", action:'email_send', contact_name:contact_name, contact_email:contact_email, contact_phone:contact_phone, contact_subject:contact_subject, contact_message:contact_message},
			type: 	_method,
			error: 	function(XMLHttpRequest, textStatus, errorThrown) {

				alert('Server Internal Error'); // usualy on headers 404

			},

			success: function(data) {
				data = data.trim(); // remove output spaces


				// PHP RETURN: Mandatory Fields
				if(data == '_required_') {
					//jQuery("#alertErrResponse").empty().append(_email_all);
					jQuery("#alertErr").show();
				} else

				// PHP RETURN: INVALID EMAIL
				if(data == '_invalid_email_') {
					jQuery("#alertErrResponse").empty().append(_email_invalid);
					jQuery("#alertErr").show();
				} else

				// VALID EMAIL
				if(data == '_sent_ok_') {

					// hide error warning if visible
					jQuery("#alertErr").hide();

					jQuery("#alertOkResponse").empty().append(_email_sent);
					jQuery("#alertOk").show();

					// reset form
					jQuery("#contact_name, #contact_email, #contact_phone, #contact_subject, #contact_message").val('');

				} else {

					// PHPMAILER ERROR
					alert(data); 

				}
			}
		});

	});

	/* ========================================================================
	 * Bootstrap: alert.js v3.2.0
	 * http://getbootstrap.com/javascript/#alerts
	 * ========================================================================
	 * Copyright 2011-2014 Twitter, Inc.
	 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
	 * ======================================================================== */

	+function ($) {
	  'use strict';

	  // ALERT CLASS DEFINITION
	  // ======================

	  var dismiss = '[data-dismiss="alert"]'
	  var Alert   = function (el) {
		$(el).on('click', dismiss, this.close)
	  }

	  Alert.VERSION = '3.2.0'

	  Alert.prototype.close = function (e) {
		var $this    = $(this)
		var selector = $this.attr('data-target')

		if (!selector) {
		  selector = $this.attr('href')
		  selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') // strip for ie7
		}

		var $parent = $(selector)

		if (e) e.preventDefault()

		if (!$parent.length) {
		  $parent = $this.hasClass('alert') ? $this : $this.parent()
		}

		$parent.trigger(e = $.Event('close.bs.alert'))

		if (e.isDefaultPrevented()) return

		$parent.removeClass('in')

		function removeElement() {
		  // detach from parent, fire event then clean up data
		  $parent.detach().trigger('closed.bs.alert').remove()
		}

		$.support.transition && $parent.hasClass('fade') ?
		  $parent
			.one('bsTransitionEnd', removeElement)
			.emulateTransitionEnd(150) :
		  removeElement()
	  }


	  // ALERT PLUGIN DEFINITION
	  // =======================

	  function Plugin(option) {
		return this.each(function () {
		  var $this = $(this)
		  var data  = $this.data('bs.alert')

		  if (!data) $this.data('bs.alert', (data = new Alert(this)))
		  if (typeof option == 'string') data[option].call($this)
		})
	  }

	  var old = $.fn.alert

	  $.fn.alert             = Plugin
	  $.fn.alert.Constructor = Alert


	  // ALERT NO CONFLICT
	  // =================

	  $.fn.alert.noConflict = function () {
		$.fn.alert = old
		return this
	  }


	  // ALERT DATA-API
	  // ==============

	  $(document).on('click.bs.alert.data-api', dismiss, Alert.prototype.close)

	}(jQuery);