@extends('admin::layouts.right-side')

@section('content')

<form id="form-gettext" method="post">
    {!! token() !!}

    <input type="hidden" name="locale" value="{{ $current }}" />

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            @foreach ($locales as $locale)
            <li {!! ($locale === $current) ? 'class="active"' : '' !!}><a href="{{ route('admin.management.gettext.index', $locale) }}">{{ $locale }}</a></li>
            @endforeach
        </ul>

        <div class="tab-content">
            <div class="tab-pane in active">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-8">
                            <input type="search" placeholder="{{ __('Search (min. 3 chars)') }}..." class="form-control" />
                        </div>

                        <div class="col-lg-4">
                            <div class="btn-group pull-right" data-toggle="buttons">
                                <label class="btn">
                                    <input type="checkbox" data-gettext="empty" autocomplete="off" /> {{ __('Only empty') }}
                                </label>

                                <label class="btn">
                                    <input type="checkbox" data-gettext="admin" autocomplete="off" /> {{ __('Only admin') }}
                                </label>

                                <label class="btn">
                                    <input type="checkbox" data-gettext="web" autocomplete="off" /> {{ __('Only web') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $i = 0; ?>

                @foreach ($entries as $entry)
                <div class="form-group gettext-group">
                    <label for="entry-{{ ++$i }}">{{{ $entry->getOriginal() }}}</label>

                    <?php if ($entry->lines) { ?>
                    <a href="#" class="fa fa-info-circle show-references"></a>

                    <ul class="list-unstyled references text-muted">
                        <li>{{ implode('</li><li>', $entry->lines) }}</li>
                    </ul>
                    <?php } ?>

                    <input id="entry-{{ $i }}" type="text" name="translations[{{{ $entry->getOriginal() }}}]" value="{{{ $entry->getTranslation() }}}" class="form-control" />
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="box-footer clearfix">
        <div class="pull-right">
            <button type="submit" {!! empty($I->admin) ? 'disabled' : '' !!} name="_action" value="save" class="btn btn-success">
                {{ __('Save') }}
            </button>

            <button type="submit" {!! empty($I->admin) ? 'disabled' : '' !!} name="refresh" value="true" class="btn btn-primary">
                {{ __('Search for new translations') }}
            </button>

            <button type="submit" {!! empty($I->admin) ? 'disabled' : '' !!} name="_action" value="download" class="btn btn-primary">
                {{ __('Download translations') }}
            </button>
        </div>
    </div>
</form>

@stop