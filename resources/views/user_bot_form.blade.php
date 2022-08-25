@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <h3>Диалоги</h3>

            @if ($dialogs)
                @foreach ($dialogs[0]->allDialogs->chats as $chat)
                    <p>тип чата - {{ $chat->_ }}</p>
                    <p>id чата - {{ $chat->id }}</p>
                    <p>access_hash чата - {{ $chat->access_hash ?? false }}</p>
                    <p>title чата - {{ $chat->title }}</p>
                    <p>. </p>
                @endforeach
            @endif
        </div>

        <div class="col-md-4">
            <form method="GET" action="{{ route('user.bot.form') }}">
                <label for="method" class="form-label">
                    Выберите метод
                </label>
                <select name="method">
                    <option value="getMessages" checked>getMessages($chat_id, $type, $access_hash = null, $limit = null, $offset_id = null) Получить сообщения
                        чата</option>
                    <option value="getMessagesViews">getMessagesViews($chat_id, $type, $message_id, $access_hash = null)
                        Получить количесво просмотров сообщения</option>
                    <option value="getChannelReactions" checked>getChannelReactions($chat_id, $message_id, $access_hash)
                        Получить реакции сообщения в канале</option>
                    <option value="getReactions" checked>getReactions($chat_id, $messages_id, $limit = null, $offset = null) Получить реакции сообщения в
                        группе</option>
                    <option value="getChatInfo" checked>getChatInfo($chat_id) Получить информацию о группе. В отм числе и её
                        участников</option>
                    <option value="getUsersInChannel" checked>getUsersInChannel($channel_id, $access_hash, $limit = null, $offset = null) Получить
                        пользователей канала</option>
                </select>
                <p>

                    <label for="type" class="form-label mt-2">
                        Выберите тип чата
                    </label><p>
                    <select name="type">
                        <option value="channel" checked>Канал</option>
                        <option value="group">Группа</option>
                    </select><p>
                
                    <label for="chat_id" class="form-label mt-2">
                        Введите id чата
                    </label><p>
                    <input type="text" class="form-control pointer" placeholder="id чата" name="chat_id">
                <p>
                    <label for="access_hash" class="form-label mt-2">
                        Введите access_hash если тип чата - канал
                    </label><p>
                    <input type="text" class="form-control pointer" placeholder="access_hash" name="access_hash">
                <p>
                    <label for="message_id" class="form-label mt-2">
                        Введите message_id если это необходимо методу
                    </label><p>
                    <input type="text" class="form-control pointer" placeholder="message_id" name="message_id">
                <p>
                    <label for="limit" class="form-label mt-2">
                        Введите limit если это необходимо методу
                    </label><p>
                    <input type="text" class="form-control pointer" placeholder="limit" name="limit">
                <p>
                    <label for="offset" class="form-label mt-2">
                        Введите offset или offset_id если это необходимо методу
                    </label><p>
                    <input type="text" class="form-control pointer" placeholder="offset" name="offset">
                <p>
                    <button type="submit" class="btn btn-primary w-30">
                       Отправить
                    </button>
            </form>
            <label for="data_for_form" class="form-label mt-5">
                Данные
            </label><p>
            <textarea name="data_for_form" id="data_for_form" cols="100" rows="10" style="border: 2px; border-style: solid; border-color: rgb(131, 56, 228);">
                {{json_encode($data)}}
            </textarea>
        </div>
    </div>

    


@endsection
