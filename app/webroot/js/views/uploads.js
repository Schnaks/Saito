define([
    'jquery',
    'underscore',
    'backbone',
    'models/appSetting',
    'collections/uploads', 'views/upload',
    'views/uploadNew',
    'text!templates/uploads.html'
], function($, _, Backbone,
            AppSetting,
    UploadsCollection, UploadView,
    UploadNewView,
    uploadsTpl
    ) {

    var UploadsView = Backbone.View.extend({

        events: {
            "click .current .btn-submit": "_closeDialog"
        },

        initialize: function(options) {
            this.textarea = options.textarea;
            this.eventBus = options.eventBus;

            this.collection = new UploadsCollection({
                url: AppSetting.get('webroot')
            });

            this.listenTo(this.collection, "reset", this._addAll);

            this.$('.body').html(_.template(uploadsTpl));

            this.uploadNewView = new UploadNewView({
                collection: this.collection,
                eventBus: this.eventBus
            })
            this.$('.content').append(this.uploadNewView.render().el);

            this.render();
            this.collection.fetch();
        },

        _addOne: function(upload) {
            var uploadView = new UploadView({
                model: upload,
                textarea: this.textarea,
                eventBus: eventBus
            })
            this.$(".upload-new").after(uploadView.render().el);
        },

        _addAll: function() {
            this._removeAll();
            this.collection.each(this._addOne, this);
        },

        _removeAll: function() {
            this.$('.upload_box.current').remove();
        },

        _setDialogSize: function() {
            this.$el.dialog("option", "width", window.innerWidth - 80 );
            this.$el.dialog("option", "height", window.innerHeight - 80 );
        },

        _closeDialog: function() {
            this.$el.dialog("close");
        },

        render: function() {
            this.uploadNewView.render();
            this.$el.dialog({
                title: $.i18n.__("Upload"),
                modal: true,
                draggable: false,
                resizable: false,
                position: [40, 40],
                hide: 'fade'
            });

            this._setDialogSize();
            $(window).resize(_.bind(function() {
                this._setDialogSize();
            }, this));
            window.onorientationchange = _.bind(function() {
                this._setDialogSize();
            }, this);
            return this;
        }

    });

    return UploadsView;

});
