<?php

if (!function_exists('auth_user')) {
    /**
     * Get authenticated user from session
     */
    function auth_user()
    {
        return session('user');
    }
}

if (!function_exists('is_authenticated')) {
    /**
     * Check if user is authenticated
     */
    function is_authenticated()
    {
        return session()->has('user');
    }
}

if (!function_exists('user_role')) {
    /**
     * Get user role
     */
    function user_role()
    {
        return session('user.role');
    }
}

if (!function_exists('is_role')) {
    /**
     * Check if user has specific role
     */
    function is_role($role)
    {
        return session('user.role') === $role;
    }
}
