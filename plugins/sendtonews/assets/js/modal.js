
var S2N_Modal={};(function($,S2N_Modal){var $=jQuery;_.extend(S2N_Modal,{view:{},controller:{}});S2N_Modal.view.Overview=wp.media.View.extend({className:'sendtonews-overview',template:wp.media.template('sendtonews-overview'),});S2N_Modal.controller.Overview=wp.media.controller.State.extend({defaults:{id:'sendtonews-overview',menu:'default',content:'sendtonews_overview'}});S2N_Modal.view.SmartMatch=wp.media.View.extend({className:'sendtonews-smartmatch',template:wp.media.template('sendtonews-smartmatch'),});S2N_Modal.controller.SmartMatch=wp.media.controller.State.extend({defaults:{id:'sendtonews-smartmatch',menu:'default',content:'sendtonews_smartmatch'}});S2N_Modal.view.Help=wp.media.View.extend({className:'sendtonews-help',template:wp.media.template('sendtonews-help'),});S2N_Modal.controller.Help=wp.media.controller.State.extend({defaults:{id:'sendtonews-help',menu:'default',content:'sendtonews_help'}});if(sendtonews_model_i18n.enable_library){S2N_Modal.view.VideoLibrary=wp.media.View.extend({className:'sendtonews-video-library',template:wp.media.template('sendtonews-video-library'),});S2N_Modal.controller.VideoLibrary=wp.media.controller.State.extend({defaults:{id:'sendtonews-video-library',menu:'default',content:'sendtonews_video_library'}});}
var MediaFrame=wp.media.view.MediaFrame;S2N_Modal.frame=MediaFrame.extend({className:'media-frame sendtonews-modal',regions:['menu','title','content'],initialize:function(){MediaFrame.prototype.initialize.apply(this,arguments);_.defaults(this.options,{selection:[],library:{},multiple:false,state:'sendtonews-overview',modal:true,uploader:false,});this.createStates();this.bindHandlers();},createStates:function(){var options=this.options;if(this.options.states){return;}
var states=[];var overviewState=new S2N_Modal.controller.Overview({title:'Overview',id:'sendtonews-overview',priority:20});states.push(overviewState);var playerState=new S2N_Modal.controller.SmartMatch({title:'Smart Match Player',id:'sendtonews-smartmatch',priority:30});states.push(playerState);if(sendtonews_model_i18n.enable_library){var libraryState=new S2N_Modal.controller.VideoLibrary({title:'Video Library',id:'sendtonews-video-library',priority:40});states.push(libraryState);}
var helpState=new S2N_Modal.controller.Help({title:'Help',id:'sendtonews-help',priority:50});states.push(helpState);this.states.add(states);},bindHandlers:function(){this.on('content:render:sendtonews_overview',this.cancelStoriesScrollChecker,this);this.on('content:render:sendtonews_smartmatch',this.cancelStoriesScrollChecker,this);this.on('content:render:sendtonews_help',this.cancelStoriesScrollChecker,this);this.on('content:render:sendtonews_overview',this.overviewContent,this);this.on('content:render:sendtonews_smartmatch',this.smartMatchContent,this);if(sendtonews_model_i18n.enable_library){this.on('content:render:sendtonews_video_library',this.libraryContent,this);}
this.on('content:render:sendtonews_help',this.helpContent,this);this.on('close',this.closeFrameContent,this);},overviewContent:function(){var view=new S2N_Modal.view.Overview({controller:this,model:this.state().props,});view.on('ready',this.initOverviewContent);this.content.set(view);},smartMatchContent:function(){var view=new S2N_Modal.view.SmartMatch({controller:this,model:this.state().props});view.on('ready',this.initSmartMatchContent);this.content.set(view);},libraryContent:function(){var view=new S2N_Modal.view.VideoLibrary({controller:this,model:this.state().props});view.on('ready',this.initLibraryContent);this.content.set(view);},helpContent:function(){var view=new S2N_Modal.view.Help({controller:this,model:this.state().props});view.on('ready',this.initHelpContent);this.content.set(view);},initOverviewContent:function(){this.$el.closest('.media-frame').addClass('hide-toolbar');this.$el[0].parentElement.style="background-color: #fff;";},initSmartMatchContent:function(){this.$el.closest('.media-frame').addClass('hide-toolbar');this.$el[0].parentElement.style="background-color: #0d3b62;";$('[data-toggle="tooltip"]').mobileTooltip();updatePlayersSelect();},initLibraryContent:function(){this.$el.closest('.media-frame').addClass('hide-toolbar');this.$el[0].parentElement.style="background-color: #fff;";$('[data-toggle="tooltip"]').mobileTooltip();const reason=sessionStorage.getItem("sendtonews-search-reason")||"load";const searchSettings=JSON.parse(sessionStorage.getItem("sendtonews-search-settings")||"{}");const searchString=searchSettings.search||'';const searchAge=searchSettings.age||1;const searchLang=searchSettings.lang||'EN';$('.sendtonews-video-library .sendtonews-header-search .sendtonews-stories-search-input').val(searchString);$('.sendtonews-video-library .sendtonews-header-filters .sendtonews-stories-age-dropdown').val(searchAge);$('.sendtonews-video-library .sendtonews-header-filters .sendtonews-stories-lang-dropdown').val(searchLang);onSearchChanged();updateCategoryFilters(searchLang);refreshStories(reason);if(storiesScrollChecker){clearInterval(storiesScrollChecker);}
storiesScrollChecker=setInterval(storiesScrollCheck,50);jQuery('.sendtonews-modal .media-frame-content').on('resize scroll',async function(){scrolled=true;});closed=false;},initHelpContent:function(){this.$el.closest('.media-frame').addClass('hide-toolbar');this.$el[0].parentElement.style="background-color: #fff;";},cancelStoriesScrollChecker:function(){if(storiesScrollChecker){clearInterval(storiesScrollChecker);}},closeFrameContent:function(){closed=true;if(storiesScrollChecker){clearInterval(storiesScrollChecker);}
this.remove();},});})(jQuery,S2N_Modal);