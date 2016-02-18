(function() {
	tinymce.PluginManager.add('blockquote_cite', function( editor, url ) {
		editor.addButton( 'blockquote_cite', {
			title: 'Blockquote & Cite',
			icon: "icon dashicons-testimonial",
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert Blockquote and Citation',
					body: [
						{
							type: 'textbox',
							name: 'multilineQuote',
							label: 'Quotation',
							value: editor.selection.getContent(),
							multiline: true,
							minWidth: 300,
							minHeight: 100
						},
						{
							type: 'textbox',
							name: 'textboxCite',
							label: 'Cite',
							value: ''
						},
						{
							type: 'textbox',
							name: 'textboxURL',
							label: 'Cite URL',
							value: ''
						},
						{
							type: 'listbox',
							name: 'listboxAlign',
							label: 'Alignment',
							'values': [
								{text: 'None', value: ''},
								{text: 'Left', value: 'alignleft'},
								{text: 'Right', value: 'alignright'}
							]
						}
					],
					onsubmit: function( e ) {
						var cite, link = '';
						if ( e.data.textboxURL.trim() )
							cite = '<cite><a href="' + e.data.textboxURL.trim() + '">' + e.data.textboxCite.trim() + '</a></cite>';
						else if ( e.data.textboxCite.trim() )
							cite = '<cite>' + e.data.textboxCite.trim() + '</cite>';
						editor.insertContent( '<section  class="pullquote ' + e.data.listboxAlign + '"><blockquote>' + e.data.multilineQuote + cite + '</blockquote></section>');
					}
				});
			}
		});
	});
})();