define(function(require) {
	var elgg = require("elgg");
	var $ = require("jquery");

	var Ajax = require('elgg/Ajax');
	var ajax = new Ajax(false);

	function gifts_previewImage(ImageID) {
		// Check if Image file is available!!!
		ajax.action('gifts/ajaxImage', {
			data: {
				ImageID: ImageID
			}
		}).done(function(json, status, jqXHR) {
			if (jqXHR.AjaxData.status == -1) {
				return;
			}

			if (json.success) {
				$('#gift_preview').html(json.html);
			}
		});

		// Need here a dynamic function to check userpoints
		var useuserpoints = $('#gift_id').data('useuserpoints');
		if (useuserpoints == '1') {
			var points = $('#gift_id').data('points');
			calculateUserpoints(ImageID, points);
		}
	}

	function calculateUserpoints(GiftID, Points) {
		// Calculating Userpoints and display send button when point are enough
		// Else display error message
		ajax.action('gifts/ajaxGetPoints', {
			data: {
				GiftID: GiftID
			}
		}).done(function(json, status, jqXHR) {
			if (jqXHR.AjaxData.status == -1) {
				return;
			}

			if (json.success) {
				var Cost = json.points;
				if(Cost <= Points) {
					// Add hidden field with the cost of this gift
					var code = '<input type="hidden" name="giftcost" value="' + Cost + '" /><input class="elgg-button elgg-button-submit" type="submit" value="' + elgg.echo('gifts:send') + '"/>';
					$('#gift_cost').html(elgg.echo('gifts:pointscost') + Cost + elgg.echo('gifts:pointscostafter'));
					$('#sendButton').html(code);
				} else {
					var code = '<label>' + elgg.echo('gifts:notenoughpoints') + '</label>';
					$('#gift_cost').html(elgg.echo('gifts:pointscost') + Cost + elgg.echo('gifts:pointscostafter'));
					$('#sendButton').html(code);
				}
			}
		});
	}

	function validateForm() {
		var result = true;
		var focus = false;
		var msg = "";

		var receiver = $('#send_to').val();

		if (receiver) {
			return result;
		} else {
			receiver = $("select[name='send_to']").val();

			if ((receiver == "") || (receiver == 0)) {
				result = false;
				msg = elgg.echo("gifts:blank");
				$('#send_to').focus();
				focus = true;
			}
		}

		if (!result) {
			alert(msg);
		}

		return result;
	}

	$(document).ready(function() {
		gifts_previewImage(1);
	});

	$(document).on('submit', '#gift_send_form', function() {
		return validateForm();
	});
	$('#gift_id').change(function() {
		gifts_previewImage($(this).children('option:selected').val());
	});
});
