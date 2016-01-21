function updateQuery (key, value) {
    var queryString = location.search.slice(1),
        params = {};

    queryString.replace(/([^=]*)=([^&]*)&*/g, function (_, key, value) {
        params[key] = value;
    });

    params[key] = value;

    var query = [];

    for (current in params) {
        if (!params.hasOwnProperty(current)) {
            continue;
        }

        query.push(current + '=' + params[current]);
    }

    location.search = query.join('&');
}

$(function() {
    'use strict';

    var $sidebar = $('.sidebar'),
        $sidebarMenu = $sidebar.find('.sidebar-menu'),
        $sidebarMenuGroups = $sidebarMenu.find('> li:gt(0)'),
        $sidebarSearch = $sidebarMenu.find('input[type="search"]'),
        $contentWrapper = $('.content-wrapper'),
        $rightSide = $('.right-side'),
        $mainHeader = $('.main-header'),
        $mainFooter = $('.main-footer');

    $sidebarSearch.on('keydown', function (e) {
        var $this = $(this);

        if (e.keyCode === 27) {
            $this.val('');

            $sidebarMenuGroups.show();
            $sidebarMenuGroups.filter('.treeview.active').find('> a').trigger('click');

            return false;
        }

        if ((e.keyCode === 13) || (e.keyCode === 40)) {
            return false;
        }
    });

    $sidebarSearch.on('keyup', function (e) {
        var $this = $(this);

        setTimeout(function () {
            var search = $this.val().toLowerCase();

            if (search.length === 0) {
                $sidebarMenuGroups.filter('.treeview.active').find('> a').trigger('click');
                $sidebarMenuGroups.show();
                return false;
            }

            $sidebarMenuGroups.each(function () {
                var $group = $(this),
                    text = $group.text().toLowerCase();

                if (text.indexOf(search) !== -1) {
                    $group.show();

                    if ($group.hasClass('treeview') && $group.find('ul:hidden').length) {
                        $group.find('> a').trigger('click');
                    }
                } else {
                    if ($group.hasClass('treeview') && $group.find('ul:visible').length) {
                        $group.find('> a').trigger('click');
                    }

                    $group.hide();
                }
            });
        }, 500);
    });

    var resetForm = function (form) {
        var id = 'a' + Math.floor ( Math.random ( ) * 1000 + 1 );

        $(form).find('input, select, textarea, label').each(function () {
            var $this = $(this);
            var value, attr, attrs = ['id', 'name', 'for'];
            var type = $this.attr('type');

            for (var i = attrs.length; i--;) {
                attr = attrs[i];

                if ((value = $this.attr(attr)) && (value.indexOf('[') !== -1)) {
                    $this.attr(attr, value.replace(/\[a?[0-9]+\]/g, '[' + id + ']'));
                }
            }

            if (type === 'checkbox') {
                $this.attr('checked', false);
            } else if ($this.attr('value') && (type !== 'hidden')) {
                $this.val('');
            }
        });

        return form;
    }

    $.fn.tree = function() {
        return this.each(function() {
            var btn = $(this).children('a').first();
            var menu = $(this).children('.treeview-menu').first();
            var isActive = $(this).hasClass('active');

            if (isActive) {
                menu.show();

                btn.children('.fa-angle-left')
                    .first()
                    .removeClass('fa-angle-left')
                    .addClass('fa-angle-down');
            }

            btn.click(function(e) {
                e.preventDefault();

                if (isActive) {
                    isActive = false;

                    menu.slideUp();

                    btn.children('.fa-angle-down')
                        .first()
                        .removeClass('fa-angle-down')
                        .addClass('fa-angle-left');

                    btn.parent('li').removeClass('active');
                } else {
                    isActive = true;

                    menu.slideDown();

                    btn.children('.fa-angle-left')
                        .first()
                        .removeClass('fa-angle-left')
                        .addClass('fa-angle-down');

                    btn.parent('li').addClass('active');
                }
            });
        });
    };

    var wHeight = $(window).height(),
        sHeight = $sidebar.height();

    if (wHeight >= sHeight) {
        var negative = ($mainHeader.outerHeight() + ($mainFooter.length ? $mainFooter.outerHeight() : 0));

        $contentWrapper.css('min-height', wHeight - negative);
        $rightSide.css('min-height', wHeight - negative);
    } else {
        $contentWrapper.css('min-height', sHeight);
        $rightSide.css('min-height', sHeight);
    }

    $contentWrapper.on('click', function() {
        var $body = $('body');

        if (($(window).width() <= 767) && $body.hasClass('sidebar-open')) {
            $body.removeClass('sidebar-open');
        }
    });

    $('[data-toggle="offcanvas"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse').toggleClass('sidebar-open');
    });

    $('[data-widget="collapse"]').on('click', function() {
        var $this = $(this),
            $box = $this.closest('.box'),
            $bf = $box.find('.box-body, .box-footer');

        if ($box.hasClass('collapsed-box')) {
            $box.removeClass('collapsed-box');
            $this.children('.fa-plus').removeClass('fa-plus').addClass('fa-minus');
            $bf.slideDown();
        } else {
            $box.addClass('collapsed-box');
            $this.children('.fa-minus').removeClass('fa-minus').addClass('fa-plus');
            $bf.slideUp();
        }
    });

    $sidebar.find('.treeview').tree();

    $('.htmleditor').summernote({
        styleWithSpan: false,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['misc', ['undo', 'redo', 'codeview']],
        ]
    });

    $('.select2').select2();

    $('.file-uploader').fileinput({
        maxFileSize: 1000,
        maxFilesNum: 10
    });

    $('.duplicate-action-add').on('click', function () {
        var $selection = $('.' + $(this).attr('rel')).last();

        if (!$selection.length) {
            return false;
        }

        $selection.before(resetForm($selection.clone(true)));

        return false;
    });

    var $datatable = $('.datatable');

    if ($datatable.length) {
        $datatable.dataTable({
            'bDestroy': true,
            'bPaginate': true,
            'bSort': true,
            'bInfo': true,
            'bAutoWidth': false,
            'bProcessing': true,
            'aaSorting': [],
            'oLanguage': gettext['datatables'],
            'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, 'All']],
            'fnInitComplete': function () {
                this.fnAdjustColumnSizing();
            }
        });

        $('form button[type=submit]').on('click', function () {
            var $form = $(this).closest('form');

            if ($form.find('input, select').length === 0) {
                return true;
            }

            var inputs = '';

            $datatable.each(function () {
                $('input, select', $(this).dataTable().fnGetNodes()).each(function () {
                    var $this = $(this),
                        checkbox = $this.is(':checkbox') || $this.is(':radio');

                    if (!checkbox || (checkbox && $this.is(':checked'))) {
                        inputs += '<input type="hidden" name="' + $this.attr('name') + '" value="' + $this.val() + '" />';
                    }
                });
            });

            if (($('input:checked', $form).length === 0) && (inputs === '')) {
                return true;
            }

            $(inputs).appendTo($form);

            return true;
        });
    }

    $('button[name="uploads-delete"]').on('click', function (e) {
        e.stopPropagation();

        var $this = $(this),
            $modal = $('#uploads-delete-modal'),
            $form = $('form', $modal);

        $.each($this.data(), function (k, v) {
            var $input = $form.find('input[name="' + k + '"]');

            if ($input.length) {
                $input.val(v);
            }
        });

        $modal.modal();
    });

    $('button[name="uploads-copy-url"]').on('click', function (e) {
        e.stopPropagation();

        window.prompt(gettext['copy-clipboard'], $(this).data('url'));
    });

    var $wait = $('.submit-wait');

    if ($wait.length) {
        if ($wait.is('form') !== true) {
            $wait = $wait.closest('form');
        }

        $wait.on('submit', function () {
            var message = $wait.data('message');

            $('body').append('<div class="waiting-layer"></div>')
                .append('<div class="waiting-layer-message">' + message + '</div>');

            return true;
        });
    }

    $('.form-group[data-related] a').on('click', function(e) {
        e.preventDefault();

        var $this = $(this),
            $select = $this.closest('.form-group').find('select');

        if ($select.val()) {
            window.location.href = $this.attr('href') + '/' + $select.val();
        }
    });

    $('[data-change-submit]').on('change', function (e) {
        e.preventDefault();

        var $this = $(this),
            $form = $this.closest('form');

        if ($form.length) {
            return $form.submit();
        }

        updateQuery($this.attr('name'), $this.val());
    });

    $('[data-print]').on('click', function (e) {
        e.preventDefault();

        var $this = $(this),
            $body = $($this.data('print'));

        if ($body.length === 0) {
            return;
        }

        var $head = $('head').clone();

        $head.find('script').remove();
        $body.find('script').remove();

        var win = window.open();

        win.focus();

        win.document.open();
        win.document.write('<html><head>' + $head.html() + '</head>');
        win.document.write('<body style="background: none;">' + $body.html() + '</body></html>');
        win.document.close();

        win.onload = function() {
            win.print();
            win.close();
        };
    });
});
