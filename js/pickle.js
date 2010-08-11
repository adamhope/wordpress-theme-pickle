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
