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
        context: '#topcontent',
        mainImage: '#mainimage'
    },

    loadComplete = function () {
        $('#topcontent').css({
            width: preloadedImg.width
        });
        $('#mainimage').attr({
            'width': preloadedImg.width,
            'height': preloadedImg.height,
            'src': preloadedImg.src
        });
    },

    refresh = function (data) {
        // fade out image
        // fade in new image
        preloadedImg = new Image();
        preloadedImg.onload = loadComplete;
        preloadedImg.src = data.image_uri;

        // $('#mainimage').css({'visibility': 'hidden'});
        nextPostID = data.next_post;
        prevPostID = data.prev_post;

        $('#nextPostLink').html(data.next_post === 0 ? '' : '&raquo;');
        $('#prevPostLink').html(data.prev_post === 0 ? '' : '&laquo;');

        $('#overNextLink').css({
            'display': data.next_post === 0 ? 'none' : 'block'
        });
        $('#overPrevLink').css({
            'display': data.prev_post === 0 ? 'none' : 'block'
        });

        // this.nextPostLink.href = $('#overNextLink').href = data.next_post_perm;
        // this.prevPostLink.href = $('#overPrevLink').href = data.prev_post_perm;
        $('#panelExif').html(data.exif);
        $('#comment').html(data.comment_count + " comment" + (data.comment_count !== 1 ? "s" : ""));
        $('#comment').attr({
            'href': data.permalink + '#comments'
        });
        $('#textTitle').html('<a href="' + data.permalink + '">' + data.post_title + '</a><span id="inlinedate">' + data.post_date + '</span>');
        $('#panelInfo').html(data.post_content);

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
        $.getJSON(ajaxSource + params, refresh);
    },

    setupNavigation = function () {
        $('.previous, .next').each(function (i, el) {
            $(el).click(function (e) {
                getNewContent(el);
                e.preventDefault();
            });
        });
        $('#exif').click(function (e) {
            $('#panelExif').toggleFade();
            e.preventDefault();
        });
        $('#info').click(function (e) {
            $('#panelInfo').toggleFade();
            e.preventDefault();
        });
    };

    return {
        init: function () {
            // TODO sort out config
            cfg = $.extend({}, DEFAULTS, user_opts);
            nextPostID = cfg.nextPostID;
            prevPostID = cfg.prevPostID;
            ajaxSource = cfg.templateDir + '/ajax_blog.php';
            setupNavigation();
        }
    };

}(opts));
