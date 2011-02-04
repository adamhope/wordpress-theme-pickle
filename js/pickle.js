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

var Slideshow = (function () {

    var cfg, preloadedImg, nextPostID, prevPostID, ajaxSource, DEFAULTS = {
        ctx      : '#slideshow',
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

        $('#overNextLink', cfg.ctx).css({'display': data.next_post === 0 ? 'none' : 'block'});
        $('#overPrevLink', cfg.ctx).css({'display': data.prev_post === 0 ? 'none' : 'block'});

        $('.previous').attr('href', data.nextPostPerm);
        $('.next').attr('href', data.prevPostPerm);

        $('#panelExif', cfg.ctx).html(data.exif);
        $('#comment', cfg.ctx).html(data.comment_count + " comment" + (data.comment_count !== 1 ? "s" : ""));
        $('#comment', cfg.ctx).attr({'href': data.permalink + '#comments'});
        $('#textTitle', cfg.ctx).html('<a href="' + data.permalink + '">' + data.post_title + '</a><time id="inlinedate">' + data.post_date + '</time>');
        $('#panelInfo', cfg.ctx).html(data.post_content);

    },

    getNewContent = function (el) {
        var postID, params,
            direction = $(el).attr('class');
        switch (direction) {
        case 'next':
            postID = nextPostID;
            break;
        case 'previous':
            postID = prevPostID;
            break;
        default:
            return false;
        }
        params = '?id=' + postID;
        $('#mainImage', cfg.ctx).fadeOut(function () {
            $.getJSON(ajaxSource + params, refresh);
        });
    },

    // TODO this only needs to be done when the page first loads
    setImageHolderSize = function () {
        $('#imageHolder', cfg.ctx).css({
            'width':  $('#mainImage', cfg.ctx).css('width'),
            'height': $('#mainImage', cfg.ctx).css('height')
        });
    },

    setupNavigation = function () {
        $('.previous, .next').each(function (i, el) {
            $(el).click(function (e) {
                setImageHolderSize();
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
        init: function (opts) {
            if (!opts) {
                return false;
            }
            cfg        = $.extend({}, DEFAULTS, opts);
            nextPostID = cfg.nextPostID;
            prevPostID = cfg.prevPostID;
            ajaxSource = cfg.templateDir + '/ajax_blog.php';
            setupNavigation();
        }
    };

}());

var Browser = (function () {

    var cfg,
        photoData,
        imgCache = [],
        preloaded = 0,

    setupTooltips = function () {

        $('.mosaic').each(function (i, el) {
            $(el).data({
                'tip:title': photoData[i].post_title,
                'tip:text': photoData[i].post_date    +
                '<br />' + photoData[i].comment_count +
                ' '                                   +
                (photoData[i].comment_count === 1 ? 'comment' : 'comments')
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

    spinner = function (opacity) {
        $('#tagProgress').css({'opacity': opacity, 'visibility': 'visible'});
    },

    preloadFinish = function () {
        var tagFlag, link, i;

        if (preloaded !== imgCache.length - 1) {
            preloaded = preloaded + 1;
            return false;
        }

        // Remove all images from container
        $('#tag-pics').empty();

        spinner(0);

        tagFlag = typeof photoData[0].comment_count !== 'undefined';

        for (i = 0; i < imgCache.length; i = i + 1) {
            // TODO shouldn't be necessary if the image cache is cleared
            if (!photoData[i]) {
                return false;
            }
            link = $('<a href="' + photoData[i].permalink + '" />');
            $(imgCache[i]).addClass('mosaic');
            $(link).append(imgCache[i]);
            $('#tag-pics').append(link);
        }

        if (tagFlag) {
            setupTooltips();
        }

    },

    tagRefresh = function (data) {
        var i;
        photoData = data;
        // TODO clear imgCache here
        for (i = 0; i < photoData.length; i = i + 1) {
            imgCache[i] = new Image();
            imgCache[i].onload = preloadFinish;
            imgCache[i].src = photoData[i].image_uri;
        }
    },

    tagClick = function (el, type) {
        var params,
            ident,
            url = cfg.templateDir + '/ajax_browse.php';

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

        params = '?' + type + '=' + ident;

        $('a.current').removeClass('current');
        $(el).addClass('current');

        spinner(1);

        $.getJSON(url + params, tagRefresh);
    };

    return {
        init: function (opts, posts) {

            if (!opts || !posts) {
                return false;
            }

            cfg = opts;
            photoData = posts.data;

            $(['cat', 'tag', 'year']).each(function (i, group) {
                $('#' + group + 'Cloud a').each(function (i, el) {
                    $(el).click(function (e) {
                        tagClick(el, group);
                        e.preventDefault();
                    });
                });
            });

            if (photoData && typeof photoData[0].comment_count !== 'undefined') {
                setupTooltips();
            }

            // Make tagProgress visible but with zero opacity (allows us to calculate height).
            spinner(0);

        }
    };

}());
