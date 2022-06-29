@extends('layouts.app')

@section('title', trans('history.title'))

@section('contents')
<?php
    $languages = config('lang');
    $genders = config('gender');
?>

@section('styles')
    <style>
        #game_history div[name='HeaderSettings']>button,
        #bonus_history div[name='HeaderSettings']>button {
            display: none;
        }
    </style>
@endsection
<div class="body-min-height back-semitrans-theme">
    <div class="container">
        <!-- Page title -->
        <section id="page-title" class="page-title-left text-dark background-transparent">
            <div class="container">
                <div class="page-title">
                    <h1>{{ trans('history.page_title') }}</h1>
                    <span>{{ trans('history.page_title_desc') }}</span>
                </div>
            </div>
        </section>
        <!-- end: Page title -->
        <hr>
        <!-- Content -->
        
        <section id="page-content" class="background-transparent">
            <div class="container" id="history-page">
                <div class="row">
                    <div class="col-md-12">
                        <div id="bonus_history" role="tabpanel" aria-labelledby="bonus_history-tab">
                            <div class="card back-semi-theme border-theme  border-theme ">
                                <div class="card-body">
                                    <h6>@lang('history.bonus_history')</h6>
                                    <bonus-history trans='@json(trans('history'))'></bonus-history>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        let trans = @json(trans('tree'));
        let historyTrans = @json(trans('history'));
    </script>
    <script src="{{ asset('plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ cAsset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ cAsset('plugins/bootstrap-datetimepicker/tempusdominus-bootstrap-4.js') }}"></script>
    <script src="{{ cAsset('js/pages/history.js') }}"></script>
    
    
@endsection
