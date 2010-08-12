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

    var preload,
        initialPosts,

        preloadFinish = function () {

            var tagFlag, tmpdata, link;

            // Remove all images from container
            $('.mosaic').remove();

            // Create image element and insert into container.
            // TODO might not work
            tagFlag = typeof preload[0].retrieve('imgData').comment_count != 'undefined';

            for (var i = 0; i < preload.length; i++) {
                tmpdata = preload[i].retrieve('imgData'),
                link = $('<a href="' + tmpdata.permalink + '" />');

                $(preload[i]).addClass('mosaic');

                $(link).append(preload[i]);

                if (tagFlag) {
                    $(preload[i]).data('tip:title', tmpdata.post_title);
                    $(preload[i]).data(
                        'tip:text', tmpdata.post_date + "<br />" + tmpdata.comment_count + " " + (tmpdata.comment_count === 1 ? "comment" : "comments")
                    );
                }
                $('#tagContainer').append(link);
            }

            if (tagFlag) {
                // TODO need tooltip alternative
                var tipz = new Tips('.mosaic', {
                    className: 'tipz',
                    hideDelay: 50,
                    showDelay: 50
                });
            }

            $('#tagContainer').css({
                'height': 'auto',
                'overflow': 'auto'
            });

        },

    tagRefresh = function (data) {
        // Create asset manager to grab all images from the server.
        var srcArray = [];
        for (var i = 0; i < data.length; i++) {
            srcArray[i] = data[i].image_uri;
        }
        // TODO need alternative
        preload = new Asset.images(srcArray, {
            onComplete: preloadFinish
        });

        // Store data associated with each image using Mootools element storage.
        for (var i = 0; i < data.length; i++) {
            preload[i].store('imgData', data[i]);
        }
    },
    
    tagClick = function (el, type) {
        
        var params,
            ident,
            cur,
            url = user_opts.templateDir + '/ajax_browse.php';

        if (type === "tag") {
            ident = /tag-link-(\d+)/.exec($(el).attr('class'))[1];
        } else if (type === "cat") {
            ident = /cat-item-(\d+)/.exec($(el).parent().attr('class'))[1];
        } else {
            ident = $(l).html();
        }

        // Set up heights for a smooth transition.
        $('tagPics').css('height', $('tagPics').scrollHeight);

        $('tagContainer').css({
            'overflow': 'hidden',
            'height': $('tagContainer').scrollHeight
        });

        // Highlight this link as the current one.
        cur = $('a.current');
        if (cur.length > 0) {
            $(cur[0]).removeClass('current');
        }
        $(el).addClass('current');

        // Send off a JSON Request to the server for the list of images associated with this tag/year.
        params = '?' + type + '=' + ident;
        $.getJSON(url + params, tagRefresh);
    };

    return {
        init: function () {
            
            initialPosts = user_opts.posts;

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

            if (initialPosts && typeof initialPosts[0].comment_count !== 'undefined') {
                $('.mosaic').each(function (i, el) {
                    $(el).data('tip:title', initialPosts[i].post_title);
                    $(el).data('tip:text', initialPosts[i].post_date + "<br />" + initialPosts[i].comment_count + " " + (initialPosts[i].comment_count === 1 ? "comment" : "comments"));
                }).tooltip({
                    track: true,
                    delay: 0,
                    showURL: false,
                    fade: 250 ,
                    bodyHandler: function() { 
                        return $(this).data('tip:title') + '<br />' + $(this).data('tip:text');
                    }
                });
            }

            // Make tagProgress visible but with zero opacity (allows us to calculate height).
            $('#tagProgress').css({
                'opacity': 0,
                'visibility': 'visible'
            });

        }
    };
    
}(browseOpts));

