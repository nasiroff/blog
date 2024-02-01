<?php

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use App\Models\ChatRequest;
use Illuminate\Database\Eloquent\Builder;
use Ratchet\MessageComponentInterface;

use Ratchet\ConnectionInterface;

use App\Models\User;

use App\Models\Chat;


class SocketController extends Controller implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring, $queryarray);
        if (isset($queryarray['token'])) {

            $user = User::query()
                ->where('token', $queryarray['token'])
                ->first(['id']);
            $user->update(['connection_id' => $conn->resourceId, 'user_status' => 'Online']);

            $data['id'] = $user->id;
            $data['status'] = 'Online';
            foreach ($this->clients as $client) {
                if ($client->resourceId != $conn->resourceId) {
                    $client->send(json_encode($data));
                }
            }
        }
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        $data = json_decode($msg);
        if (isset($data->type)) {
            if ($data->type == 'request_load_unconnected_user') {
                $userData = User::query()
                    ->select('id', 'name', 'user_status')
                    ->where('id', '!=', $data->from_user_id)
                    ->orderBy('name', 'ASC')
                    ->get();

                $subData = [];
                foreach ($userData as $row) {
                    $subData[] = [
                        'name' => $row['name'],
                        'id' => $row['id'],
                        'status' => $row['user_status']
                    ];
                }

                $sender = User::query()
                    ->find($data->from_user_id, ['connection_id']);

                $sendData['data'] = $subData;
                $sendData['response_load_unconnected_user'] = true;

                foreach ($this->clients as $client) {
                    if ($client->resourceId == $sender->connection_id) {
                        $client->send(json_encode($sendData));
                    }
                }
            }

            if ($data->type == 'request_search_user') {
                $userData = User::query()
                    ->select('id', 'name', 'user_status')
                    ->where('id', '!=', $data->from_user_id)
                    ->where('name', 'like', '%' . $data->search_query . '%')
                    ->orderBy('name', 'ASC')
                    ->get();

                $subData = [];
                foreach ($userData as $row) {
                    $chatRequest = ChatRequest::query()
                        ->select('id')
                        ->where(function (Builder $query) use ($data, $row) {
                            $query->where('from_user_id', $data->from_user_id)
                                ->where('to_user_id', $row->id);
                        })
                        ->orWhere(function (Builder $query) use ($data, $row) {
                            $query->where('from_user_id', $row->id)
                                ->where('to_user_id', $data->from_user_id);
                        })
                        ->get();

                    if (!$chatRequest->count()) {
                        $subData[] = [
                            'name' => $row['name'],
                            'id' => $row['id'],
                            'status' => $row['user_status']
                        ];
                    }
                }

                $sender = User::query()
                    ->find($data->from_user_id, ['connection_id']);

                $sendData['data'] = $subData;
                $sendData['response_search_user'] = true;

                foreach ($this->clients as $client) {
                    if ($client->resourceId == $sender->connection_id) {
                        $client->send(json_encode($sendData));
                    }
                }
            }

            if ($data->type == 'request_chat_user') {

                ChatRequest::query()->create([
                    'from_user_id' => $data->from_user_id,
                    'to_user_id' => $data->to_user_id,
                    'status' => 'Pending'
                ]);

                $sender = User::query()
                    ->find($data->from_user_id, ['connection_id']);

                $receiver = User::query()
                    ->find($data->to_user_id, ['connection_id']);

                foreach ($this->clients as $client) {
                    if ($client->resourceId == $sender->connection_id) {
                        $sendData['response_from_user_chat_request'] = true;
                        $client->send(json_encode($sendData));
                    }

                    if ($client->resourceId == $receiver->connection_id) {
                        $sendData['user_id'] = $data->to_user_id;
                        $sendData['response_to_user_chat_request'] = true;
                        $client->send(json_encode($sendData));
                    }
                }
            }

            if ($data->type == 'request_load_unread_notification') {

                $notificationData = ChatRequest::query()
                    ->select(['id', 'from_user_id', 'to_user_id', 'status'])
                    ->where('status', '!=', 'Approve')
                    ->where(function (Builder $query) use ($data) {
                        $query->where('from_user_id', $data->user_id)
                            ->orWhere('to_user_id', $data->user_id);
                    })->orderBy('id')->get();

                $subData = [];

                foreach ($notificationData as $row) {

                    if ($row->from_user_id == $data->user_id) {
                        $userId = $row->to_user_id;
                        $notificationType = 'Send Request';
                    } else {
                        $userId = $row->from_user_id;
                        $notificationType = 'Receive Request';
                    }

                    $userData = User::query()
                        ->select('name')
                        ->where('id', $userId)
                        ->first();

                    $subData[] = [
                        'id' => $row->id,
                        'from_user_id' => $row->from_user_id,
                        'to_user_id' => $row->to_user_id,
                        'name' => $userData->name,
                        'notification_type' => $notificationType,
                        'status' => $row->status,
                    ];
                }

                $sender = User::query()
                    ->find($data->user_id, ['connection_id']);

                foreach ($this->clients as $client) {
                    if ($client->resourceId == $sender->connection_id) {
                        $sendData['response_load_notification'] = true;
                        $sendData['data'] = $subData;
                        $client->send(json_encode($sendData));
                    }
                }
            }

            if ($data->type == 'request_process_chat_request') {

                ChatRequest::query()
                    ->where('id', $data->chat_request_id)
                    ->update(['status' => $data->action]);

                $sender = User::query()
                    ->find($data->from_user_id, ['connection_id']);


                $receiver = User::query()
                    ->find($data->from_user_id, ['connection_id']);


                foreach ($this->clients as $client) {
                    $sendData['response_process_chat_request'] = true;

                    if ($client->resourceId == $sender->connection_id) {
                        $sendData['user_id'] = $data->from_user_id;
                    }

                    if ($client->resourceId == $receiver->connection_id) {
                        $sendData['user_id'] = $data->to_user_id;
                    }

                    $client->send(json_encode($sendData));
                }
            }

            if ($data->type == 'request_connected_chat_user') {
                $fistCondition = ['from_user_id' => $data->from_user_id, 'to_user_id' => $data->from_user_id];

                $userIdData = ChatRequest::query()
                    ->select('from_user_id', 'to_user_id')
                    ->orWhere($fistCondition)
                    ->where('status', 'Approve')
                    ->get();

                $subData = [];
                foreach ($userIdData as $userIdRow) {

                    if ($userIdRow->from_user_id != $data->from_user_id) {
                        $userId = $userIdRow->from_user_id;
                    } else {
                        $userId = $userIdRow->to_user_id;
                    }

                    $userData = User::query()
                        ->find($userId, ['id', 'name', 'user_status', 'updated_at']);

                    if (date('Y-m-d') == date('Y-m-d', strtotime($userData->updated_at))) {
                        $lastSeen = 'Last Seen At ' . date('H:i', strtotime($userData->updated_at));
                    } else {
                        $lastSeen = 'Last Seen At ' . date('d/m/Y H:i', strtotime($userData->updated_at));
                    }

                    $subData[] = [
                        'id' => $userData->id,
                        'name' => $userData->name,
                        'user_status' => $userData->user_status,
                        'last_seen' => $lastSeen
                    ];
                }

                $sender = User::query()
                    ->find($data->from_user_id, ['connection_id']);

                foreach ($this->clients as $client) {
                    if ($client->resourceId == $sender->connection_id) {
                        $sendData['response_connected_chat_user'] = true;
                        $sendData['data'] = $subData;
                        $client->send(json_encode($sendData));
                    }
                }
            }

            if ($data->type == 'request_send_message') {

                $chat = new Chat([
                    'from_user_id' => $data->from_user_id,
                    'to_user_id' => $data->to_user_id,
                    'chat_message' => $data->message,
                    'message_status' => 'Not Send',
                ]);
                $chat->save();

                $receiver = User::query()
                    ->find($data->to_user_id, 'connection_id');

                $sender = User::query()
                    ->find($data->from_user_id, 'connection_id');

                foreach ($this->clients as $client) {
                    if ($client->resourceId == $receiver->connection_id || $client->resourceId == $sender->connection_id) {

                        $sendData = [
                            'chat_message_id' => $chat->id,
                            'message' => $data->message,
                            'from_user_id' => $data->from_user_id,
                            'to_user_id' => $data->to_user_id,
                        ];

                        if ($client->resourceId == $receiver->connection_id) {
                            $chat->update(['message_status' => 'Send']);
                            $sendData['message_status'] = 'Send';
                        } else {
                            $sendData['message_status'] = 'Not Send';
                        }
                        $client->send(json_encode($sendData));
                    }
                }
            }

            if ($data->type == 'request_chat_history') {
                $chatData = Chat::query()
                    ->select(['id', 'from_user_id', 'to_user_id', 'chat_message', 'message_status'])
                    ->where(function (Builder $query) use ($data) {
                        $query->where('from_user_id', $data->from_user_id)->where('to_user_id', $data->to_user_id);
                    })
                    ->orWhere(function (Builder $query) use ($data) {
                        $query->where('from_user_id', $data->to_user_id)->where('to_user_id', $data->from_user_id);
                    })->orderBy('id')->get();

                $sendData['chat_history'] = $chatData;

                $receiver = User::query()
                    ->find($data->from_user_id, 'connection_id');

                foreach ($this->clients as $client) {
                    if ($client->resourceId == $receiver->connection_id) {
                        $client->send(json_encode($sendData));
                    }
                }

            }

            if ($data->type == 'update_chat_status') {
                Chat::query()
                    ->where('id', $data->chat_message_id)
                    ->update(['message_status' => $data->chat_message_status]);

                $sender = User::query()
                    ->find($data->from_user_id, 'connection_id');

                foreach ($this->clients as $client) {
                    if ($client->resourceId == $sender->connection_id) {
                        $sendData['update_message_status'] = $data->chat_message_status;
                        $sendData['chat_message_id'] = $data->chat_message_id;
                        $client->send(json_encode($sendData));
                    }
                }
            }

            if ($data->type == 'check_unread_message') {
                $chatData = Chat::query()
                    ->select('id', 'from_user_id', 'to_user_id')
                    ->where('message_status', '!=', 'Read')
                    ->where('from_user_id', $data->to_user_id)->get();

                $sender = User::query()
                    ->find($data->from_user_id, 'connection_id');

                $receiver =
                    User::query()
                        ->find($data->to_user_id, 'connection_id');

                foreach ($chatData as $row) {
                    Chat::query()
                        ->where('id', $row->id)
                        ->update(['message_status' => 'Send']);

                    foreach ($this->clients as $client) {
                        if ($client->resourceId == $sender->connection_id) {
                            $sendData['count_unread_message'] = 1;
                            $sendData['chat_message_id'] = $row->id;
                            $sendData['from_user_id'] = $row->from_user_id;
                        }

                        if ($client->resourceId == $receiver->connection_id) {
                            $sendData['update_message_status'] = 'Send';
                            $sendData['chat_message_id'] = $row->id;
                            $sendData['unread_msg'] = 1;
                            $sendData['from_user_id'] = $row->from_user_id;
                        }
                        $client->send(json_encode($sendData));
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring, $queryarray);

        if (isset($queryarray['token'])) {
            User::query()
                ->where('token', $queryarray['token'])
                ->update(['connection_id' => 0, 'user_status' => 'Offline']);
            $userId = User::query()
                ->select('id', 'updated_at')
                ->where('token', $queryarray['token'])->first();
            $data['id'] = $userId->id;
            $data['status'] = 'Offline';
            $updatedAt = $userId->updated_at;
            if (date('Y-m-d') == date('Y-m-d', strtotime($updatedAt))) {
                $data['last_seen'] = 'Last Seen at ' . date('H:i');
            } else {
                $data['last_seen'] = 'Last Seen at ' . date('d/m/Y H:i');
            }
            foreach ($this->clients as $client) {
                if ($client->resourceId != $conn->resourceId) {
                    $client->send(json_encode($data));
                }
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        dump("An error has occurred: {$e->getMessage()} \n" .
            "Line: {$e->getLine()}");
        $conn->close();
    }
}
