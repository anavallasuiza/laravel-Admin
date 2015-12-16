$(function() {
    'use strict';

    var $gettextForm = $('#form-gettext'),
        $gettextGroups = $gettextForm.find('.tab-pane > .gettext-group'),
        $gettextSearch = $gettextForm.find('input[type="search"]'),
        $gettextEmpty = $gettextForm.find('[data-gettext="empty"]'),
        common = /\/(libs|models)\//;

    if ($gettextForm.length === 0) {
        return;
    }

    var gettextSearch = function ($group, text) {
        var minChar = 3,
            search = $gettextSearch.val().toLowerCase();

        if (search.length < minChar) {
            return true;
        }

        text = ($group.find('input').val() + $group.find('label').text()).toLowerCase();

        return (text.indexOf(search) !== -1);
    };

    var gettextEmptyShow = function ($group, text) {
        return (!$gettextEmpty.is(':checked') || ($group.find('input').val() === ''));
    };

    var gettextFilter = function () {
        $gettextGroups.hide();

        $gettextGroups.each(function () {
            var $group = $(this),
                text = $group.text().toLowerCase();

            if (gettextSearch($group, text) && gettextEmptyShow($group, text)) {
                $group.show();
            }
        });
    }

    $gettextSearch.on('keydown', function (e) {
        var $this = $(this);

        if (e.keyCode === 27) {
            e.preventDefault();

            $this.val('');
            $gettextGroups.show();

            return false;
        }

        if ((e.keyCode === 13) || (e.keyCode === 40)) {
            e.preventDefault();
            return false;
        }

        setTimeout(function () {
            gettextFilter();
        }, 500);
    });

    $gettextForm.find('[data-gettext]').on('change', function () {
        gettextFilter();
    });

    $gettextGroups.find('label').on('dblclick', function () {
        var $this = $(this);
        $this.siblings('input').val($this.text());
    });

    $gettextGroups.find('.show-references').on('click', function (e) {
        e.preventDefault();
        $(this).siblings('.references').slideToggle();
    });

    $gettextForm.find('input[id^="entry-"]').on('change', function (e) {
        $gettextForm.data('changed', true);
    });

    $gettextForm.on('submit', function () {
        $gettextForm.data('changed', false);
    });

    window.onbeforeunload = function () {
        if ($gettextForm.data('changed')) {
            return gettext['gettext-changes'];
        }
    };
});