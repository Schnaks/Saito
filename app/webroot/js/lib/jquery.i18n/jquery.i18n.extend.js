/**
 * Extension for i18n for CakePHP/Saito
 */
define([
  'jquery',
  'lib/jquery.i18n/jquery.i18n'
], function($) {
  'use strict';

  $.extend($.i18n, {

    currentString: '',

    setDict: function(dict) {
      this.dict = dict;
    },

    setUrl: function(dictUrl) {
      this.dictUrl = dictUrl;
      this._loadDict();
    },

    _loadDict: function() {
      return $.ajax({
        url: this.dictUrl,
        dataType: 'json',
        async: false,
        cache: true,
        success: $.proxy(function(data) {
          this.dict = data;
        }, this)
      });
    },

    /**
     * Localice string with tokens
     *
     * Token replacement compatible to CakePHP's String::insert()
     *
     */
    __: function(string, tokens) {
      var out = string;
      if (typeof this.dict[string] === 'object' && this.dict[string][''] !== '') {
        out = this.dict[string][''];
      }
      if (typeof tokens === 'object') {
        out = this._insert(out, tokens);
      }
      return out;
    },

    _insert: function(string, tokens) {
      return string.replace(/:([-\w]+)/g, function(token, match) {
        if (typeof tokens[match] !== 'undefined') {
          return tokens[match];
        }
        return token;
      });
    }
  });

});
