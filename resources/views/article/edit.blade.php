@php
    $user=\Illuminate\Support\Facades\Auth::user();

    $allTags = App\Tag::orderBy('name')->get();
    $selected_tags_array = $article->tags()->pluck('id')->toArray();
@endphp

@extends('layouts.app')

@section('head')

    <script src="{{ asset('js/lib/chosen.jquery.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/lib/chosen.min.css') }}" />

    <title>{{$pageTitle}}</title>

    <meta name="article-id" content="{{$article->id}}"/>
@endsection

@section('content')

    <div class="container col-md-9">

        <h1>{{$pageTitle}}</h1>

        <form method="POST" action="{{route('article.post', ['id'=>$article->id])}}" id="article-editor-form">
            @csrf

            <div class="form-group">
                <label for="title">Заголовок:</label>
                <input type="text" name="title" value="{{$article->title}}" class="form-control" AUTOCOMPLETE="off"
                       required/>
                <small class="form-text text-muted">Заголовок должен быть наполнен смыслом, чтобы можно было понять, о
                    чем будет публикация.
                </small>
            </div>

            <div class="form-group">
                <label for="tags">Метки:</label>
{{--                <input type="text" name="tags" class="form-control" AUTOCOMPLETE="off" id="tags-input" />--}}
                <select name="tags[]" id="tags-input" class="form-control"  multiple="multiple" data-placeholder="Отметьте теги для статьи">
                    @foreach($allTags as $tag)
                        <option value="{{$tag->id}}" @if(array_search($tag->id,$selected_tags_array) !== false) selected @endif>{{$tag->name}}</option>
                    @endforeach
                </select>
            </div>

            @if($user->moderator)

                <div class="moderator"><span class="red-text">Модератор</span>

                    <div class="form-group">
                        <label for="slug">URL:</label>
                        <button @if($article->public()) disabled @endif title="сгенерировать URL из заголовка" onClick="setSlug(); return false;"><span class="fas fa-arrow-circle-down"></span></button>
                        <input type="text" name="slug" value="{{$article->slug}}" class="form-control"
                               AUTOCOMPLETE="off" @if($article->public()) disabled @else required @endif />
                    </div>

                    <div class="form-group">
                        <label for="keywords">Keywords:</label>
                        <input type="text" name="keywords" value="{{$article->meta_keywords}}" class="form-control"
                               AUTOCOMPLETE="off"/>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <input type="text" name="description" value="{{$article->meta_description}}" class="form-control"
                               AUTOCOMPLETE="off"/>
                    </div>

                    <div class="form-group">
                        <label for="feed">Отображать в ленте:</label>
                        <input type="checkbox" name="feed" @if($article->feed) checked @endif />
                    </div>

                </div>

            @endif

            <div class="form-group">
                <label for="annotation">Аннотация</label>
                <textarea class="htmleditor" name="annotation"></textarea>
            </div>

            <div id="cut">
                <img src="/images/Scissors-128.png" width="20" height="20"/>
                <div id="dashed-border"></div>
                <br>
            </div>

            <div class="form-group">
                <label for="content">Основной текст</label>
                <textarea class="htmleditor" name="article-content"></textarea>
            </div>

            <button id="publish-article" type="submit" name="finished" value="1" class="btn btn-primary">Опубликовать</button>
            @if(!$article->public())
                <button type="submit" name="finished" value="0" name="save-draft" class="btn btn-secondary">Сохранить в
                    черновики
                </button>
            @endif
            <button type="button" id="btn-preview" class="btn float-right" data-toggle="modal"
                    data-target="#preview-content">Предпросмотр
            </button>

        </form>

    </div>

    <div class="modal fade" id="preview-content" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content article-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLongTitle">Предпросмотр</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body article-text">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        var submit = false;

        $(document).ready(function () {

            $('#tags-input').chosen();

            function fillEditorContent(e) {
                if ($(this).attr('id') == 'article-content')
                    this.setContent(`{!! $article->content !!}`);

                if ($(this).attr('id') == 'annotation')
                    this.setContent(`{!! $article->annotation !!}`);
            }

            tinymce.init({
                selector: '.htmleditor',
                language: 'ru',
                language_url: '{{asset('js/lib/lang/tinymce-ru.js')}}',
                plugins: "image, link, fullscreen, emoticons, media, lists, charmap, paste",
                paste_as_text: true,
                toolbar: 'undo redo | styleselect | bold italic removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | indent outdent | charmap emoticons link image media | fullscreen',
                menubar: false,
                height: 500,
                media_live_embeds: true,
                file_picker_types: 'file image media',
                images_upload_url: '/upload',
                automatic_uploads: true,
                images_upload_handler: uploadImage,
                setup: function(editor) {
                    editor.on('init', fillEditorContent);
                },
                content_style: 'h1,h2,h3,h4,h5,h6,a{color:#C45911;}'
                +'blockquote, q {\n' +
                    '    position: relative !important;\n' +
                    '    padding: 12px 12px 12px 60px !important;\n' +
                    '    margin: 10px !important;\n' +
                    '    color: #041e42 !important;\n' +
                    '    font-style:italic !important;\n' +
                    '    font-size: 1rem !important;\n' +
                    '    background: #f8f9fa !important;\n' +
                    '    border-left: 3px solid #041e42 !important;\n' +
                    '    display: block !important;\n' +
                    '    quotes: none !important;\n' +
                    '}\n' +
                    'blockquote:after {\n' +
                    '    content: \'”\';\n' +
                    '    position: absolute !important;\n' +
                    '    top: 15px !important;\n' +
                    '    left: 10px !important;\n' +
                    '    font-size: 3em !important;\n' +
                    '    line-height: 1 !important;\n' +
                    '}' +
                    'html, body{font-family: "Roboto", sans-serif !important;}',
                style_formats: [
                    { title: 'Headings', items: [
                            { title: 'Heading 1', format: 'h1' },
                            { title: 'Heading 2', format: 'h2' },
                            { title: 'Heading 3', format: 'h3' },
                            { title: 'Heading 4', format: 'h4' },
                            { title: 'Heading 5', format: 'h5' },
                            { title: 'Heading 6', format: 'h6' }
                        ]},
                    { title: 'Inline', items: [
                            { title: 'Bold', format: 'bold' },
                            { title: 'Italic', format: 'italic' },
                            { title: 'Underline', format: 'underline' },
                            { title: 'Strikethrough', format: 'strikethrough' },
                            { title: 'Superscript', format: 'superscript' },
                            { title: 'Subscript', format: 'subscript' }
                        ]},
                    { title: 'Blocks', items: [
                            { title: 'Blockquote', format: 'blockquote'},
                            { title: 'Paragraph', format: 'p' }
                        ]},
                ],
                contextmenu: false,
                browser_spellcheck: true,
		relative_urls: false,
            });

            // top: 43px;
            // left: 0px;

            $('#btn-preview').click(function (e) {
                e.preventDefault();
                tinyMCE.triggerSave();
                $('.modal-title').html('Предпросмотр: ' + $('[name="title"]').val());
                $('.modal-body').html(
                    $('[name="annotation"]').val()
                    + '<br />' +
                    $('[name="article-content"]').val()
                );
            });

            // блокируем Tab т.к. пользователь нечаянно нажимает опубликовать статью, пока набирает
            $(document).keydown(function (e) {
                if (e.keyCode == 9) {
                    e.preventDefault();
                }
            });

            @if ($article->authorId == \Illuminate\Support\Facades\Auth::user()->id)
            // каждую минуту сохраняем данные формы на всякий случай
            window.setInterval(ajax_save, 60000);
            @endif

            $('#publish-article').click(function(event){
                 if( !confirm('Вы уверены что хотите опубликовать статью?') )
                    event.preventDefault();
            });

        });

        var setSlug = function(){

            title = $('input[name=title]').val();
            if (title == '') return;
            $.get('/api/generateSlug/' + title, function( data ) {
                $('input[name=slug]').val(data);
            });
        }

        $('button[type=submit]').on('click', function () {
            submit = true;
        });

        @if(!(env('APP_DEBUG')))
        $(window).on('beforeunload', function () {
            if (!submit) return confirm();
        });

        @endif

        function ajax_save() {
            tinyMCE.triggerSave();
            var form = $('#article-editor-form');
            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                async: true,
            });
        }

        var article_id = function() {
            return {{$article->id}};
        }

    </script>

    <style>
        #dashed-border {
            border-bottom: 1px black dashed;
            margin-top: -10px;
            margin-left: 15px;
        }
        .modal {
            width: 90%;
            z-index: 9999;
            margin-left: 5%;
            max-width: initial;
        }
        .modal-dialog {
            max-width: 90%;
        }
        .tox-collection__item-label blockquote:after{
            top: 10px !important;
            left: 0px !important;
        }
        .moderator{
            border: red 1px solid;
            padding: 10px;
            margin: 20px 0;
        }
        .tox-fullscreen {
            z-index: 2147483648 !important;
        }
    </style>

@endsection
