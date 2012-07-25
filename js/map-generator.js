// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.mapgen', {
		// creates control instances based on the control's id.
		// our button's id is "mapgen_button"
		createControl : function(id, controlManager) {
			if (id == 'mapgen_button') {
				// creates the button
				var button = controlManager.createButton('mapgen_button', {
					title : 'Google Maps & Street View Shortcode', // title of the button
					image : '../wp-content/plugins/map-generator/images/button.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = "320px";
						tb_show( 'Map-Generator.net Shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=mapgen-form' );
						tinymce.DOM.setStyle(["TB_window", "TB_load"], "max-height", H, "width", "300px")
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('mapgen', tinymce.plugins.mapgen);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="mapgen-form">\
		<p>Visit <a href="http://www.map-generator.net" target="_blank">map-generator.net</a> and create your own googlemap or Street View Scene. <br>\
		After hitting the createbutton you get a link. Insert that link or its id in the form below.</p>\
		<table id="mapgen-table" class="form-table">\
			<tr>\
				<th><label for="mapgen-url">ID or Link</label></th>\
				<td><input type="text" id="mapgen-url" name="url" value="12038" /><br />\
				<small>get your own from <a href="http://www.map-generator.net" target="_blank">map-generator.net</a></small></td>\
			</tr>\
			<tr>\
				<th><label for="mapgen-height">Height</label></th>\
				<td><input type="text" name="height" id="mapgen-height" value="300px" /><br />\
				<small>specify the height of your map</small>\
			</tr>\
		  <tr>\
				<th><label for="mapgen-width">Width</label></th>\
				<td><input type="text" name="width" id="mapgen-width" value="600px" /><br />\
				<small>specify the width of your map</small>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="mapgen-submit" class="button-primary" value="Insert Gallery" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#mapgen-submit').click(function(){
			// defines the options and their default values
			// again, this is not the most elegant way to do this
			// but well, this gets the job done nonetheless
			var options = { 
				'url'         : '',
				'height'       : '',
				'width'    : ''
				};
			var shortcode = '[mapgen';
			
			for( var index in options) {
				var value = table.find('#mapgen-' + index).val();
				
				// attaches the attribute to the shortcode only if it's different from the default value
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += ']';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()