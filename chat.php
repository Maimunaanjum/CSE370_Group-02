<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Get receiver_id from the URL or set to null
$receiver_id = isset($_GET['receiver_id']) ? (int)$_GET['receiver_id'] : null;

// If receiver_id is not provided or is invalid, redirect to user list
if ($receiver_id <= 0) {
    header('Location: user_list.php');
    exit;
}

// Check if the receiver exists in the database
$stmt = $conn->prepare("SELECT user_id, name FROM user WHERE user_id = ?");
$stmt->bind_param("i", $receiver_id);
$stmt->execute();
$stmt->store_result();

// If receiver doesn't exist, redirect to user list
if ($stmt->num_rows == 0) {
    header('Location: user_list.php');
    exit;
}

// Fetch messages
function get_messages($sender_id, $receiver_id) {
    global $conn;
    $query = "SELECT * FROM Chat WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY time_stamp ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $messages;
}

$messages = get_messages($_SESSION['user_id'], $receiver_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat with User</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        h1 { text-align: center; }
        .chat-box { max-width: 600px; margin: 0 auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .message { padding: 10px; background: #f9f9f9; border-radius: 5px; margin: 8px 0; }
        .message.sent { background: #e1f5fe; text-align: right; }
        .message.received { background: #fff; text-align: left; }
        input[type="text"] { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Chat with <?php echo htmlspecialchars($receiver_id); ?></h1>

    <div class="chat-box">
        <?php
        if ($messages):
            foreach ($messages as $msg):
                $class = ($msg['sender_id'] == $_SESSION['user_id']) ? 'sent' : 'received';
                echo "<div class='message $class'>
                        <strong>" . ($msg['sender_id'] == $_SESSION['user_id'] ? 'You' : 'Other User') . ":</strong><br>
                        " . htmlspecialchars($msg['chat']) . "<br><small>" . $msg['time_stamp'] . "</small>
                      </div>";
            endforeach;
        else:
            echo "<p>No messages yet. Start the conversation!</p>";
        endif;
        ?>
    </div>

    <form action="send_message.php" method="POST">
        <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
        <input type="text" name="message" placeholder="Type a message..." required>
        <input type="submit" value="Send">
    </form>
</body>
</html>
