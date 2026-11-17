<?php
    // [Real-time] Server-Sent Events controller.
    // The old front-end opened an XMLHttpRequest every 500ms (chat) and every
    // 800ms (chat list) and replaced the whole HTML on every tick. Now the
    // browser keeps ONE persistent HTTP connection per page and the server
    // pushes an event only when the data in SQLite really changed.
    class Stream extends UserHelper
    {
        public static function stream()
        {
            // [Real-time] The stream is a normal PHP request, so the session has
            // to be started here (this controller is not loaded together with
            // the other controllers).
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }

            header('Content-Type: text/event-stream; charset=utf-8');
            header('Cache-Control: no-cache, no-store');
            header('Connection: keep-alive');
            // [Real-time] Tell nginx/the preview proxy not to buffer the stream,
            // otherwise events would arrive in one burst instead of instantly.
            header('X-Accel-Buffering: no');

            if(!isset($_SESSION['unique_id'])){
                echo "event: unauthorized\ndata: login-required\n\n";
                return;
            }

            $me   = (int)$_SESSION['unique_id'];
            $peer = isset($_GET['user_id']) ? (int)preg_replace('/\D/', '', $_GET['user_id']) : 0;

            // [Real-time] Load every class that touches the session BEFORE the
            // lock is released, so no later include can start the session again
            // while the connection stays open. They are required directly
            // because the project autoloader only knows the Routers folder.
            require_once 'app/Controllers/ChatList.class.php';
            require_once 'app/Controllers/ChatRoom.class.php';

            // [Real-time] Release the session file lock. Without this line the
            // open stream would block the same user's send_msg / logout
            // requests until the connection ends.
            session_write_close();

            while(ob_get_level() > 0){
                ob_end_flush();
            }
            @set_time_limit(0);
            ignore_user_abort(false);

            $chatModel = Controller::model('chatRoomModel');

            // [Real-time] Tell the browser to reconnect 2s after this stream
            // closes, so an idle chat still receives new messages.
            echo "retry: 2000\n\n";
            flush();

            $lastListSig = null;
            $lastChatSig = null;
            $lastBeat    = microtime(true);
            // [Real-time] Every connection lives 30 seconds and the browser
            // reconnects right away; this keeps PHP workers from piling up.
            $deadline = microtime(true) + 30;

            while(microtime(true) < $deadline){
                if(connection_aborted()){
                    break;
                }

                // ---------- chat list (sidebar) ----------
                $listSig = $chatModel->listRevision();
                if($listSig !== $lastListSig){
                    $lastListSig = $listSig;
                    self::send('chats', ChatList::chatsHtml($me));
                }

                // ---------- messages of the open conversation ----------
                if($peer > 0){
                    $chatSig = $chatModel->pairRevision($me, $peer);
                    if($chatSig !== $lastChatSig){
                        $lastChatSig = $chatSig;
                        self::send('messages', ChatRoom::msgsHtml($me, $peer));
                    }
                }

                // [Real-time] A comment line every 10s keeps proxies from closing
                // an idle connection.
                if(microtime(true) - $lastBeat >= 10){
                    echo ": heartbeat\n\n";
                    flush();
                    $lastBeat = microtime(true);
                }

                // [Real-time] 400ms is only a database check on the server, the
                // browser sends no request at all during this time.
                usleep(400000);
            }

            echo "event: reconnect\ndata: bye\n\n";
            flush();
        }

        /**
         * [Real-time] Writes one SSE event. Newlines are removed because an
         * empty "data:" line would end the event early, and the generated HTML
         * does not depend on them.
         */
        private static function send($event, $html)
        {
            echo 'event: '.$event."\n";
            echo 'data: '.str_replace(["\r", "\n"], '', $html)."\n\n";
            flush();
        }
    }
