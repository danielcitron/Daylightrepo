$(document).ready(function() {
		//make blocks within

		$( ".board-blocks-draggable" ).sortable({ 
			opacity: .6,
			cursor: 'move',
			placeholder: 'placeholder-ui',
			tolerance: 'pointer',
			revert: 100,

			update: function(event, ui){
				var title = $(this).data('title');
				var item_ids = new Array;
				var i = 0;
				$(this).find('li').each(function(){
					item_ids.push($(this).data('id'));
				});
				$.post('/order', {title : title, item_ids : item_ids}, function(data){});

		}});

		//edit information within board
		$('.edit-board-title').editable('/edit/board/title', { 
	         name: 'newvalue',
	         indicator: '...',
	         tooltip: 'Edit Title',
	         onblur: 'submit',
	         submitdata: function (value, settings) {
			      return {
			           "origValue": this.revert
			      };
		      },
	     });

		$('.edit-board-description').editable('/edit/board/description', { 
	         name: 'newvalue',
	         indicator: '...',
	         tooltip: "Edit Description",
	         onblur: 'submit',
	         submitdata: function (value, settings) {
			      return {
			           "origValue": this.revert,
			           "boardId": $(this).data('id')
			      };
		      },
	     });

		$('.edit-block-title').editable('/edit/block/title', { 
	         name: 'newvalue',
	         indicator: '...',
	         tooltip: "Edit Block Title",
	         onblur: 'submit',
	         submitdata: function (value, settings) {
			      return {
			           "origValue": this.revert,
			           "blockId": $(this).data('id')
			      };
		      },
	     });

		$('.edit-block-text').editable('/edit/block/text', { 
	         name: 'newvalue',
	         indicator: '...',
	         tooltip: "Edit Block Text",
	         onblur: 'submit',
	         submitdata: function (value, settings) {
			      return {
			           "origValue": this.revert,
			           "blockId": $(this).data('id')
			      };
		      },
	     });

		$('.profile-boards-menu').click(function() {
			$('.profile-boards-menu').addClass('selected');
			$('.profile-blocks-menu').removeClass('selected');
			$('.profile-subscriptions-menu').removeClass('selected');
			$('.boards-initial-list').css("display", "block");
			$('.block-list').css("display", "none");
			$('.subscription-list').css("display", "none");
		});

		$('.profile-blocks-menu').click(function() {
			$('.profile-boards-menu').removeClass('selected');
			$('.profile-blocks-menu').addClass('selected');
			$('.profile-subscriptions-menu').removeClass('selected');
			$('.boards-initial-list').css("display", "none");
			$('.block-list').css("display", "block");
			$('.subscription-list').css("display", "none");
		});

		$('.profile-subscriptions-menu').click(function() {
			$('.profile-boards-menu').removeClass('selected');
			$('.profile-blocks-menu').removeClass('selected');
			$('.profile-subscriptions-menu').addClass('selected');
			$('.boards-initial-list').css("display", "none");
			$('.block-list').css("display", "none");
			$('.subscription-list').css("display", "block");
		});

		$('.subscriptions-href').click(function() {
			$('.profile-boards-menu').removeClass('selected');
			$('.profile-blocks-menu').removeClass('selected');
			$('.profile-subscriptions-menu').addClass('selected');
			$('.boards-initial-list').css("display", "none");
			$('.block-list').css("display", "none");
			$('.subscription-list').css("display", "block");
		});

		if (window.location.hash == "#subscriptions") {
		    $('.profile-boards-menu').removeClass('selected');
			$('.profile-blocks-menu').removeClass('selected');
			$('.profile-subscriptions-menu').addClass('selected');
			$('.boards-initial-list').css("display", "none");
			$('.block-list').css("display", "none");
		    $('.subscription-list').css("display", "block");
		}

		$('.discover-recent-tab').click(function() {
			$('.discover-recent-tab').addClass('selected');
			$('.discover-random-tab').removeClass('selected');
			$('.discover-list').removeClass('random-scroll');
		});

		$('.discover-random-tab').click(function() {
			$('.discover-recent-tab').removeClass('selected');
			$('.discover-random-tab').addClass('selected');
			$('.discover-list').addClass('random-scroll');
		});

		$('.create-new-block').click(function() {
			if ($('.create-new-block').hasClass('form-visible')) {
			    $('.create-new-container').removeClass('form-visible');
			    $('.create-new-container').addClass('form-hidden');
				$('.create-new-block').removeClass('form-visible');
				$('.add-container').removeClass('form-visible');
				$('.tab-text').addClass('selected');
				
				$('.tab-photos').removeClass('selected');
				$('.form-photo-container').addClass('hidden-container');
				$('.add-description').addClass('hidden-container');
				$('.photo-input').attr('data-validation', '');
				$('.block-form-content').attr('data-validation', 'required');
				$('.form-content-container').removeClass('hidden-container');
			}
			else {
				$('.create-new-container').addClass('form-visible');
				$('.create-new-container').removeClass('form-hidden');
				$('.create-new-block').addClass('form-visible');
				$('.add-container').addClass('form-visible');
			}
		});

		$('.home-register-button').click(function() {
			if ($('.input-subcontainer').hasClass('register')) {
			    $('.input-subcontainer').removeClass('register');
			}
			else {
				$('.input-subcontainer').addClass('register');
			}
		});

		$('.expand-block-width').click(function() {
			if ($('.block-comment-container').hasClass('expanded')) {
			    $('.block-comment-container').removeClass('expanded');
			}
			else {
				$('.block-comment-container').addClass('expanded');
			}
		});

		$('.tab-text').click(function() {
			$('.tab-text').addClass('selected');
			$('.tab-photos').removeClass('selected');
			$('.form-photo-container').addClass('hidden-container');
			$('.add-description').addClass('hidden-container');
			$('.photo-input').attr('data-validation', '');
			$('.block-form-content').attr('data-validation', 'required');
			$('.form-content-container').removeClass('hidden-container');
		});

		$('.tab-photos').click(function() {
			$('.tab-text').removeClass('selected');
			$('.tab-photos').addClass('selected');
			$('.form-photo-container').removeClass('hidden-container');
			$('.form-content-container').addClass('hidden-container');
			$('.photo-input').attr('data-validation', 'required');
			$('.block-form-content').attr('data-validation', '');
			$('.add-description').removeClass('hidden-container');
		});

		$('.add-description').click(function() {
			$('.form-content-container').removeClass('hidden-container');
			$('.add-description').addClass('hidden-container');
		});

		$.validate({
		});


		
});