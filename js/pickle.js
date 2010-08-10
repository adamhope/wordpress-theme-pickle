var Pickle = (function(user_opts){
  
  var cfg,
  DEFAULTS = {
    context: $('#topcontent'),
    mainImage: $('#mainimage'),
    nextLink: $('#overNextLink'),     // nextPostLink
    previousLink: $('#overPrevLink') // prevPostLink
  },
  
  preloadedImg,
  
  setupNavigation = function () {
    $([cfg.nextLink, cfg.previousLink]).each(function(i, el) {
      el.click(function(e){
        getNewContent(el);
        e.preventDefault();        
      });
    });
    $('#exif').click(function(e){
      $('#panel_exif').toggle();
      e.preventDefault();
    });
  },
  
  getNewContent = function (el) {
		var url = cfg.templateDir+'/ajax_blog.php',
  		postID = cfg.prevPostID,
		  params = '?id=' + postID;
      $.getJSON(url + params, update);
  },
  
  loadComplete = function() {
    var mainImage = '#mainimage';
    $('#topcontent').css({width: preloadedImg.width});
		$(mainImage)[0].width  = preloadedImg.width;
		$(mainImage)[0].height = preloadedImg.height;
    $(mainImage).attr('src', preloadedImg.src);
 	 };
  
  update = function (data) {
    console.debug(data);
    // fade out image
    // resize image area
    // fade in new image
    preloadedImg = new Image();
    preloadedImg.onload = loadComplete;
    preloadedImg.src = data.image_uri;
    // $('#mainimage').css({'visibility':'hidden'});
		$('#imageholder').css('background-image', cfg.templateDir + '/images/loading.gif');
    // update exif
    $('#panel_exif').html(data.exif);
  };
  
  return {
    init: function () {

      // sort out config
      cfg = $.extend({}, DEFAULTS, user_opts);
      setupNavigation();
      

    }
  }
  
}(opts));