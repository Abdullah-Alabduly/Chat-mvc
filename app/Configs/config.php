<?php
    // [SQLite migration] The MySQL server settings (host/port/dbname/root user)
    // were removed because the project now stores its data in a single local
    // SQLite file. The file and its tables are created automatically on the
    // first request, so no manual database import is required anymore.
    return [
        'driver' => 'sqlite',
        // The database is generated at runtime and is not part of the source.
        'path'   => dirname(__DIR__).'/database/chat.sqlite',
    ];
