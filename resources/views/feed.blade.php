@extends('layouts.app')
@section('head')
    <title>Текст-уник: о копирайтинге из опыта и знаний</title>
    <meta name="description"
          content="Полезный сайт для копирайтеров в формате коллективного блога. Уникальные статьи о копирайтинге для начинающих авторов и опытных копирайтеров. Здесь общаются и публикуются, чтобы делиться знаниями и продвигаться">
    <meta name="keywords" content="авторские статьи о копирайтинге">
@endsection

@section('content')
    <div class="container col-md-9 padding-0-phone">

        <div class="container col-xl-8 col-lg-12 feed">

            @if(!empty($title))
                <div class="container feed-title">
                    {!! $title !!}
                </div>
            @endif

            @include('article.list',['articles'=>$articles])

            <div class="ajax-load text-center" style="display:none">
                <p><img src="/images/ajax-loader.gif">Загрузка еще статей...</p>
            </div>
        </div>

        <div class="right-banner-container container col-xl-4 hidden-lg ">
            <div class="right-banner">

                <div class="right-banner-content">
                    <p class="title-secondary">О проекте</p>
                    <p>Этот сайт создан, чтобы выстраивать сообщество веб-райтеров и копирайтеров. Здесь вы сможете
                        размещать информационный, продающий, развлекательный контент и общаться, делиться знаниями и
                        продвигаться в профессии.</p>
                    <a href="/about">Подробнее</a>
                </div>

                <div class="right-banner-content">
                    @include('ads.google')
                </div>

                <div class="right-banner-content quiz-banner">
                    <div class="quiz-header">
                        <span>Делаете ли вы эти ошибки?</span>
                    </div>
                    <div class="quiz-container" >

                    </div>


                </div>

                <div class="right-banner-content">
                    <p class="title-secondary">Популярное</p>
                    @include('article.popular-tags')
                </div>

                <div class="right-banner-content">
                    @include('ads.google')
                </div>

            </div>
        </div>
    </div>


    <script type="text/javascript">

        let current_url = new URL(window.location.href);
        let page = current_url.searchParams.get("page");
        if (!page) page = 1;

        let stopAjaxPagination = false;

        $(window).on('scroll', onScroll);

        function onScroll() {

            if (stopAjaxPagination) {
                return;
            }

            if ($(window).scrollTop() > $(document).height() - $(window).height() - 2000) {
                page++;
                ajaxPagination(page);
            }
        }

        function ajaxPagination(page) {
            $.ajax(
                {
                    url: '?page=' + page,
                    type: "get",
                    async: false,
                    beforeSend: function () {
                        $('.ajax-load').show();
                    }
                })
                .done(function (data) {
                    $('.ajax-load').hide();

                    if (data.html == "") {
                        stopAjaxPagination = true;
                        return;
                    }

                    $('.pagination').remove();
                    $(".feed").append(data.html);
                })
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    console.log(thrownError);
                    stopAjaxPagination = true;
                });
        };
    </script>
@endsection
