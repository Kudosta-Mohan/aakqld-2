(function () {

var defs = {}; // id -> {dependencies, definition, instance (possibly undefined)}

// Used when there is no 'main' module.
// The name is probably (hopefully) unique so minification removes for releases.
var register_3795 = function (id) {
  var module = dem(id);
  var fragments = id.split('.');
  var target = Function('return this;')();
  for (var i = 0; i < fragments.length - 1; ++i) {
    if (target[fragments[i]] === undefined)
      target[fragments[i]] = {};
    target = target[fragments[i]];
  }
  target[fragments[fragments.length - 1]] = module;
};

var instantiate = function (id) {
  var actual = defs[id];
  var dependencies = actual.deps;
  var definition = actual.defn;
  var len = dependencies.length;
  var instances = new Array(len);
  for (var i = 0; i < len; ++i)
    instances[i] = dem(dependencies[i]);
  var defResult = definition.apply(null, instances);
  if (defResult === undefined)
     throw 'module [' + id + '] returned undefined';
  actual.instance = defResult;
};

var def = function (id, dependencies, definition) {
  if (typeof id !== 'string')
    throw 'module id must be a string';
  else if (dependencies === undefined)
    throw 'no dependencies for ' + id;
  else if (definition === undefined)
    throw 'no definition function for ' + id;
  defs[id] = {
    deps: dependencies,
    defn: definition,
    instance: undefined
  };
};

var dem = function (id) {
  var actual = defs[id];
  if (actual === undefined)
    throw 'module [' + id + '] was undefined';
  else if (actual.instance === undefined)
    instantiate(id);
  return actual.instance;
};

var req = function (ids, callback) {
  var len = ids.length;
  var instances = new Array(len);
  for (var i = 0; i < len; ++i)
    instances.push(dem(ids[i]));
  callback.apply(null, callback);
};

var ephox = {};

ephox.bolt = {
  module: {
    api: {
      define: def,
      require: req,
      demand: dem
    }
  }
};

var define = def;
var require = req;
var demand = dem;
// this helps with minificiation when using a lot of global references
var defineGlobal = function (id, ref) {
  define(id, [], function () { return ref; });
};
/*jsc
["tinymce/inlite/Theme","global!tinymce.ThemeManager","global!tinymce.util.Delay","tinymce/inlite/ui/Panel","tinymce/inlite/ui/Buttons","tinymce/inlite/core/SkinLoader","tinymce/inlite/core/SelectionMatcher","tinymce/inlite/core/ElementMatcher","tinymce/inlite/core/Matcher","tinymce/inlite/alien/Arr","tinymce/inlite/core/PredicateId","global!tinymce.util.Tools","global!tinymce.ui.Factory","global!tinymce.DOM","tinymce/inlite/ui/Toolbar","tinymce/inlite/ui/Forms","tinymce/inlite/core/Measure","tinymce/inlite/core/Layout","tinymce/inlite/file/Conversions","tinymce/inlite/file/Picker","tinymce/inlite/core/Actions","global!tinymce.EditorManager","global!tinymce.util.Promise","tinymce/inlite/alien/Uuid","tinymce/inlite/alien/Unlink","tinymce/inlite/core/UrlType","global!tinymce.geom.Rect","tinymce/inlite/core/Convert","tinymce/inlite/alien/Bookmark","global!tinymce.dom.TreeWalker","global!tinymce.dom.RangeUtils"]
jsc*/
defineGlobal("global!tinymce.ThemeManager", tinymce.ThemeManager);
defineGlobal("global!tinymce.util.Delay", tinymce.util.Delay);
defineGlobal("global!tinymce.util.Tools", tinymce.util.Tools);
defineGlobal("global!tinymce.ui.Factory", tinymce.ui.Factory);
defineGlobal("global!tinymce.DOM", tinymce.DOM);
/**
 * Toolbar.js
 *
 * Released under LGPL License.
 * Copyright (c) 1999-2016 Ephox Corp. All rights reserved
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

define('tinymce/inlite/ui/Toolbar', [
	'global!tinymce.util.Tools',
	'global!tinymce.ui.Factory'
], function (Tools, Factory) {
	var setActiveItem = function (item, name) {
		return function(state, args) {
			var nodeName, i = args.parents.length;

			while (i--) {
				nodeName = args.parents[i].nodeName;
				if (nodeName == 'OL' || nodeName == 'UL') {
					break;
				}
			}

			item.active(state && nodeName == name);
		};
	};

	var getSelectorStateResult = function (itemName, item) {
		var result = function (selector, handler) {
			return {
				selector: selector,
				handler: handler
			};
		};

		var activeHandler = function(state) {
			item.active(state);
		};

		var disabledHandler = function (state) {
			item.disabled(state);
		};

		if (itemName == 'bullist') {
			return result('ul > li', setActiveItem(item, 'UL'));
		}

		if (itemName == 'numlist') {
			return result('ol > li', setActiveItem(item, 'OL'));
		}

		if (item.settings.stateSelector) {
			return result(item.settings.stateSelector, activeHandler);
		}

		if (item.settings.disabledStateSelector) {
			return result(item.settings.disabledStateSelector, disabledHandler);
		}

		return null;
	};

	var bindSelectorChanged = function (editor, itemName, item) {
		return function () {
			var result = getSelectorStateResult(itemName, item);
			if (result !== null) {
				editor.selection.selectorChanged(result.selector, result.handler);
			}
		};
	};

	var create = function (editor, name, items) {
		var toolbarItems = [], buttonGroup;

		if (!items) {
			return;
		}

		Tools.each(items.split(/[ ,]/), function(item) {
			var itemName;

			if (item == '|') {
				buttonGroup = null;
			} else {
				if (Factory.has(item)) {
					item = {type: item};
					toolbarItems.push(item);
					buttonGroup = null;
				} else {
					if (!buttonGroup) {
						buttonGroup = {type: 'buttongroup', items: []};
						toolbarItems.push(buttonGroup);
					}

					if (editor.buttons[item]) {
						itemName = item;
						item = editor.buttons[itemName];

						if (typeof item == 'function') {
							item = item();
						}

						item.type = item.type || 'button';

						item = Factory.create(item);
						item.on('postRender', bindSelectorChanged(editor, itemName, item));
						buttonGroup.items.push(item);
					}
				}
			}
		});

		return Factory.create({
			type: 'toolbar',
			layout: 'flow',
			name: name,
			items: toolbarItems
		});
	};

	return {
		create: create
	};
});

defineGlobal("global!tinymce.util.Promise", tinymce.util.Promise);
/**
 * Uuid.js
 *
 * Released under LGPL License.
 * Copyright (c) 1999-2016 Ephox Corp. All rights reserved
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/**
 * Generates unique ids this is the same as in core but since
 * it's not exposed as a global we can't access it.
 */
define("tinymce/inlite/alien/Uuid", [
], function() {
	var count = 0;

	var seed = function () {
		var rnd = function () {
			return Math.round(Math.random() * 0xFFFFFFFF).toString(36);
		};

		return 's' + Date.now().toString(36) + rnd() + rnd() + rnd();
	};

	var uuid = function (prefix) {
		return prefix + (count++) + seed();
	};

	return {
		uuid: uuid
	};
});

/**
 * Bookmark.js
 *
 * Released under LGPL License.
 * Copyright (c) 1999-2016 Ephox Corp. All rights reserved
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

define('tinymce/inlite/alien/Bookmark', [
], function () {
	/**
	 * Returns a range bookmark. This will convert indexed bookmarks into temporary span elements with
	 * index 0 so that they can be restored properly