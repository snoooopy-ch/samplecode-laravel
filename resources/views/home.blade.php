@extends('layouts.app')

@section('title', trans('app.menu.home'))

@section('contents')
    <!-- WELCOME -->

    <div id="welcome">
        <section class="p-b-0 p-t-0 background-transparent">
            <div class="container p-t-0">
                <div class="slide-captions text-start text-light title pt-4">
                    <!-- Captions -->
                    <div class="heading-text heading-section text-light text-center mb-7 animate__animated pt-4" 
                        data-animate="animate__fadeInUp">
                        <img id="mv-img" class="mv-img" src="{{ asset('images/visual-text.png') }}" />
                    </div>
                    <!-- end: Captions -->
    
                    
                    <div class="col-lg-6 col-12 center text-light animate__animated rounded"
                        data-animate="animate__fadeInRight">
                        <div class="hero-heading-2 text-light back-theme-trans" style="border: 2px solid #6666ff">
                            @if ($lastData)
                                <h5 class="m-b-0">@lang('home.at_moment', ['time' => serverTime2Local($lastData->display_time?? $lastData->created_at)])</h5>
                            @endif
                            @if (isset($realInfo))
                                <br>
                                <h5 class="m-t-0 text-bold pink-emphass">@lang('home.realdesc')</h5>
                                <h5 class="m-t-0 pink-emphass">
                                    {!! trans('home.your_real_level', ['level' => $realInfo->levelInfo->name->level]) !!}
                                </h5>

                                <h5 class="m-t-0 pink-emphass">
                                    {{ trans('home.current_bonus', [
                                        'amount' => _number_format($realInfo->basic_bonus * $realInfo->bonus_rate / 100, 0)
                                    ])}}
                                </h5>
                                
                                @if ($realInfo->level == 10)
                                    <h5 class="m-t-0 pink-emphass">@lang('home.current_is_max')</h5>
                                @else
                                    @if (isset($realGradeUp))
                                        <h5 class="m-t-0 pink-emphass">@lang('home.can_real_upgrade', [
                                            'amount' => _number_format($realGradeUp->need_bet, 0)
                                    ])</h5>
                                    @endif
                                @endif
                            @endif
                            @if (isset($binaryInfo))
                                <br>
                                <h5 class="m-t-0 text-bold pink-emphass">@lang('home.binarydesc')</h5>
                                <h5 class="m-t-0 pink-emphass">
                                    {!! trans('home.your_binary_level', ['level' => $binaryInfo->levelInfo->name->level]) !!}
                                </h5>

                                <h5 class="m-t-0 pink-emphass">
                                    {{ trans('home.current_bonus', [
                                        'amount' => _number_format($binaryInfo->basic_bonus * $binaryInfo->bonus_rate / 100, 0)
                                    ])}}
                                </h5>
                                
                                @if ($binaryInfo->level == 10)
                                    <h5 class="m-t-0 pink-emphass">@lang('home.current_is_max')</h5>
                                @else
                                    @if (isset($binaryGradeUp))
                                        <h5 class="m-t-0 pink-emphass">@lang('home.can_binary_upgrade', [
                                            'amount' => _number_format($binaryGradeUp->need_bet, 0)
                                    ])</h5>
                                    @endif
                                @endif
                            @endif
                            @if (!isset($realInfo) && !isset($binaryInfo))
                                <h5 class="m-b-0 text-center">{!! trans('home.welcome') !!}</h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="container home-tree mt-0 position-relative" id="home-tree-container">
                <div class="tabs tabs-clean col-lg-6 col-12 center pt-5">
                    <ul class="nav nav-tabs mb-0" id="home-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home2" role="tab" aria-controls="home" aria-selected="true">@lang('home.realtree')</span></a>
                        </li>
                        <li class="nav-item m-l-10">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile2" role="tab" aria-controls="profile" aria-selected="false">@lang('home.binarytree')</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-2" id="myTabContent2">
                        <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab">
                            <div class="container home-tree mt-0 position-relative p-0">
                                @if (isset($realInfo))
                                <div class="equipment home-tool text-center" data-animate="animate__fadeInLeft">
                                    <img class="img-fluid mb-5" src="{{ $realInfo->levelInfo->image }}"/>
                                </div>
                                @endif
                            </div>
                            <div class="container home-tree mt-0 position-relative container-tree">
                                <home-real id="realtree-page"
                                    :json="realTree" 
                                    :class="{landscape: landscape.length}" 
                                    :trans="trans"></home-real>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile2" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="container home-tree mt-0 position-relative p-0">
                                @if (isset($binaryInfo))
                                <div class="equipment home-tool text-center" data-animate="animate__fadeInLeft">
                                    <img class="img-fluid mb-5" src="{{ $binaryInfo->levelInfo->image }}"/>
                                </div>
                                @endif
                            </div>
                            <div class="container home-tree mt-0 position-relative container-tree">
                                <home-tree id="tree-page"
                                    :json="data" 
                                    :class="{landscape: landscape.length}" 
                                    :trans="trans"></home-tree>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
        
        <section class="background-transparent pt-0" id="alliance">
            <div class="container p-t-0">
                <h4 class="text-primary p-10"><span class="text-light back-theme p-10 rounded">@lang('home.alliance')</span></h4>
                <ul class="grid grid-4-columns">
                    @foreach ($alliances as $alliance)
                        <li class="alliance-item border-0">
                            <div class="card border-0 background-transparent">
                                <div class="card-body p-0 background-transparent">
                                    <a href="{{ $alliance->site_url}}" target="_blank"><img class="img_alliance" src="{{ $alliance->site_image}}" /></a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <div id="arrows-examples">
            <div class="arrow-up" id="alliance-show" v-on:click="showAlliance"></div>
        </div>
    </div>
    
@endsection


@section('scripts')
    <script>
        let trans = @json(trans('home'));
    </script>
    <script src="{{ cAsset('js/pages/home.js') }}"></script>
@endsection
