define([
  'jquery',
  'underscore',
  'backbone',
  'models/app',
  'collections/threadlines', 'views/threadlines',
  'collections/threads', 'views/thread',
  'collections/postings', 'models/posting', 'views/postingLayout',
  'collections/bookmarks', 'views/bookmarks',
  'views/helps', 'views/categoryChooser',
  'collections/slidetabs', 'views/slidetabs',
  'views/answering',
  'jqueryUi'
], function($, _, Backbone, App, ThreadLineCollection, ThreadLineView, ThreadCollection, ThreadView, PostingCollection, PostingModel, PostingLayout, BookmarksCollection, BookmarksView, HelpsView, CategoryChooserView, SlidetabsCollection, SlidetabsView, AnsweringView) {
  'use strict';

  var AppView = Backbone.View.extend({

    el: $('body'),

    autoPageReloadTimer: false,

    _domInitializers: {
      '.entry.add-not-inline': '_initAnsweringNotInlined',
      '#bookmarks': '_initBookmarks',
      '#category-chooser': '_initCategoryChooser',
      '.js-entry-view-core': '_initPostings',
      '#slidetabs': '_initSlidetabs',
      '.threadBox': '_initThreadBoxes',
      '.threadLeaf': '_initThreadLeafs',
      '.users.logout': '_initLogout'
    },

    events: {
      'click #showLoginForm': 'showLoginForm',
      'focus #header-searchField': 'widenSearchField',
      'click #btn-scrollToTop': 'scrollToTop',
      'click #btn-manuallyMarkAsRead': 'manuallyMarkAsRead',
      'click #btn-category-chooser': 'toggleCategoryChooser',
      'click #btn_header_logo': '_onEntriesIndexReload'
    },

    initialize: function() {
      this.threads = new ThreadCollection();
      if (App.request.controller === 'entries' && App.request.action === 'index') {
        this.threads.fetch();
      }
      this.postings = new PostingCollection();
      // collection of threadlines not bound to thread (bookmarks, search results …)
      this.threadLines = new ThreadLineCollection();

      this.listenTo(App.eventBus, 'initAutoreload', this.initAutoreload);
      this.listenTo(App.eventBus, 'breakAutoreload', this.breakAutoreload);
      this.$el.on('dialogopen', this.fixJqueryUiDialog);
    },

    initFromDom: function(options) {
      _.each(this._domInitializers, function(initializer, element) {
        var $elements = $(element);
        if ($elements.length > 0) {
          this[initializer]($elements);
        }
      }, this);

      this.initAutoreload();
      this.initHelp('.shp');

      /*** All elements initialized, show page ***/

      App.status.start();
      this._showPage(options.SaitoApp.timeAppStart, options.contentTimer);
      App.eventBus.trigger('notification', options.SaitoApp);

      // scroll to thread
      if (window.location.href.indexOf('/jump:') > -1) {
        var results = /jump:(\d+)/.exec(window.location.href);
        this.scrollToThread(results[1]);
        window.history.replaceState(
            'object or string',
            'Title',
            window.location.pathname.replace(/jump:\d+(\/)?/, '')
        );
      }
    },

    /**
     * init the entries/add form where answering is not appended to a posting
     *
     * @param element
     * @private
     */
    _initAnsweringNotInlined: function(element) {
      this.answeringForm = new AnsweringView({
        el: element,
        model: new PostingModel({id: 'foo'}),
        ajax: false
      });
    },

    _initBookmarks: function(element_n) {
      var bookmarksView;
      var bookmarks = new BookmarksCollection();
      bookmarksView = new BookmarksView({
        el: element_n,
        collection: bookmarks
      });
    },

    _initCategoryChooser: function(element) {
      this.categoryChooser = new CategoryChooserView({ el: element });
    },

    _initLogout: function() {
      App.commands.execute('app:localStorage:clear');
    },

    _initPostings: function(elements) {
      _.each(elements, function(element) {
        var id,
          postingLayout,
          postingModel;

        id = parseInt(element.getAttribute('data-id'), 10);
        postingModel = new PostingModel({id: id});
        this.postings.add(postingModel, {silent: true});
        postingLayout = new PostingLayout({
          el: $(element),
          model: this.postings.get(id),
          collection: this.postings
        });
      }, this);
    },

    _initSlidetabs: function(element) {
      var slidetabs,
        slidetabsView;
      slidetabs = new SlidetabsCollection();
      slidetabsView = new SlidetabsView({
        el: element,
        collection: slidetabs
      });
    },

    _initThreadBoxes: function(elements) {
      _.each(elements, function(element) {
        var threadView, threadId;

        threadId = parseInt($(element).attr('data-id'), 10);
        if (!this.threads.get(threadId)) {
          this.threads.add([
            {
              id: threadId,
              isThreadCollapsed: App.request.controller === 'entries' && App.request.action === 'index' && App.currentUser.get('user_show_thread_collapsed')
            }
          ], {silent: true});
        }
        threadView = new ThreadView({
          el: $(element),
          postings: this.postings,
          model: this.threads.get(threadId)
        });
      }, this);

    },

    _initThreadLeafs: function(elements) {
      _.each(elements, function(element) {
        var leafData = JSON.parse(element.getAttribute('data-leaf'));
        // 'new' is 'isNewToUser' in leaf model; also 'new' is JS-keyword
        leafData.isNewToUser = leafData['new'];
        delete(leafData['new']);

        var threadsCollection = this.threads.get(leafData.tid);
        var threadlineCollection;
        if (threadsCollection) {
          // leafData belongs to complete thread on page
          threadlineCollection = threadsCollection.threadlines;
        } else {
          // leafData is not shown in its complete thread context (e.g. bookmark)
          threadlineCollection = this.threadLines;
        }

        var threadLineView = new ThreadLineView({
          el: $(element),
          leafData: leafData,
          postings: this.postings,
          collection: threadlineCollection
        });
      }, this);
    },

    _showPage: function(startTime, timer) {
      var triggerVisible = function() {
        App.eventBus.trigger('isAppVisible', true);
      };

      if (App.request.isMobile || (new Date().getTime() - startTime) > 1500) {
        $('#content').css('visibility', 'visible');
        triggerVisible();
      } else {
        $('#content')
            .css({visibility: 'visible', opacity: 0})
            .animate(
            { opacity: 1 },
            {
              duration: 150,
              easing: 'easeInOutQuart',
              complete: triggerVisible
            });
      }
      timer.cancel();
    },

    fixJqueryUiDialog: function(event, ui) {
      $('.ui-icon-closethick')
          .attr('class', 'jqueryUi-closethick-fix')
          .html('');
    },

    toggleCategoryChooser: function(event) {
      event.preventDefault();
      this.categoryChooser.toggle();
    },

    initHelp: function(element_n) {
      var helps = new HelpsView({
        el: 'body',
        elementName: element_n,
        indicatorName: '#shp-show',
        webroot: App.settings.get('webroot')
      });
    },

    scrollToThread: function(tid) {
      $('.threadBox[data-id=' + tid + ']')[0].scrollIntoView('top');
    },

    initAutoreload: function() {
      this.breakAutoreload();
      if (App.settings.get('autoPageReload')) {
        this.autoPageReloadTimer = setTimeout(
            _.bind(function() {
              window.location = App.settings.get('webroot') + 'entries/';
            }, this), App.settings.get('autoPageReload') * 1000);
      }
    },

    breakAutoreload: function() {
      if (this.autoPageReloadTimer !== false) {
        clearTimeout(this.autoPageReloadTimer);
        this.autoPageReloadTimer = false;
      }
    },

    /**
     * Widen search field
     */
    widenSearchField: function(event) {
      var width = 350;
      event.preventDefault();
      if ($(event.currentTarget).width() < width) {
        $(event.currentTarget).animate({
              width: width + 'px'
            },
            "fast"
        );
      }
    },

    showLoginForm: function(event) {
      var modalLoginDialog;

      if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
        return;
      }

      modalLoginDialog = $('#modalLoginDialog');

      event.preventDefault();
      modalLoginDialog.height('auto');
      var title = event.currentTarget.title;
      modalLoginDialog.dialog({
        modal: true,
        title: title,
        width: 420,
        show: 'fade',
        hide: 'fade',
        position: ['center', 120],
        resizable: false
      });
    },

    scrollToTop: function(event) {
      event.preventDefault();
      window.scrollTo(0, 0);
    },

    manuallyMarkAsRead: function(event) {
      if (event) {
        event.preventDefault();
      }
      this._onEntriesIndexReload();
      window.redirect(App.settings.get('webroot') + 'entries/update');
    },

    _onEntriesIndexReload: function() {
      var _controller = App.request.controller,
          _action = App.request.action;
      if (_controller !== 'entries' || _action !== 'index') {
        return;
      }
      App.commands.execute('shoutbox:mar', {silent: true});
    }
  });

  return AppView;

});
