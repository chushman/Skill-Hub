<?php
function log_activity($conn, $user_id, $activity_type, $description) {
    try {
        // Check if columns exist
        $columns = $conn->query("SHOW COLUMNS FROM user_activity LIKE 'activity_type'");
        if ($columns->num_rows == 0) {
            $conn->query("ALTER TABLE user_activity 
                         ADD COLUMN activity_type VARCHAR(50) NOT NULL AFTER user_id");
        }

        $stmt = $conn->prepare("INSERT INTO user_activity 
                               (user_id, activity_type, description) 
                               VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $activity_type, $description);
        
        if (!$stmt->execute()) {
            error_log("Activity log failed: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Activity logger error: " . $e->getMessage());
    }
}
?>