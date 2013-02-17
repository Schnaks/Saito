define([
    'jquery',
    'underscore',
    'backbone',
    'models/geshi'
], function($, _, Backbone, GeshiModel) {

    var GeshiView = Backbone.View.extend({

        plainText: false,
        htmlText: false,

        events: {
            "click .geshi-plain-text": "_togglePlaintext"
        },

        initialize: function() {
            this.model = new GeshiModel;
            this.collection.push(this.model);
            this.block = this.$('.geshi-plain-text').next();

            this._setPlaintextButton();

            this.listenTo(this.model, 'change', this.render)
        },

        _setPlaintextButton: function() {
            if (this.model.get('isPlaintext')) {
                this.$('.geshi-plain-text').html("<i class='icon-list-ol'></i>");
            } else {
                this.$('.geshi-plain-text').html("<i class='icon-reorder'></i>");
            }
        },

        _togglePlaintext: function(event) {
            event.preventDefault();
            this.model.set('isPlaintext', !this.model.get('isPlaintext'));
        },

        _extractPlaintext: function() {
            if (this.plainText !== false) {
                return;
            }
            this.htmlText = this.block.html();
            if (navigator.appName == 'Microsoft Internet Explorer') {
                this.htmlText = this.htmlText.replace(/\n\r/g, "+");
                this.plainText = jQuery(this.htmlText).text().replace(/\+\+/g, "\r");
            } else {
                this.plainText = this.block.text().replace(/code /g, "code \n");
            }
        },

        _renderText: function() {
            if (this.model.get('isPlaintext')) {
                this.block.text(this.plainText).wrapInner("<pre class=\"code\"></pre>");
            } else {
                this.block.html(this.htmlText);
            }
        },

        render: function() {
            this._setPlaintextButton()
            this._extractPlaintext();
            this._renderText();
            return this;
        }


    });

    return GeshiView;

});