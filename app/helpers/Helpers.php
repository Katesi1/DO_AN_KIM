<?php
/**
 * Helper functions for the application
 */

// Clean input data
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Check if email belongs to the university
function is_university_email($email) {
    return (strpos($email, '@pdu.edu.vn') !== false);
}

// Generate a random token
function generate_token($length = 32) {
    return bin2hex(random_bytes($length));
}

// Format timestamp to readable date
function format_date($timestamp, $format = 'd/m/Y H:i') {
    return date($format, strtotime($timestamp));
}

// Calculate time elapsed (e.g., "2 minutes ago")
function time_elapsed($datetime) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Get weeks from days
    $weeks = floor($diff->d / 7);
    $diff->d -= $weeks * 7;

    $string = array(
        'y' => 'năm',
        'm' => 'tháng',
        'w' => 'tuần',
        'd' => 'ngày',
        'h' => 'giờ',
        'i' => 'phút',
        's' => 'giây',
    );

    foreach ($string as $k => &$v) {
        $value = 0;
        
        if ($k == 'w') {
            $value = $weeks;
        } else {
            $value = $diff->$k;
        }
        
        if ($value) {
            $v = $value . ' ' . $v . ($value > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$string) {
        return 'vừa xong';
    }

    $string = array_slice($string, 0, 1);
    return implode(', ', $string) . ' trước';
}

// Format phone number
function format_phone($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Format as Vietnamese phone number
    if (strlen($phone) == 10) {
        return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
    }
    
    return $phone;
}

// Upload an image
function upload_image($file, $destination_path) {
    // Check if file was uploaded without errors
    if ($file['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        // Validate file type and size
        if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
            // Generate a unique filename
            $filename = time() . '_' . uniqid() . '_' . basename($file['name']);
            $destination = $destination_path . $filename;
            
            // Move the uploaded file
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return $filename;
            }
        }
    }
    
    return false;
}

// Format currency
function format_currency($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

// Get file extension
function get_file_extension($filename) {
    return pathinfo($filename, PATHINFO_EXTENSION);
}

// Generate verification questions
function generate_verification_questions() {
    $questions = [
        'Đặc điểm nhận dạng chính của vật phẩm là gì?',
        'Vật phẩm có dấu hiệu đặc biệt nào không?',
        'Thời gian và địa điểm chính xác bạn đánh mất vật phẩm?',
        'Có thông tin cá nhân nào trên vật phẩm không?',
        'Vật phẩm có giá trị ước tính là bao nhiêu?'
    ];
    
    return $questions;
}

// Calculate trust points
function calculate_trust_points($user_id, $db) {
    // Implementation would depend on business logic
    // Example: +10 for each successful return
    //         +5 for each found item reported
    //         -2 for each false claim
    return 0; // Placeholder
}

// Send email (basic implementation)
function send_email($to, $subject, $message) {
    $headers = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM_ADDRESS . ">\r\n";
    $headers .= "Reply-To: " . MAIL_FROM_ADDRESS . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $message, $headers);
}
?> 