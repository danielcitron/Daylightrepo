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

		
});