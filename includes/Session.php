<?php
class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    public static function delete($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    public static function destroy() {
        self::start();
        session_destroy();
    }
    
    public static function isLoggedIn() {
        return self::has('user_id');
    }
    
    public static function isAdmin() {
        return self::has('user_role') && self::get('user_role') === 'admin';
    }
    
    public static function getUserId() {
        return self::get('user_id');
    }
    
    public static function getUser() {
        return [
            'id' => self::get('user_id'),
            'name' => self::get('user_name'),
            'email' => self::get('user_email'),
            'role' => self::get('user_role')
        ];
    }
    
    public static function setUser($user) {
        self::set('user_id', $user['id']);
        self::set('user_name', $user['name']);
        self::set('user_email', $user['email']);
        self::set('user_role', $user['role']);
    }
    
    public static function logout() {
        self::delete('user_id');
        self::delete('user_name');
        self::delete('user_email');
        self::delete('user_role');
    }
    
    public static function setMessage($type, $message) {
        self::set('flash_message', ['type' => $type, 'message' => $message]);
    }
    
    public static function getMessage() {
        $message = self::get('flash_message');
        self::delete('flash_message');
        return $message;
    }
}
?>