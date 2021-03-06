(function (scope) {
var ephox = scope.ephox = scope.ephox || {};
var bolt = ephox.bolt = ephox.bolt || {};

var def = function (deps, factory) {
    return factory.apply(null, deps);
};
var kernel = bolt.kernel = bolt.kernel || {};
kernel.api = kernel.api || {};
kernel.async = kernel.api || {};
kernel.fp = kernel.fp || {};
kernel.modulator = kernel.modulator || {};
kernel.module = kernel.module || {};
kernel.fp.array = def(
  [
  ],

  function () {
    var equals = function (a1, a2) {
      if (a1.length !== a2.length)
        return false;
      for (var i = 0; i < a1.length; ++i)
        if (a1[i] !== a2[i])
          return false;
      return true;
    };

    var forall = function (a, f) {
      var fn = f || function (x) {
        return x === true;
      };
      for (var i = 0; i < a.length; ++i)
        if (fn(a[i]) !== true)
          return false;
      return true;
    };

    var map = function (a, f) {
      var r = [];
      for (var i = 0; i < a.length; ++i)
        r.push(f(a[i], i));
      return r;
    };

    var flatten = function (a) {
      var r = [];
      for (var i = 0; i < a.length; ++i)
        r = r.concat(a[i]);
      return r;
    };

    var flatmap = function (a, f) {
      return flatten(map(a, f));
    };

    var filter = function (a, f) {
      var r = [];
      for (var i = 0; i < a.length; ++i)
        if (f(a[i]))
          r.push(a[i]);
      return r;
    };

    var each = map;

    var contains = function (a, x) {
      return !forall(a, function (v) {
        return v !== x;
      });
    };

    var indexof = function (a, x) {
      for (var i = 0; i < a.length; ++i)
        if (a[i] === x)
          return i;
      return -1;
    };

    return {
      equals: equals,
      forall: forall,
      map: map,
      flatten: flatten,
      flatmap: flatmap,
      filter: filter,
      each: each,
      contains: contains,
      indexof: indexof
    };
  }
);
kernel.fp.object = def(
  [
  ],

  function () {
    var map = function (o, f) {
      var r = {};
      for (var i in o)
        if (o.hasOwnProperty(i))
          r[i] = f(i, o[i]);
      return r;
    };

    var each = map;

    var merge = function (d, s) {
      each(s, function (k, v) {
        d[k] = v;
      });
    };

    var keys = function (o) {
      var r = [];
      each(o, function (k) {
        r.push(k);
      });
      return r;
    };

    return {
      each: each,
      keys: keys,
      map: map,
      merge: merge
    };
  }
);
kernel.fp.functions = def(
  [
  ],

  function () {
    var curry = function (f) {
      var slice = Array.prototype.slice;
      var args = slice.call(arguments, 1);
      return function () {
        var all = args.concat(slice.call(arguments, 0));
        return f.apply(null, all);
      };
    };

    var not = function (z) {
      return function () {
        var slice = Array.prototype.slice;
        return !z.apply(null, slice.call(arguments, 0));
      };
    };

    var apply = function (f) {
      var slice = Array.prototype.slice;
      return f.apply(null, slice.call(arguments, 0));
    };

    return {
      curry: curry,
      not: not,
      apply: apply
    };
  }
);kernel.async.map = def(
  [
    kernel.fp.array
  ],

  function (ar) {
    var amap = function (data, f, oncomplete) {
      var total = data.length;
      var count = 0;
      var results = [];

      ar.each(data, function (datum, i) {
        f(datum, function (result) {
          ++count;
          results[i] = result;
          if (count === total)
            oncomplete(results);
        });
      });
    };

    return {
      amap: amap
    };
  }
);
/**
 * This module has a dual responsibility:
 *  1. Ensures that asynchronous function calls, 'f', that share the same
 *     'key' are not executed in parallel.
 *  2. In the case where an attempt to call in parallel is prevented,
 *     the 'action' callbacks are executed when the asynchronous call is
 *     completed.
 *
 * Example:
 *  When we async-map to remotely fetch module definition, it is
 *  important that only a single define is evaluated, but the
 *  notification that the definition has completed is propagated
 *  to all interested parties.
 *
 *    1. we require dependencies 'x' and 'y'
 *
 *    2. both x and y are defined in the same file  (i.e. compiled together), 'a.js'.
 *
 *    3. we resolve x and y, to their load spec using a modulator
 *        x_spec = {load: function () { -- load a.js -- }, url: a.js, serial: false};
 *        y_spec = {load: function () { -- load a.js -- }, url: a.js, serial: false};
 *
 *    4. we make the piggyback call for x:
 *        piggybacker.piggyback(x_spec.url, x_spec.load, xdone);
 *
 *       this will register the 'xdone' action, and actually
 *       trigger the load call, with a synthetic callback
 *       responsible for triggering all registered actions.
 *
 *    5. we make the piggyback call for y:
 *        piggybacker.piggyback(y_spec.url, y_spec.load, ydone);
 *
 *       this will register the 'ydone' action, but NOT trigger
 *       the load call.
 *
 *    6. the load call completes, and calls the synthetic callback,
 *       which is responsible for triggering both 'xdone' and 'ydone'.
 *
 *    7. something else happens that means we have to load 'a.js' again,
 *       the piggybacker DOES NOT prevent this call, and will follow
 *       the above process.
 */
kernel.async.piggybacker = def(
  [
    kernel.fp.array,
    kernel.fp.functions
  ],

  function (ar, fn) {
    var create = function () {
      var queue = {};  // key -> [actions]

      var process = function (key) {
        var actions = queue[key];
        delete queue[key];
        ar.each(actions, fn.apply);
      };

      var piggyback = function (key, f, action) {
        if (queue[key] === undefined) {
          queue[key] = [ action ];
          f(fn.curry(process, key));
        } else {
          queue[key].push(action);
        }
      };

      return {
        piggyback: piggyback
      };
    };

    return {
      create: create
    };
  }
);
kernel.modulator.globalator = def(
  [
  ],

  function () {
    var create = function () {
      // FIX pull out
      var resolve = function (name, scope) {
        var parts = name.split('.');
        var r = scope;
        for (var i = 0; i < parts.length && r !== undefined; ++i)
          r = r[parts[i]];
        return r;
      };

      var global = Function('return this')();

      var can = function (id) {
        return id.indexOf('global!') === 0;
      };

      var get = function (id, define, require) {
        var name = id.substring('global!'.length);

        var load = function (onsuccess, onfailure) {
          var instance = resolve(name, global);
          if (instance !== undefined) {
            define(id, [], function () { return instance; });
            onsuccess();
          } else {
            onfailure('Modulator error: could not resolve global [' + name + ']');
          }
        };

        return {
          url: id, // this just needs to be unique, no download required.
          load: load,
          serial: true
        };
      };

      return {
        can: can,
        get: get
      }
    };
    return {
      create: create
    };
  }
);
kernel.modulator.bolt = def(
  [
    kernel.fp.functions
  ],

  function (fn) {
    var create = function (loader, pather, namespace, path, idTransformer, options) {
      var can = function (id) {
        return id === namespace || id.indexOf(namespace + '.') === 0 || id.indexOf(namespace + '/') === 0;
      };

      var get = function (id) {
        var before = options !== undefined && options.absolute === true ? path : pather(path);
        var after = options !== undefined && options.fresh === true ? '?cachebuster=' + new Date().getTime() : '';
        var url = before + "/" + idTransformer(id) + '.js' + after;
        var load = fn.curry(loader.load, url);

        return {
          url: url,
          load: load,
          serial: false
        };
      };

      return {
        can: can,
        get: get
      };
    };

    return {
      create: create
    };
  }
);kernel.module.stratifier = def(
  [
    kernel.fp.array
  ],

  function (ar) {
    var stratify = function (specs) {
      var parallels = ar.filter(specs, function (spec) {
        return !spec.serial;
      });
      return parallels.length > 0 ? parallels : specs.slice(0, 1);
    };

    return {
      stratify: stratify
    };
  }
);
/**
 * This module performs dependency analysis of strings that depend on sets of
 * strings.
 *
 * The input is an array of root strings to start analysis from, and an object
 * that contains a mapping of each string to the strings it depends on.
 *
 * Performing an analysis results in either:
 *   1. an empty array, indicating that all dependencies are satisfied,
 *   2. an array of strings that are, at the minimum, still needed in order to
 *      satisfy the given dependency trees, or
 *   3. an array of strings that form a dependency cycle.
 */
kernel.module.analyser = def(
  [
    kernel.fp.array
  ],

  function (array) {
    var collect = function (path, name) {
      var i = array.indexof(path, name);
      var p = path.slice(i);
      return p.concat([name]);
    };

    /**
     * @param {array} roots Contains a list of root ids
     * @param {object} modules Contains dependency information in format: { id: [ 'id1', 'id2' ] }
     */
    var analyse = function (roots, modules) {
      var done = {};
      var path = [];
      var missing = [];
      var cycle;

      var children = function (name) {
        array.each(modules[name], attempt);
      };

      var examine = function (name) {
        if (modules[name])
          children(name);
        else
          missing.push(name);
      };

      var descend = function (name) {
        path.push(name);
        examine(name);
        path.pop();
      };

      var decycle = function (name) {
        if (array.contains(path, name))
          cycle = collect(path, name);
        else
          descend(name);
      };

      var attempt = function (name) {
        if (!done[name]) {
          decycle(name);
          done[name] = true;
        }
      };

      array.each(roots, attempt);

      return cycle ? { cycle: cycle } : { load: missing };
    };

    return {
      analyse: analyse
    };
  }
);
kernel.module.fetcher = def(
  [
    kernel.fp.array,
    kernel.fp.functions,
    kernel.async.map,
    kernel.async.piggybacker,
    kernel.module.stratifier
  ],

  function (ar, fn, map, piggybacker, stratifier) {
    var create = function (regulator, validator, onerror, define, require, demand) {
      var piggyback = piggybacker.create();

      var validate = function (onsuccess, results) {
        var failed = ar.filter(results, fn.not(validator));
        if (failed.length > 0)
          onerror('Fetcher error: modules were not defined: ' + failed.join(', '));
        else
          onsuccess();
      };

      var mapper = function (spec, onresult) {
        var action = fn.curry(onresult, spec.id);
        var load = function (callback) {
          spec.load(callback, onerror);
        };
        piggyback.piggyback(spec.url, load, action);
      };

      var asyncfetch = function (specs, onsuccess) {
        var oncomplete = fn.curry(validate, onsuccess);
        var strata = stratifier.stratify(specs);
        map.amap(strata, mapper, oncomplete);
      };

      var fetch = function (ids, onsuccess) {
        regulator.regulate(ids, define, require, demand, function (specs) {
          asyncfetch(specs, onsuccess);
        }, onerror);
      };

      return {
        fetch: fetch
      };
    };

    return {
      create: create
    };
  }
);
kernel.module.loader = def(
  [
    kernel.module.analyser
  ],

  function (analyser) {
    var load = function (roots, deps, fetcher, oncontinue, onsuccess, onerror) {
      var result = analyser.analyse(roots, deps);

      if (result.cycle)
        onerror('Dependency error: a circular module dependency exists from ' + result.cycle.join(' ~> '));
      else if (result.load.length === 0)
        onsuccess();
      else
        fetcher.fetch(result.load, oncontinue);
    };

    return {
      load: load
    };
  }
);
kernel.module.manager = def(
  [
    kernel.fp.array,
    kernel.fp.object,
    kernel.module.loader,
    kernel.module.fetcher
  ],

  function (ar, obj, loader, fetcher) {
    var create = function (regulator, onerror) {
      var blueprints = {};  // id -> { id: string, dependencies: [ string ], definition: function }
      var modules = {};     // id -> module

      // Adds a module to the system.
      var define = function (id, dependencies, definition) {
        if (id === undefined)
          onerror("Define error: module id can not be undefined");
        else if (blueprints[id] !== undefined)
          onerror("Define error: module '" + id + "' is already defined");
        else
          blueprints[id] = { id: id, dependencies: dependencies, definition: definition };
      };

      // Loads a set of modules asynchronously.
      var require = function (ids, callback) {
        var onsuccess = function () {
          var instances = ar.map(ids, demand);
          callback.apply(null, instances);
        };

        var oncontinue = function () {
          var deps = obj.map(blueprints, function (k, v) {
            return v.dependencies;
          });
          loader.load(ids, deps, fetch, oncontinue, onsuccess, onerror);
        };

        oncontinue();
      };

      // Instantiates a module and all of its dependencies.
      var demand = function (id) {
        if (modules[id] !== undefined)
          return modules[id];
        if (blueprints[id] === undefined)
          throw "module '" + id + "' is not defined";
        var result = instantiate(id);
        if (result === undefined)
          throw "module '" + id + "' returned undefined from definition function";
        modules[id] = result;
        return result;
      };

      var instantiate = function (id) {
        var blueprint = blueprints[id];
        var args = ar.map(blueprint.dependencies, demand);  // Instantiate dependencies
        return blueprint.definition.apply(null, args);  // Instantiate self
      };

      var validator = function (id) { return blueprints[id] !== undefined; };
      var fetch = fetcher.create(regulator, validator, onerror, define, require, demand);

      r