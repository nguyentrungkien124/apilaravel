(function ($) {
    "use strict"; // Start of use strict
    /* ---------------------------------------------
     Resize mega menu
     --------------------------------------------- */

    function responsive_megamenu_item(container, element) {
        if ( container != 'undefined' ) {
            var container_width  = 0,
                container_offset = container.offset();

            if ( typeof container_offset != 'undefined' ) {
                container_width = container.innerWidth();
                setTimeout(function () {
                    $(element).children('.megamenu').css({'max-width': container_width + 'px'});
                    var sub_menu_width = $(element).children('.megamenu').outerWidth(),
                        item_width     = $(element).outerWidth();
                    $(element).children('.megamenu').css({'left': '-' + (sub_menu_width / 2 - item_width / 2) + 'px'});
                    var container_left  = container_offset.left,
                        container_right = (container_left + container_width),
                        item_left       = $(element).offset().left,
                        overflow_left   = (sub_menu_width / 2 > (item_left - container_left)),
                        overflow_right  = ((sub_menu_width / 2 + item_left) > container_right);

                    if ( overflow_left ) {
                        var left = (item_left - container_left);
                        $(element).children('.megamenu').css({'left': -left + 'px'});
                    }
                    if ( overflow_right && !overflow_left ) {
                        var left = (item_left - container_left);
                        left     = left - (container_width - sub_menu_width);
                        $(element).children('.megamenu').css({'left': -left + 'px'});
                    }
                }, 100);
            }
        }
    }

    function stelina_resize_megamenu() {
        var window_size = jQuery('body').innerWidth();
        window_size += stelina_get_scrollbar_width();
        if ( $('.stelina-menu-wapper.horizontal .item-megamenu').length > 0 && window_size > 991 ) {
            $('.stelina-menu-wapper.horizontal .item-megamenu').each(function () {
                var _this            = $(this),
                    _data_responsive = _this.children('.megamenu').data('responsive'),
                    _container       = _this.closest('.stelina-menu-wapper');
                if ( _data_responsive != '' )
                    _container = _this.closest(_data_responsive);

                responsive_megamenu_item(_container, _this);
            });
        }
    }

    /**==============================
     Auto width Vertical menu
     ===============================**/
    function stelina_auto_width_vertical_menu() {
        $('.stelina-menu-wapper.vertical.support-mega-menu').each(function () {
            var menu_offset = $(this).offset(),
                menu_width  = parseInt($(this).actual('width')),
                menu_left   = menu_offset.left + menu_width;

            $(this).find('.megamenu').each(function () {
                var data_responsive   = $(this).data('responsive'),
                    element_caculator = $('.container');
                if ( data_responsive != '' )
                    element_caculator = $(this).closest(data_responsive);

                var container_width  = parseInt(element_caculator.innerWidth()) - 30,
                    container_offset = element_caculator.offset(),
                    container_left   = container_offset.left + container_width,
                    width            = (container_width - menu_width);

                if ( menu_offset.left > container_left || menu_left < container_offset.left )
                    width = container_width;
                if ( menu_left > container_left )
                    width = container_width - (menu_width - (menu_left - container_left)) - 30;

                if ( width > 0 )
                    $(this).css('max-width', width + 'px');
            });
        })
    }

    function stelina_get_scrollbar_width() {
        var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
            $outer = jQuery('<div style="width:200px;height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
            inner  = $inner[ 0 ],
            outer  = $outer[ 0 ];
        jQuery('body').append(outer);
        var width1 = inner.offsetWidth;
        $outer.css('overflow', 'scroll');
        var width2 = outer.clientWidth;
        $outer.remove();
        return (width1 - width2);
    }

    /* ---------------------------------------------
     MOBILE MENU
     --------------------------------------------- */
    function stelina_menuclone_all_menus() {
        if ( !$('.stelina-menu-clone-wrap').length && $('.stelina-clone-mobile-menu').length > 0 ) {
            $('body').prepend('<div class="stelina-menu-clone-wrap">' +
                '<div class="stelina-menu-panels-actions-wrap">' +
                '<span class="stelina-menu-current-panel-title">MENU</span>' +
                '<a class="stelina-menu-close-btn stelina-menu-close-panels" href="#">x</a></div>' +
                '<div class="stelina-menu-panels"></div>' +
                '</div>');
        }
        var i                = 0,
            panels_html_args = Array();
        if ( !$('.stelina-menu-clone-wrap .stelina-menu-panels #stelina-menu-panel-main').length ) {
            $('.stelina-menu-clone-wrap .stelina-menu-panels').append('<div id="stelina-menu-panel-main" class="stelina-menu-panel stelina-menu-panel-main"><ul class="depth-01"></ul></div>');
        }

        $('.stelina-clone-mobile-menu').each(function () {
            var $this              = $(this),
                thisOfficeu           = $this,
                this_menu_id       = thisOfficeu.attr('id'),
                this_menu_clone_id = 'stelina-menu-clone-' + this_menu_id;

            if ( !$('#' + this_menu_clone_id).length ) {
                var thisClone = $this.clone(true); // Clone Wrap
                thisClone.find('.menu-item').addClass('clone-menu-item');

                thisClone.find('[id]').each(function () {
                    // Change all tab links with href = this id
                    thisClone.find('.vc_tta-panel-heading a[href="#' + $(this).attr('id') + '"]').attr('href', '#' + stelina_menuadd_string_prefix($(this).attr('id'), 'stelina-menu-clone-'));
                    thisClone.find('.stelina-menu-tabs .tabs-link a[href="#' + $(this).attr('id') + '"]').attr('href', '#' + stelina_menuadd_string_prefix($(this).attr('id'), 'stelina-menu-clone-'));
                    $(this).attr('id', stelina_menuadd_string_prefix($(this).attr('id'), 'stelina-menu-clone-'));
                });

                thisClone.find('.stelina-menu-menu').addClass('stelina-menu-menu-clone');

                // Create main panel if not exists

                var thisMainPanel = $('.stelina-menu-clone-wrap .stelina-menu-panels #stelina-menu-panel-main ul');
                thisMainPanel.append(thisClone.html());

                stelina_menu_insert_children_panels_html_by_elem(thisMainPanel, i);
            }
        });
    }

    // i: For next nav target
    function stelina_menu_insert_children_panels_html_by_elem($elem, i) {
        if ( $elem.find('.menu-item-has-children').length ) {
            $elem.find('.menu-item-has-children').each(function () {
                var thisChildItem = $(this);
                stelina_menu_insert_children_panels_html_by_elem(thisChildItem, i);
                var next_nav_target = 'stelina-menu-panel-' + i;

                // Make sure there is no duplicate panel id
                while ( $('#' + next_nav_target).length ) {
                    i++;
                    next_nav_target = 'stelina-menu-panel-' + i;
                }
                // Insert Next Nav
                thisChildItem.prepend('<a class="stelina-menu-next-panel" href="#' + next_nav_target + '" data-target="#' + next_nav_target + '"></a>');

                // Get sub menu html
                var sub_menu_html = $('<div>').append(thisChildItem.find('> .submenu').clone()).html();
                thisChildItem.find('> .submenu').remove();

                $('.stelina-menu-clone-wrap .stelina-menu-panels').append('<div id="' + next_nav_target + '" class="stelina-menu-panel stelina-menu-sub-panel stelina-menu-hidden">' + sub_menu_html + '</div>');
            });
        }
    }

    function stelina_menuadd_string_prefix(str, prefix) {
        return prefix + str;
    }

    function stelina_menuget_url_var(key, url) {
        var result = new RegExp(key + "=([^&]*)", "i").exec(url);
        return result && result[ 1 ] || "";
    }

    function stelina_close_mobile_menu() {
        // BOX MOBILE MENU
        $(document).on('click', '.menu-toggle', function () {
            $('.stelina-menu-clone-wrap').addClass('open');
            return false;
        });
        // Close box menu
        $(document).on('click', '.stelina-menu-clone-wrap .stelina-menu-close-panels', function () {
            $('.stelina-menu-clone-wrap').removeClass('open');
            return false;
        });
        $(document).on('click', function (event) {
            if ( $('body').hasClass('rtl') ) {
                if ( event.offsetX < 0 )
                    $('.stelina-menu-clone-wrap').removeClass('open');
            } else {
                if ( event.offsetX > $('.stelina-menu-clone-wrap').width() )
                    $('.stelina-menu-clone-wrap').removeClass('open');
            }
        });
    }

    // Open next panel
    $(document).on('click', '.stelina-menu-next-panel', function (e) {
        var $this     = $(this),
            thisItem  = $this.closest('.menu-item'),
            thisPanel = $this.closest('.stelina-menu-panel'),
            target_id = $this.attr('href');

        if ( $(target_id).length ) {
            thisPanel.addClass('stelina-menu-sub-opened');
            $(target_id).addClass('stelina-menu-panel-opened').removeClass('stelina-menu-hidden').attr('data-parent-panel', thisPanel.attr('id'));
            // Insert current panel title
            var item_title     = thisItem.find('.stelina-menu-item-title').attr('title'),
                firstItemTitle = '';

            if ( $('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').length > 0 ) {
                firstItemTitle = $('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').html();
            }

            if ( typeof item_title != 'undefined' && typeof item_title != false ) {
                if ( !$('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').length ) {
                    $('.stelina-menu-panels-actions-wrap').prepend('<span class="stelina-menu-current-panel-title"></span>');
                }
                $('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').html(item_title);
            }
            else {
                $('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').remove();
            }

            // Back to previous panel
            $('.stelina-menu-panels-actions-wrap .stelina-menu-prev-panel').remove();
            $('.stelina-menu-panels-actions-wrap').prepend('<a data-prenttitle="' + firstItemTitle + '" class="stelina-menu-prev-panel" href="#' + thisPanel.attr('id') + '" data-cur-panel="' + target_id + '" data-target="#' + thisPanel.attr('id') + '"></a>');
        }

        e.preventDefault();
    });

    // Go to previous panel
    $(document).on('click', '.stelina-menu-prev-panel', function (e) {
        var $this        = $(this),
            cur_panel_id = $this.attr('data-cur-panel'),
            target_id    = $this.attr('href');

        $(cur_panel_id).removeClass('stelina-menu-panel-opened').addClass('stelina-menu-hidden');
        $(target_id).addClass('stelina-menu-panel-opened').removeClass('stelina-menu-sub-opened');

        // Set new back button
        var new_parent_panel_id = $(target_id).attr('data-parent-panel');
        if ( typeof new_parent_panel_id == 'undefined' || typeof new_parent_panel_id == false ) {
            $('.stelina-menu-panels-actions-wrap .stelina-menu-prev-panel').remove();
            $('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').html('MAIN MENU');
        }
        else {
            $('.stelina-menu-panels-actions-wrap .stelina-menu-prev-panel').attr('href', '#' + new_parent_panel_id).attr('data-cur-panel', target_id).attr('data-target', '#' + new_parent_panel_id);
            // Insert new panel title
            var item_title = $('#' + new_parent_panel_id).find('.stelina-menu-next-panel[data-target="' + target_id + '"]').closest('.menu-item').find('.stelina-menu-item-title').attr('data-title');
            item_title     = $(this).data('prenttitle');
            if ( typeof item_title != 'undefined' && typeof item_title != false ) {
                if ( !$('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').length ) {
                    $('.stelina-menu-panels-actions-wrap').prepend('<span class="stelina-menu-current-panel-title"></span>');
                }
                $('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').html(item_title);
            }
            else {
                $('.stelina-menu-panels-actions-wrap .stelina-menu-current-panel-title').remove();
            }
        }

        e.preventDefault();
    });

    /* ---------------------------------------------
     Scripts resize
     --------------------------------------------- */

    $(window).on("resize", function () {
        stelina_resize_megamenu();
        stelina_close_mobile_menu();
        stelina_auto_width_vertical_menu();
    });
    window.addEventListener('load',
        function (ev) {
            stelina_resize_megamenu();
            stelina_close_mobile_menu();
            stelina_auto_width_vertical_menu();
            stelina_menuclone_all_menus();
        }, false);

})(jQuery); // End of use strict