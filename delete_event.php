<?php
include 'db.php'; // Database connection

if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    // Prepare and execute DELETE query
    $stmt = $conn->prepare("DELETE FROM Events WHERE id = ?");
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        // Redirect back to the event management page
        header("Location: editphotos.php?msg=Event Deleted Successfully");
        exit();
    } else {
        echo "Error deleting event: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
