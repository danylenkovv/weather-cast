<?php

namespace app\core;

class Session
{
    /**
     * Starts a new session or resumes an existing session.
     *
     * @return void
     */
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Sets a session variable.
     *
     * @param string $key The key for the session variable.
     * @param mixed $value The value to be stored in the session.
     * @return void
     */
    public static function set(string $key, ?string $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieves a session variable.
     *
     * @param string $key The key of the session variable to retrieve.
     * @return mixed|null The value of the session variable, or null if not set.
     */
    public static function get(string $key): string|null
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Removes a session variable.
     *
     * @param string $key The key of the session variable to remove.
     * @return void
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroys the current session and all session data.
     *
     * @return void
     */
    public static function destroy(): void
    {
        session_destroy();
    }
}
