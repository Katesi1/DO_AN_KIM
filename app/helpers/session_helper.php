<?php
// Session Helper
session_start();

// Flash message helper
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if(!empty($name)) {
        if(!empty($message) && empty($_SESSION[$name])) {
            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : $class;
            echo '<div class="'.$class.' alert-dismissible fade show" role="alert">
                    '.$_SESSION[$name].'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }
    }
}

// Check if user is logged in
function isLoggedIn() {
    if(isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

// Check if user is admin
function isAdmin() {
    if(isset($_SESSION['user_role_name']) && $_SESSION['user_role_name'] === 'Admin') {
        return true;
    } else {
        return false;
    }
} 