<?php
/**
 * Base Controller
 * Loads the models and views
 */
class Controller {
    // Load model
    public function model($model) {
        // Require model file
        require_once '../app/models/' . $model . '.php';

        // Instantiate model
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        // Check for view file
        if(file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }

    // Redirect to page
    public function redirect($page) {
        header('location: ' . URLROOT . '/' . $page);
    }

    /**
     * Set flash message
     * 
     * @param string $name
     * @param string $message
     * @param string $class
     * @return void
     */
    public function setFlash($name, $message, $class = 'alert alert-success') {
        flash($name, $message, $class);
    }

    /**
     * Get flash message
     * 
     * @param string $name
     * @return string|null
     */
    public function getFlash($name) {
        if (isset($_SESSION[$name])) {
            $class = isset($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : 'alert alert-success';
            $message = $_SESSION[$name];
            
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
            
            return [
                'message' => $message,
                'class' => $class
            ];
        }
        return null;
    }

    // Check if logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Get logged in user
    public function getUser() {
        if($this->isLoggedIn()) {
            $userModel = $this->model('User');
            return $userModel->getUserById($_SESSION['user_id']);
        }
        return false;
    }
}
?> 