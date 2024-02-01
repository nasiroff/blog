@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-sm-4 col-lg-3">
            <div class="card">
                <div class="card-header"><b>Connected User</b></div>
                <div class="card-body" id="user_list">

                </div>
            </div>
        </div>
        <div class="col-sm-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col col-md-6" id="chat_header"><b>Chat Area</b></div>
                        <div class="col col-md-6" id="close_chat_area"></div>
                    </div>
                </div>
                <div class="card-body" id="chat_area">

                </div>
            </div>
        </div>
        <div class="col-sm-4 col-lg-3">
            <div class="card" style="height:255px; overflow-y: scroll;">
                <div class="card-header">
                    <input type="text" class="form-control" placeholder="Search User..." autocomplete="off"
                           id="search_people" onkeyup="searchUser('{{ Auth::id() }}', this.value);"/>
                </div>
                <div class="card-body">
                    <div id="search_people_area" class="mt-3"></div>
                </div>
            </div>
            <br/>
            <div class="card" style="height:255px; overflow-y: scroll;">
                <div class="card-header"><b>Notification</b></div>
                <div class="card-body">
                    <ul class="list-group" id="notification_area">

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>

        #chat_area {
            min-height: 500px;
            /*overflow-y: scroll*/;
        }

        #chat_history {
            min-height: 500px;
            max-height: 500px;
            overflow-y: scroll;
            margin-bottom: 16px;
            background-color: #ece5dd;
            padding: 16px;
        }

        #user_list {
            min-height: 500px;
            max-height: 500px;
            overflow-y: scroll;
        }
    </style>

@endsection('content')

