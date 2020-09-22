@extends('layouts.app')

@section('head')

    <title>Как написать статью на «Текст-уник»</title>
    <meta name="robots" content="noindex">

@endsection

@section('content')
    <div class="container col-md-8">
        <h1>Как написать статью на «Текст-уник»</h1>
        <p>
            Добро пожаловать на площадку, где так легко размещать уникальные тексты о копирайтинге!
            <br>Чтобы написать статью на «Текст-уник», необходимо <a href="{{route('login')}}">авторизоваться</a>. Если у Вас еще нет аккаунта на нашем сайте, Вы можете <a href="{{route('register')}}">зарегистрироваться</a>
            <h3 class="text-main">&nbsp;Как стать полноправным автором «Текст-уник» всего за 5 шагов</h3>
        </p>
        <ol>
            <li>Вы пишете пост на актуальную Вам тему.&nbsp;<i class="fas fa-question-circle" id="open-what-to-write"></i></li>
            <li>Ваш текст отправляется на модерацию.&nbsp;</li>
            <li>После проверки статья попадает в главную ленту сайта.</li>
            <li>Сообщение о публикации Вы получаете на свой email.</li>
            <li>В ленте Ваша статья набирает просмотры и комментарии.&nbsp;</li>
        </ol>
        <h3 class="text-main">Зачем писать статью на «Текст-уник»</h3>
        <p>Написать пост — значит, найти интересную тему по копирайтингу и полноценно ее раскрыть. Расскажите о своем опыте! Изложите всю суть по полочкам. Поделитесь знаниями – и получите полноценный отклик.&nbsp;</p>
        <ul>
            <li>Начинающие копирайтеры прочитают Ваш пост с благодарностью, ибо получат огромную пользу. А это так приятно!&nbsp;</li>
            <li>Опытные коллеги дадут в комментариях бесценные советы. Часто узнать подобное можно лишь на платных курсах и вебинарах – и этот фидбэк дорогого стоит!&nbsp;</li>
        </ul>
        <p>Каждая статья публикуется с сохранением права авторства. Помните: Ваша публикация — это ваша репутация. Желаем успеха!&nbsp;</p>
        <div id="gtx-trans" style="position: absolute; left: -44px; top: -17.3333px;">
            <div class="gtx-trans-icon">
                <br>
            </div>
        </div>

    </div>

    <div id="what-to-write">
       <h3>Пишите о том, что доставляет Вам радость. Размышляйте. Советуйте. Дискутируйте!</h3>
        <p>&nbsp;</p>
        <ul>
            <li>Публикуйте свои истории о входе в профессию.</li>
            <li>Делитесь мнением и персональными лайфхаками.</li>
            <li>Предлагайте инструменты, которые облегчают труд.</li>
            <li>Пишите, как развиваетесь и оттачиваете мастерство.</li>
            <li>Как исправляете оплошности и достигаете целей.</li>
            <li>Сообщайте о важных новостях в мире копирайтинга.</li>
            <li>Делитесь переживаниями и блистайте достижениями.</li>
            <li>Размещайте классные кейсы, на которых стоит учиться.</li>
        </ul>
        <p>Все посты и комментарии отображаются в главной ленте и личном профиле. Темы для постов Вы предлагаете сами!</p>
    </div>

    <script>
        $(function () {
            $('#open-what-to-write').click(function () {
                $('#what-to-write').modal();
            });
        })
    </script>


    <style>
        #what-to-write{
            display: none;
            padding: 1rem !important;
        }
        #open-what-to-write{
            cursor: pointer;
        }

    </style>

@endsection
