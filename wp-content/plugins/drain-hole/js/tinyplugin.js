var TinyMCE_DrainholePlugin = {
	/**
	 * Returns information about the plugin as a name/value array.
	 * The current keys are longname, author, authorurl, infourl and version.
	 *
	 * @returns Name/value array containing information about the plugin.
	 * @type Array 
	 */
	getInfo : function() {
		return {
			longname : 'Drain Hole',
			author : 'John Godley',
			authorurl : 'http://urbangiraffe.com',
			infourl : 'http://urbangiraffe.com/plugins/drainhole/',
			version : "1.0"
		};
	},

	/**
	 * Gets executed when a TinyMCE editor instance is initialized.
	 *
	 * @param {TinyMCE_Control} Initialized TinyMCE editor control instance. 
	 */
	initInstance : function(inst) {
		// You can take out plugin specific parameters
//		alert("Initialization parameter:" + tinyMCE.getParam("somename_someparam", false));

		// Register custom keyboard shortcut
//		inst.addShortcut('ctrl', 't', 'lang_somename_desc', 'mceSomeCommand');
	},

	/**
	 * Gets executed when a TinyMCE editor instance is removed.
	 *
	 * @param {TinyMCE_Control} Removed TinyMCE editor control instance. 
	 */
	removeInstance : function(inst) {
		// Cleanup instance resources
	},

	/**
	 * Gets executed when a TinyMCE editor instance is displayed using for example mceToggleEditor command.
	 *
	 * @param {TinyMCE_Control} Visible TinyMCE editor control instance. 
	 */
	showInstance : function(inst) {
		// Show instance resources
	},

	/**
	 * Gets executed when a TinyMCE editor instance is hidden using for example mceToggleEditor command.
	 *
	 * @param {TinyMCE_Control} Hidden TinyMCE editor control instance. 
	 */
	hideInstance : function(inst) {
		// Hide instance resources
	},

	/**
	 * Returns the HTML code for a specific control or empty string if this plugin doesn't have that control.
	 * A control can be a button, select list or any other HTML item to present in the TinyMCE user interface.
	 * The variable {$editor_id} will be replaced with the current editor instance id and {$pluginurl} will be replaced
	 * with the URL of the plugin. Language variables such as {$lang_somekey} will also be replaced with contents from
	 * the language packs.
	 *
	 * @param {string} cn Editor control/button name to get HTML for.
	 * @return HTML code for a specific control or empty string.
	 * @type string
	 */
	getControlHTML : function(cn) {
		switch (cn) {
			case "drainhole":
				return tinyMCE.getButtonHTML(cn, 'lang_drainhole_insert', wp_base + '/images/tinymce.png', 'drainhole');
		}

		return "";
	},

	/**
	 * Executes a specific command, this function handles plugin commands.
	 *
	 * @param {string} editor_id TinyMCE editor instance id that issued the command.
	 * @param {HTMLElement} element Body or root element for the editor instance.
	 * @param {string} command Command name to be executed.
	 * @param {string} user_interface True/false if a user interface should be presented.
	 * @param {mixed} value Custom value argument, can be anything.
	 * @return true/false if the command was executed by this plugin or not.
	 * @type
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			// Remember to have the "mce" prefix for commands so they don't intersect with built in ones in the browser.
			case "drainhole":
  			var template = new Array();

  			template['file'] = wp_base + 'ajax.php?id=0&cmd=editor'; // Relative to theme
  			template['width'] = 450;
  			template['height'] = 70;

  			tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes"});
				return true;
		}

		// Pass to next handler in chain
		return false;
	},

	// Private plugin internal methods

	/**
	 * This is just a internal plugin method, prefix all internal methods with a _ character.
	 * The prefix is needed so they doesn't collide with future TinyMCE callback functions.
	 *
	 * @param {string} a Some arg1.
	 * @param {string} b Some arg2.
	 * @return Some return.
	 * @type string
	 */
	_someInternalFunction : function(a, b) {
		return 1;
	}
};

// Adds the plugin class to the list of available TinyMCE plugins
tinyMCE.addPlugin ("drainhole", TinyMCE_DrainholePlugin);

// UK lang variables
tinyMCE.addToLang('',{ drainhole_insert : 'Insert Drain Hole tag'});