<script>

    let conn = new WebSocket('ws://127.0.0.1:8098/?token={{ auth()->user()->token }}');

    let from_user_id = "{{ Auth::user()->id }}";

    let to_user_id = "";

    conn.onopen = function (e) {

        console.log("Connection established!");

        loadUnconnectedUser(from_user_id);

        loadUnreadNotification(from_user_id);

        loadConnectedChatUser(from_user_id);

    };

    conn.onmessage = function (e) {

        let data = JSON.parse(e.data);

        {{--if (data.image_link) {--}}
        {{--    document.getElementById('message_area').innerHTML = `<img src="{{ asset('images/`+data.image_link+`') }}" class="img-thumbnail img-fluid" />`;--}}
        {{--}--}}

        if (data.status) {
            let onlineStatusIcon = document.getElementsByClassName('online_status_icon');

            for (let count = 0; count < onlineStatusIcon.length; count++) {
                if (onlineStatusIcon[count].id === 'status_' + data.id) {
                    if (data.status === 'Online') {
                        onlineStatusIcon[count].classList.add('text-success');

                        onlineStatusIcon[count].classList.remove('text-danger');

                        document.getElementById('last_seen_' + data.id + '').innerHTML = 'Online';
                    } else {
                        onlineStatusIcon[count].classList.add('text-danger');

                        onlineStatusIcon[count].classList.remove('text-success');

                        document.getElementById('last_seen_' + data.id + '').innerHTML = data.last_seen;
                    }
                }
            }
        }

        if (data.response_load_unconnected_user || data.response_search_user) {
            let html = '';

            if (data.data.length > 0) {
                html += '<ul class="list-group">';
                for (let count = 0; count < data.data.length; count++) {
                    html += `<li class="list-group-item">
                                <div class="row">
                                    <div class="col col-9">${data.data[count].name}</div>
                                    <div class="col col-3">
                                        <button type="button" name="send_request" class="btn btn-primary btn-sm float-end" onclick="sendRequest(this, ${from_user_id}, ${data.data[count].id})"><i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </li>`;
                }
                html += '</ul>';
            } else {
                html = 'No User Found';
            }
            document.getElementById('search_people_area').innerHTML = html;
        }

        if (data.response_from_user_chat_request) {
            searchUser(from_user_id, document.getElementById('search_people').value);
            loadUnreadNotification(from_user_id);
        }

        if (data.response_to_user_chat_request) {
            loadUnreadNotification(data.user_id);
        }

        if (data.response_load_notification) {
            let html = '';

            for (let count = 0; count < data.data.length; count++) {

                html += `<li class="list-group-item">
                            <div class="row">
                                <div class="col col-8">${data.data[count].name}</div>
                                <div class="col col-4">`;
                if (data.data[count].notification_type === 'Send Request') {
                    if (data.data[count].status === 'Pending') {
                        html += '<button type="button" name="send_request" class="btn btn-warning btn-sm float-end">Request Send</button>';
                    } else {
                        html += '<button type="button" name="send_request" class="btn btn-danger btn-sm float-end">Request Rejected</button>';
                    }
                } else {
                    if (data.data[count].status === 'Pending') {
                        html += '<button type="button" class="btn btn-danger btn-sm float-end" onclick="processChatRequest(' + data.data[count].id + ', ' + data.data[count].from_user_id + ', ' + data.data[count].to_user_id + ', `Reject`)"><i class="fas fa-times"></i></button>&nbsp;';
                        html += '<button type="button" class="btn btn-success btn-sm float-end" onclick="processChatRequest(' + data.data[count].id + ', ' + data.data[count].from_user_id + ', ' + data.data[count].to_user_id + ', `Approve`)"><i class="fas fa-check"></i></button>';
                    } else {
                        html += '<button type="button" name="send_request" class="btn btn-danger btn-sm float-end">Request Rejected</button>';
                    }
                }

                html += `</div>
                    </div>
                </li>`;
            }

            document.getElementById('notification_area').innerHTML = html;
        }

        if (data.response_process_chat_request) {
            loadUnreadNotification(data.user_id);

            loadConnectedChatUser(data.user_id);
        }

        if (data.response_connected_chat_user) {
            let html = '<div class="list-group">';

            if (data.data.length > 0) {
                for (let count = 0; count < data.data.length; count++) {
                    html += `<a href="#" class="list-group-item d-flex justify-content-between align-items-start" onclick="makeChatArea(${data.data[count].id}, '${data.data[count].name}'); loadChatData(${from_user_id}, ${data.data[count].id}); ">
					            <div class="ms-2 me-auto">`;

                    let lastSeen = '';
                    if (data.data[count].user_status === 'Online') {
                        html += '<span class="text-success online_status_icon" id="status_' + data.data[count].id + '"><i class="fas fa-circle"></i></span>';
                        lastSeen = 'Online';
                    } else {
                        html += '<span class="text-danger online_status_icon" id="status_' + data.data[count].id + '"><i class="fas fa-circle"></i></span>';
                        lastSeen = data.data[count].last_seen;
                    }

                    html += `&nbsp; <b>${data.data[count].name}</b>
                                    <div class="text-right"><small class="text-muted last_seen" id="last_seen_${data.data[count].id}">${lastSeen}</small></div>
                                </div>
                                <span class="user_unread_message" data-id="${data.data[count].id}" id="user_unread_message_${data.data[count].id}"></span>
                            </a>`;
                }
            } else {
                html += 'No User Found';
            }

            html += '</div>';

            document.getElementById('user_list').innerHTML = html;

            checkUnreadMessage();
        }

        if (data.message) {
            let html = '';

            if (data.from_user_id === from_user_id) {

                var iconStyle = '';

                if (data.message_status === 'Not Send') {
                    iconStyle = '<span id="chat_status_' + data.chat_message_id + '" class="float-end"><i class="fas fa-check text-muted"></i></span>';
                }
                if (data.message_status === 'Send') {
                    iconStyle = '<span id="chat_status_' + data.chat_message_id + '" class="float-end"><i class="fas fa-check-double text-muted"></i></span>';
                }

                if (data.message_status === 'Read') {
                    iconStyle = '<span class="text-primary float-end" id="chat_status_' + data.chat_message_id + '"><i class="fas fa-check-double"></i></span>';
                }

                html += `<div class="row">
                            <div class="col col-3">&nbsp;</div>
                            <div class="col col-9 alert alert-success text-dark shadow-sm">
                                ${data.message + iconStyle}
                            </div>
                        </div>`;
            } else {
                if (to_user_id !== '') {
                    html += `<div class="row">
                                <div class="col col-9 alert alert-light text-dark shadow-sm">
                                ${data.message}
                                </div>
                            </div>`;

                    updateMessageStatus(data.chat_message_id, from_user_id, to_user_id, 'Read');
                } else {
                    let countUnreadMessageElement = document.getElementById('user_unread_message_' + data.from_user_id + '');
                    if (countUnreadMessageElement) {
                        let countUnreadMessage = countUnreadMessageElement.textContent;
                        if (countUnreadMessage === '') {
                            countUnreadMessage = parseInt(0) + 1;
                        } else {
                            countUnreadMessage = parseInt(countUnreadMessage) + 1;
                        }
                        countUnreadMessageElement.innerHTML = '<span class="badge bg-primary rounded-pill">' + countUnreadMessage + '</span>';

                        updateMessageStatus(data.chat_message_id, data.from_user_id, data.to_user_id, 'Send');
                    }
                }

            }

            if (html !== '') {
                let previousChatElement = document.querySelector('#chat_history');
                let chatHistoryElement = document.querySelector('#chat_history');
                chatHistoryElement.innerHTML = previousChatElement.innerHTML + html;
                scrollTop();
            }

        }

        if (data.chat_history) {
            let html = '';

            for (let count = 0; count < data.chat_history.length; count++) {
                if (data.chat_history[count].from_user_id === from_user_id) {
                    let iconStyle = '';

                    if (data.chat_history[count].message_status === 'Not Send') {
                        iconStyle = '<span id="chat_status_' + data.chat_history[count].id + '" class="float-end"><i class="fas fa-check text-muted"></i></span>';
                    }

                    if (data.chat_history[count].message_status === 'Send') {
                        iconStyle = '<span id="chat_status_' + data.chat_history[count].id + '" class="float-end"><i class="fas fa-check-double text-muted"></i></span>';
                    }

                    if (data.chat_history[count].message_status === 'Read') {
                        iconStyle = '<span class="text-primary float-end" id="chat_status_' + data.chat_history[count].id + '"><i class="fas fa-check-double"></i></span>';
                    }

                    html += `<div class="row">
                                <div class="col col-3">&nbsp;</div>
                                <div class="col col-9 alert alert-success text-dark shadow-sm">
                                ${data.chat_history[count].chat_message + iconStyle}
                                </div>
                            </div>`;

                } else {
                    if (data.chat_history[count].message_status !== 'Read') {
                        updateMessageStatus(data.chat_history[count].id, data.chat_history[count].from_user_id, data.chat_history[count].to_user_id, 'Read');
                    }

                    html += `<div class="row">
                                <div class="col col-9 alert alert-light text-dark shadow-sm">
                                ${data.chat_history[count].chat_message}
                                </div>
                            </div>`;

                    let countUnreadMessageElement = document.getElementById('user_unread_message_' + data.chat_history[count].from_user_id + '');

                    if (countUnreadMessageElement) {
                        countUnreadMessageElement.innerHTML = '';
                    }
                }
            }

            document.querySelector('#chat_history').innerHTML = html;

            scrollTop();
        }

        if (data.update_message_status) {
            let chatStatusElement = document.querySelector('#chat_status_' + data.chat_message_id + '');

            if (chatStatusElement) {
                if (data.update_message_status === 'Read') {
                    chatStatusElement.innerHTML = '<i class="fas fa-check-double text-primary"></i>';
                }
                if (data.update_message_status === 'Send') {
                    chatStatusElement.innerHTML = '<i class="fas fa-check-double text-muted"></i>';
                }
            }

            if (data.unread_msg) {
                let countUnreadMessageElement = document.getElementById('user_unread_message_' + data.from_user_id + '');

                if (countUnreadMessageElement) {
                    let countUnreadMessage = countUnreadMessageElement.textContent;

                    if (countUnreadMessage === '') {
                        countUnreadMessage = parseInt(0) + 1;
                    } else {
                        countUnreadMessage = parseInt(countUnreadMessage) + 1;
                    }
                    countUnreadMessageElement.innerHTML = '<span class="badge bg-danger rounded-pill">' + countUnreadMessage + '</span>';
                }
            }
        }
    };

    function scrollTop() {
        document.querySelector('#chat_history').scrollTop = document.querySelector('#chat_history').scrollHeight;
    }

    function loadUnconnectedUser(from_user_id) {
        let data = {
            from_user_id,
            type: 'request_load_unconnected_user'
        };
        conn.send(JSON.stringify(data));
    }

    function searchUser(from_user_id, search_query) {
        if (search_query.length > 0) {
            var data = {
                from_user_id,
                search_query,
                type: 'request_search_user'
            };

            conn.send(JSON.stringify(data));
        } else {
            loadUnconnectedUser(from_user_id);
        }
    }

    function sendRequest(element, from_user_id, to_user_id) {
        let data = {
            from_user_id,
            to_user_id,
            type: 'request_chat_user'
        };

        element.disabled = true;
        conn.send(JSON.stringify(data));
    }

    function loadUnreadNotification(user_id) {
        let data = {
            user_id,
            type: 'request_load_unread_notification'
        };

        conn.send(JSON.stringify(data));
    }

    function processChatRequest(chat_request_id, from_user_id, to_user_id, action) {
        var data = {
            chat_request_id,
            from_user_id,
            to_user_id,
            action,
            type: 'request_process_chat_request'
        };

        conn.send(JSON.stringify(data));
    }

    function loadConnectedChatUser(from_user_id) {
        var data = {
            from_user_id: from_user_id,
            type: 'request_connected_chat_user'
        };

        conn.send(JSON.stringify(data));
    }

    function makeChatArea(userId, toUserName) {
        document.getElementById('chat_area').innerHTML = `<div id="chat_history"></div>
                                                            <div class="input-group mb-3">
                                                                <div id="message_area" class="form-control" contenteditable style="min-height:125px; border:1px solid #ccc; border-radius:5px;"></div>
                                                                <label class="btn btn-warning" style="line-height:125px;">
                                                                    <i class="fas fa-upload"></i> <input type="file" id="browse_image" onchange="uploadImage()" hidden />
                                                                </label>
                                                                <button type="button" class="btn btn-success" id="send_button" onclick="sendChatMessage()"><i class="fas fa-paper-plane"></i></button>
                                                            </div>`;


        document.getElementById('chat_header').innerHTML = 'Chat with <b>' + toUserName + '</b>';
        document.getElementById('close_chat_area').innerHTML = '<button type="button" id="close_chat" class="btn btn-danger btn-sm float-end" onclick="closeChat();"><i class="fas fa-times"></i></button>';
        to_user_id = userId;
    }

    function closeChat() {
        document.getElementById('chat_header').innerHTML = 'Chat Area';
        document.getElementById('close_chat_area').innerHTML = '';
        document.getElementById('chat_area').innerHTML = '';
        to_user_id = '';
    }

    function sendChatMessage() {
        document.querySelector('#send_button').disabled = true;

        var message = document.getElementById('message_area').innerHTML.trim();

        var data = {
            message,
            from_user_id,
            to_user_id,
            type: 'request_send_message'
        };

        conn.send(JSON.stringify(data));
        document.querySelector('#message_area').innerHTML = '';
        document.querySelector('#send_button').disabled = false;
    }

    function loadChatData(from_user_id, to_user_id) {
        var data = {
            from_user_id,
            to_user_id,
            type: 'request_chat_history'};
        conn.send(JSON.stringify(data));
    }

    function updateMessageStatus(chat_message_id, from_user_id, to_user_id, chat_message_status) {
        var data = {
            chat_message_id,
            from_user_id,
            to_user_id,
            chat_message_status,
            type: 'update_chat_status'
        };

        conn.send(JSON.stringify(data));
    }

    function checkUnreadMessage() {
        var unreadElement = document.getElementsByClassName('user_unread_message');

        for (var count = 0; count < unreadElement.length; count++) {
            var temp_user_id = unreadElement[count].dataset.id;

            var data = {
                from_user_id,
                to_user_id,
                type: 'check_unread_message'
            };

            conn.send(JSON.stringify(data));
        }
    }

    function uploadImage() {
        let fileElement = document.getElementById('browse_image').files[0];
        let fileName = fileElement.name;
        let fileExtension = fileName.split('.').pop().toLowerCase();
        let allowedExtension = ['png', 'jpg'];

        if (allowedExtension.indexOf(fileExtension) === -1) {
            alert("Invalid Image File");
            return false;
        }

        let fileReader = new FileReader();
        let fileRawData = new ArrayBuffer();

        fileReader.loadend = function () {
        }
        fileReader.onload = function (event) {
            fileRawData = event.target.result;
            conn.send(fileRawData);
        }
        fileReader.readAsArrayBuffer(fileElement);
    }

</script>
