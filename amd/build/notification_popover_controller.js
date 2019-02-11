define(["jquery","core/ajax","core/templates","core/str","core/url","core/notification","core/custom_interaction_events","core/popover_region_controller","message_popup/notification_repository","message_popup/notification_area_events"],function(a,b,c,d,e,f,g,h,i,j){var k={MARK_ALL_READ_BUTTON:"[data-action=\"mark-all-read\"]",ALL_NOTIFICATIONS_CONTAINER:"[data-region=\"all-notifications\"]",NOTIFICATION:"[data-region=\"notification-content-item-container\"]",UNREAD_NOTIFICATION:"[data-region=\"notification-content-item-container\"].unread",NOTIFICATION_LINK:"[data-action=\"content-item-link\"]",EMPTY_MESSAGE:"[data-region=\"empty-message\"]",COUNT_CONTAINER:"[data-region=\"count-container\"]"},l=function(a){h.call(this,a),this.markAllReadButton=this.root.find(k.MARK_ALL_READ_BUTTON),this.unreadCount=0,this.userId=this.root.attr("data-userid"),this.container=this.root.find(k.ALL_NOTIFICATIONS_CONTAINER),this.limit=20,this.offset=0,this.loadedAll=!1,this.initialLoad=!1,this.unreadCount=this.root.find(k.COUNT_CONTAINER).html()};return l.prototype=Object.create(h.prototype),l.prototype.constructor=l,l.prototype.updateButtonAriaLabel=function(){this.isMenuOpen()?d.get_string("hidenotificationwindow","message").done(function(a){this.menuToggle.attr("aria-label",a)}.bind(this)):this.unreadCount?d.get_string("shownotificationwindowwithcount","message",this.unreadCount).done(function(a){this.menuToggle.attr("aria-label",a)}.bind(this)):d.get_string("shownotificationwindownonew","message").done(function(a){this.menuToggle.attr("aria-label",a)}.bind(this))},l.prototype.getContent=function(){return this.container},l.prototype.getOffset=function(){return this.offset},l.prototype.incrementOffset=function(){this.offset+=this.limit},l.prototype.hasDoneInitialLoad=function(){return this.initialLoad},l.prototype.hasLoadedAllContent=function(){return this.loadedAll},l.prototype.setLoadedAllContent=function(a){this.loadedAll=a},l.prototype.renderUnreadCount=function(){var a=this.root.find(k.COUNT_CONTAINER);this.unreadCount?(a.text(this.unreadCount),a.removeClass("hidden")):a.addClass("hidden")},l.prototype.hideUnreadCount=function(){this.root.find(k.COUNT_CONTAINER).addClass("hidden")},l.prototype.getNotificationElement=function(a){var b=this.root.find(k.NOTIFICATION+"[data-id=\""+a+"\"]");return 1==b.length?b:null},l.prototype.renderNotifications=function(b,d){var f=[];return a.each(b,function(a,b){var d=this.getOffset()-this.limit;b.viewmoreurl=e.relativeUrl("/message/output/popup/notifications.php",{notificationid:b.id,offset:d});var g={notificationid:b.id};b.contexturl&&(g.redirecturl=encodeURIComponent(b.contexturl)),b.contexturl=e.relativeUrl("local/notifications/mark_notification_read.php",g);var h=c.render("message_popup/notification_content_item",b).then(function(a,b){return{html:a,js:b}});f.push(h)}.bind(this)),a.when.apply(a,f).then(function(){a.each(arguments,function(a,b){d.append(b.html),c.runTemplateJS(b.js)})})},l.prototype.loadMoreNotifications=function(){if(this.isLoading||this.hasLoadedAllContent())return a.Deferred().resolve();this.startLoading();var b={limit:this.limit,offset:this.getOffset(),useridto:this.userId},c=this.getContent();return i.query(b).then(function(a){var b=a.notifications;return this.unreadCount=a.unreadcount,this.setLoadedAllContent(!b.length||b.length<this.limit),this.initialLoad=!0,this.updateButtonAriaLabel(),!!b.length&&(this.incrementOffset(),this.renderNotifications(b,c))}.bind(this)).always(function(){this.stopLoading()}.bind(this))},l.prototype.markAllAsRead=function(){return this.markAllReadButton.addClass("loading"),i.markAllAsRead({useridto:this.userId}).then(function(){this.unreadCount=0,this.root.find(k.UNREAD_NOTIFICATION).removeClass("unread")}.bind(this)).always(function(){this.markAllReadButton.removeClass("loading")}.bind(this))},l.prototype.registerEventListeners=function(){g.define(this.root,[g.events.activate]),this.root.on(g.events.activate,k.MARK_ALL_READ_BUTTON,function(a,b){this.markAllAsRead(),a.stopPropagation(),b.originalEvent.preventDefault()}.bind(this)),this.root.on(g.events.activate,k.NOTIFICATION_LINK,function(b){var c=a(b.target).closest(k.NOTIFICATION);c.hasClass("unread")&&(this.unreadCount--,c.removeClass("unread")),b.stopPropagation()}.bind(this)),this.root.on(this.events().menuOpened,function(){this.hideUnreadCount(),this.updateButtonAriaLabel(),this.hasDoneInitialLoad()||this.loadMoreNotifications()}.bind(this)),this.root.on(this.events().menuClosed,function(){this.renderUnreadCount(),this.updateButtonAriaLabel()}.bind(this)),this.root.on(this.events().startLoading,function(){this.getContent().attr("aria-busy","true")}.bind(this)),this.root.on(this.events().stopLoading,function(){this.getContent().attr("aria-busy","false")}.bind(this)),this.getContentContainer().on(g.events.scrollBottom,function(){this.isLoading||this.hasLoadedAllContent()||this.loadMoreNotifications()}.bind(this)),g.define(this.getContentContainer(),[g.events.scrollLock]),a(document).on(j.notificationShown,function(a,b){if(!b.read){var c=this.getNotificationElement(b.id);c&&c.removeClass("unread"),this.unreadCount--,this.renderUnreadCount()}}.bind(this))},l});