# Chat MVC — Real-Time PHP Chat

A small chat application I built to practise the MVC pattern in **plain PHP** (no framework), with a
**SQLite** database and **real-time message delivery** instead of polling.

I wrote this README myself, together with the code, so it documents the project exactly as I built and
maintain it.

**Author:** Abdullah Alabduly — [@Abdullah-Alabduly](https://github.com/Abdullah-Alabduly)

---

## 1. What the app does

- Register an account with a first name, last name, email, password and profile picture.
- Log in with email and password and see the list of all users.
- Search the user list by first or last name.
- Open a conversation and exchange messages.
- See who is online (🟢) and the last message of every conversation in the sidebar.
- Messages, online status and the chat list update **instantly**, without refreshing the page.

Demo accounts that exist in the current database file (created while testing):

| Email           | Password |
| --------------- | -------- |
| `ali@test.com`  | `123456` |
| `sara@test.com` | `abcdef` |

## 2. Tech stack

| Layer    | Technology                                                   |
| -------- | ------------------------------------------------------------ |
| Backend  | PHP 8.4 (works on PHP 8.0+) — my own MVC core, no framework   |
| Database | SQLite 3 through PDO (single file, no server to install)      |
| Real-time| Server-Sent Events (`EventSource`), server-push               |
| Front-end| Vanilla JavaScript, HTML, CSS                                 |

## 3. Project structure

```
index.php                     entry point (delegates to public/index.php)
router.php                    router for the PHP built-in server (dev only)
.htaccess                     rewrite rules for Apache
app/
  loader.php                  bootstraps config, autoloader and the DB connection
  Configs/config.php          application config (SQLite file path)
  Routers/
    Router.class.php          maps a URL to a controller + method
    Controller.class.php      loads a model / renders a view
    Database.class.php        PDO wrapper (query, bind, fetch, rowCount)
  Controllers/
    Page.class.php            home
    User.class.php            register / login / logout
    ChatList.class.php        profile page, user list, search
    ChatRoom.class.php        one conversation, sending messages
    Stream.class.php          the real-time (SSE) endpoint
  Models/
    UserModel.class.php       users table
    ChatListModel.class.php   user list + last message
    chatRoomModel.class.php   messages table + change fingerprints
  Views/
    header.php, login.view.php, register.view.php,
    chatlist.view.php, chatroom.view.php, css/, Js/
  helpers/UserHelper.class.php  input filtering, file upload
  images/                     uploaded profile pictures
  database/chat.sqlite        the SQLite database (created automatically)
public/
  index.php                   front controller
  .htaccess                   sends every request to index.php
```

## 4. Requirements

- PHP 8.0 or newer with the **pdo_sqlite** extension.
- Apache + `mod_rewrite` (XAMPP / WAMP / Laragon) **or** the PHP built-in server.

## 5. Running the project

### Option A — Apache (XAMPP / WAMP)

1. Put the folder inside the web root (`htdocs`).
2. Set `RewriteBase` in `public/.htaccess` to the real folder name, for example
   `RewriteBase Chat-mvc/public`. I had it as `Chat_Me/public`, so change it if your folder is named
   differently.
3. Open `http://localhost/Chat-mvc/`.
4. Give `app/images/` and `app/database/` write permission if your OS asks for it.

### Option B — PHP built-in server (what I use for quick previews)

```bash
# from the project root
PHP_CLI_SERVER_WORKERS=4 php -S 0.0.0.0:8080 router.php
```

Then open `http://localhost:8080`.

`router.php` exists because the built-in server ignores `.htaccess`: it serves real files (CSS, JS,
images) directly and sends every other request to `public/index.php`, exactly like Apache does.

> `PHP_CLI_SERVER_WORKERS` is important: a real-time connection stays open for a while, so PHP needs
> more than one worker to keep answering the other requests of the same user.

## 6. Database (SQLite)

The whole database is one file: `app/database/chat.sqlite`.

I moved the project from MySQL to SQLite because it needed no server, no import step and no
credentials — the first request creates everything. `app/Routers/Database.class.php` creates the file
and the tables if they do not exist yet:

```sql
users (
    unique_id INTEGER PRIMARY KEY,   -- random id, also used as the session id
    fname     TEXT NOT NULL,
    lname     TEXT NOT NULL,
    email     TEXT NOT NULL UNIQUE,
    password  TEXT NOT NULL,
    img       TEXT,                  -- file name inside app/images/
    status    INTEGER NOT NULL DEFAULT 0   -- 1 = online
)

messages (
    msg_id          INTEGER PRIMARY KEY AUTOINCREMENT,
    msg_sender_id   INTEGER NOT NULL,
    msg_resciver_id INTEGER NOT NULL,
    msg             TEXT    NOT NULL
)
```

The connection also enables:

- `PRAGMA journal_mode = WAL` — readers (the real-time stream) do not block a writer (a new message).
- `PRAGMA busy_timeout = 5000` — a request waits for a lock instead of failing with
  "database is locked" when two users write at the same moment.

To start from an empty database, just delete `app/database/chat.sqlite` (and the `-wal` / `-shm`
files next to it); it is re-created on the next request.

## 7. The real-time layer

The first version of the front-end used AJAX polling: `XMLHttpRequest` every 500 ms for the open
conversation and every 800 ms for the chat list. That is why I replaced it with **Server-Sent Events**:

```
browser  ──GET /stream?user_id=…  (one long-lived connection)──►  Stream::stream()
         ◄── event: chats     (chat list HTML)  ── only when it changed
         ◄── event: messages  (conversation HTML)
         ◄── : heartbeat      (every 10s, keeps proxies happy)
```

How it works:

1. `app/Views/Js/chat.js` and `chatlist.js` open one `EventSource` instead of a `setInterval` timer.
2. `Stream::stream()` compares two cheap fingerprints of the database (`listRevision()` and
   `pairRevision()` in `chatRoomModel`) and only renders + pushes HTML when something really changed.
3. Sending a message is a normal `POST /send_msg` with `fetch()`. The new message reaches both
   browsers through the stream, so the sender does not re-render anything locally.
4. Each connection lives 30 seconds, then the browser reconnects by itself (`retry: 2000`). That keeps
   PHP workers from piling up on shared hosting.
5. `session_write_close()` is called inside the stream: otherwise the open connection would keep the
   PHP session file locked and block the same user's send/logout requests.

## 8. Routes

| URL                     | Controller + method        | Purpose                          |
| ----------------------- | -------------------------- | -------------------------------- |
| `/`                     | `Page::index`              | home fallback                    |
| `/login`                | `User::login`              | login form + login POST          |
| `/register`             | `User::register`           | signup form + signup POST        |
| `/logout`               | `User::logout`             | ends the session, redirects      |
| `/profile`              | `ChatList::profile`        | chat list page                   |
| `/chatlist`             | `ChatList::chatlist`       | chat list HTML (sidebar)         |
| `/search`               | `ChatList::search`         | search users by name             |
| `/chat?user_id=…`       | `ChatRoom::chat`           | open a conversation              |
| `/send_msg`             | `ChatRoom::send_msg`       | store a new message              |
| `/getMsgs`              | `ChatRoom::getMsgs`        | conversation HTML (one-shot)     |
| `/stream?user_id=…`     | `Stream::stream`           | real-time channel (SSE)          |

## 9. Notes and known limitations

These are the things I know are still open in the project:

- Passwords are stored as plain text; the login query compares the raw password. Hashing with
  `password_hash()` / `password_verify()` is the next thing I want to add.
- No CSRF token on the forms, and no limit on the uploaded image size.
- The stream renders the full conversation HTML on every change. That is fine for a chat of this
  size; for very long conversations I would send only the new message.
- `app/images/` is inside the web root, so any uploaded file is publicly reachable by URL.

## 10. Author

Written by **Abdullah Alabduly** — GitHub: [@Abdullah-Alabduly](https://github.com/Abdullah-Alabduly).

I wrote this README to document my own project: the MVC structure, the SQLite storage and the
real-time layer described above are the work I did on this codebase.
