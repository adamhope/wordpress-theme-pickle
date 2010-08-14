/*jslint white: true, browser: true, onevar: true, undef: true, nomen: true, eqeqeq: true, plusplus: true, bitwise: true, regexp: true, newcap: true, immed: true, strict: true */

/*global $: true, jQuery: true, opts: true */

"use strict";

// http://plugins.jquery.com/project/togglefade
(function ($) {
    $.fn.toggleFade = function (settings) {
        settings = jQuery.extend({
            speedIn: "normal",
            speedOut: "normal"
        }, settings);
        return this.each(function () {
            var isHidden = jQuery(this).is(":hidden");
            jQuery(this)[isHidden ? "fadeIn" : "fadeOut"](isHidden ? settings.speedIn : settings.speedOut);
        });
    };
}(jQuery));

var Pickle = (function (user_opts) {

    var cfg, preloadedImg, nextPostID, prevPostID, ajaxSource, DEFAULTS = {
        ctx: '#topContent',
        mainImage: '#mainImage'
    },

    loadComplete = function () {
        $(cfg.ctx).css({
            width: preloadedImg.width
        });
        $('#mainImage, #imageHolder', cfg.ctx).css({
            'width': preloadedImg.width,
            'height': preloadedImg.height
        });
        $('#mainImage', cfg.ctx).attr({'src': preloadedImg.src});
        $(cfg.mainImage).fadeIn();
    },

    refresh = function (data) {
        preloadedImg = new Image();
        preloadedImg.onload = loadComplete;
        preloadedImg.src = data.image_uri;

        nextPostID = data.next_post;
        prevPostID = data.prev_post;

        $('#nextPostLink', cfg.ctx).html(data.next_post === 0 ? '' : '&raquo;');
        $('#prevPostLink', cfg.ctx).html(data.prev_post === 0 ? '' : '&laquo;');

        $('#overNextLink', cfg.ctx).css({
            'display': data.next_post === 0 ? 'none' : 'block'
        });
        $('#overPrevLink', cfg.ctx).css({
            'display': data.prev_post === 0 ? 'none' : 'block'
        });

        $('.previous').attr('href', data.next_post_perm);
        $('.next').attr('href', data.prev_post_perm);

        $('#panelExif', cfg.ctx).html(data.exif);
        $('#comment', cfg.ctx).html(data.comment_count + " comment" + (data.comment_count !== 1 ? "s" : ""));
        $('#comment', cfg.ctx).attr({
            'href': data.permalink + '#comments'
        });
        $('#textTitle', cfg.ctx).html('<a href="' + data.permalink + '">' + data.post_title + '</a><span id="inlinedate">' + data.post_date + '</span>');
        $('#panelInfo', cfg.ctx).html(data.post_content);

    },

    getNewContent = function (el) {
        var postID, params, direction = $(el).attr('class');
        if (direction === 'previous') {
            postID = prevPostID;
        } else if (direction === 'next') {
            postID = nextPostID;
        } else {
            return false;
        }
        params = '?id=' + postID;
        $('#mainImage', cfg.ctx).fadeOut(function () {
            $.getJSON(ajaxSource + params, refresh);
        });
    },

    setupNavigation = function () {
        $('.previous, .next').each(function (i, el) {
            $(el).click(function (e) {
                getNewContent(el);
                e.preventDefault();
            });
        });
        $('#exif').click(function (e) {
            $('#panelExif', cfg.ctx).toggleFade();
            e.preventDefault();
        });
        $('#info').click(function (e) {
            $('#panelInfo', cfg.ctx).toggleFade();
            e.preventDefault();
        });
    };

    return {
        init: function () {
            cfg = $.extend({}, DEFAULTS, user_opts);
            nextPostID = cfg.nextPostID;
            prevPostID = cfg.prevPostID;
            ajaxSource = cfg.templateDir + '/ajax_blog.php';
            $('#imageHolder', cfg.ctx).css({
                'width': $('#mainImage', cfg.ctx).css('width'),
                'height': $('#mainImage', cfg.ctx).css('height')
            });
            setupNavigation();
        }
    };

}(opts));

// photo category browser

var Browse = (function (user_opts) {

    var imgCache = [],
        preloaded = 1,
        photoData,

    setupTooltips = function () {
        $('.mosaic').each(function (i, el) {
            $(el).data({
                'tip:title': photoData[i].post_title,
                'tip:text': photoData[i].post_date + '<br />' + photoData[i].comment_count + ' ' + (photoData[i].comment_count === 1 ? 'comment' : 'comments')
            });
        }).tooltip({
            track: true,
            delay: 0,
            showURL: false,
            fade: 250,
            bodyHandler: function () { 
                return $(this).data('tip:title') + '<br />' + $(this).data('tip:text');
            }
        });
    },

    preloadFinish = function () {

        var tagFlag, link, i;

        if (preloaded !== imgCache.length) {
            preloaded = preloaded + 1;
            return false;
        }

        // Remove all images from container
        $('#tagContainer').empty();

        tagFlag = typeof photoData[0].comment_count !== 'undefined';

        for (i = 0; i < imgCache.length; i = i + 1) {

            link    = $('<a href="' + photoData[i].permalink + '" />');

            $(imgCache[i]).addClass('mosaic');

            $(link).append(imgCache[i]);

            $('#tagContainer').append(link);

        }

        if (tagFlag) {
            setupTooltips();
        }

        $('#tagContainer').css({
            'height': 'auto',
            'overflow': 'auto'
        });

    },

    tagRefresh = function (data) {
        var i,
            photoData = data;
        for (i = 0; i < photoData.length; i = i + 1) {
            imgCache[i] = new Image();
            imgCache[i].onload = preloadFinish;
            imgCache[i].src = photoData[i].image_uri;
        }
    },
    
    tagClick = function (el, type) {
        var params,
            ident,
            url = user_opts.templateDir + '/ajax_browse.php';
        switch (type) {
        case 'tag':
            ident = /tag-link-(\d+)/.exec($(el).attr('class'))[1];
            break;
        case 'cat':
            ident = /cat-item-(\d+)/.exec($(el).parent().attr('class'))[1];
            break;
        default:
            ident = $(el).html();
        }

        // Set up heights for a smooth transition.
        // TODO don't think scrollHeight is working
        $('#tagPics').css('height', $('#tagPics').scrollHeight);
        $('#tagContainer').css({
            'overflow': 'hidden',
            'height': $('tagContainer').scrollHeight
        });
        $('a.current').removeClass('current');
        $(el).addClass('current');

        // Send off a JSON Request to the server for the list of images associated with this tag/year.
        params = '?' + type + '=' + ident;
        $.getJSON(url + params, tagRefresh);
    };

    return {
        init: function () {
            
            photoData = user_opts.posts;

            $('#tagCloud a').each(function (i, el) {
                $(el).click(function (e) {
                    tagClick(el, 'tag');
                    e.preventDefault();
                });
            });
            
            $('#catCloud a').each(function (i, el) {
                $(el).click(function (e) {
                    tagClick(el, 'cat');
                    e.preventDefault();
                });
            });
            
            $('#yearCloud a').each(function (i, el) {
                $(el).click(function (e) {
                    tagClick(el, 'year');
                    e.preventDefault();
                });
            });

            if (photoData && typeof photoData[0].comment_count !== 'undefined') {
                setupTooltips();
            }

            // Make tagProgress visible but with zero opacity (allows us to calculate height).
            $('#tagProgress').css({
                'opacity': 0,
                'visibility': 'visible'
            });

        }
    };
    
}(browseOpts));
