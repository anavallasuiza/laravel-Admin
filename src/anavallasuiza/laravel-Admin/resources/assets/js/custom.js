$(function() {
    'use strict';

    var $menuGroups = $('.sidebar-menu > li:gt(0)');

    $('.sidebar-menu input[type="search"]').on('keydown', function (e) {
        var $this = $(this);

        if (e.keyCode === 27) {
            $this.val('');

            $menuGroups.show();
            $menuGroups.filter('.treeview.active').find('> a').trigger('click');

            return false;
        }

        if ((e.keyCode === 13) || (e.keyCode === 40)) {
            return false;
        }
    });

    $('.sidebar-menu input[type="search"]').on('keyup', function (e) {
        var $this = $(this);

        setTimeout(function () {
            var search = $this.val().toLowerCase();

            if (search.length === 0) {
                $menuGroups.filter('.treeview.active').find('> a').trigger('click');
                $menuGroups.show();
                return false;
            }

            $menuGroups.each(function () {
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

    $('.content-wrapper').css('min-height', $('.main-sidebar').height());

    $('[data-toggle="offcanvas"]').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse').toggleClass('sidebar-open');
    });

    $('.content-wrapper').on('click', function() {
        var $body = $('body');

        if (($(window).width() <= 767) && $body.hasClass('sidebar-open')) {
            $body.removeClass('sidebar-open');
        }
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

    $('.sidebar .treeview').tree();

    $('.htmleditor').summernote({
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

    var $datatable = $('.datatable').dataTable({
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

    if ($datatable.length) {
        $('form button[type=submit]').on('click', function () {
            var $form = $(this).closest('form'),
                $checks = $form.find('input[type="checkbox"], input[type="radio"]');

            if ($checks.length === 0) {
                return true;
            }

            var inputs = '';

            $('input, select', $datatable.fnGetNodes()).each(function () {
                var $this = $(this),
                    checkbox = $this.is(':checkbox') || $this.is(':radio');

                if (!checkbox || (checkbox && $this.is(':checked'))) {
                    inputs += '<input type="hidden" name="' + $this.attr('name') + '" value="' + $this.val() + '" />';
                }
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

    $('[data-change-submit]').on('change', function (e) {
        e.preventDefault();

        var $this = $(this),
            $form = $this.closest('form');

        if ($form.length) {
            $form.submit();
        } else {
            window.location = '?' + $this.attr('name') + '=' + encodeURIComponent($this.val());
        }
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