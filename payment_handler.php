<?php
include 'db_connect.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $type = $_POST['type']; // 'standard' or 'seasonal'
    $status = "Paid";
    $receipt_id = "RCT-" . time();

    if ($type === "standard") {
        $stmt = $pdo->prepare("
            INSERT INTO Payment (post_id, user_id, method, payment_status, receipt_id, amount_paid, date, due)
            VALUES (:post_id, :user_id, :method, :status, :receipt_id, :amount_paid, :date, :due)
        ");

        $stmt->execute([
            ':post_id' => $_POST['post_id'],
            ':user_id' => $_POST['user_id'],
            ':method' => $_POST['method'],
            ':status' => $status,
            ':receipt_id' => $receipt_id,
            ':amount_paid' => $_POST['amount_paid'],
            ':date' => $_POST['date'],
            ':due' => $_POST['due']
        ]);

        $payment_id = $pdo->lastInsertId();
        echo json_encode([
            "success" => true,
            "type" => "standard",
            "payment_id" => $payment_id,
            "receipt_id" => $receipt_id,
            "payment_status" => $status
        ]);

    } elseif ($type === "seasonal") {
        // First, insert a row in Payment to create payment_id
        $stmt = $pdo->prepare("
            INSERT INTO Payment (post_id, user_id, method, payment_status, receipt_id, amount_paid, date, due)
            VALUES (NULL, NULL, 'Seasonal', :status, :receipt_id, 0, CURDATE(), CURDATE())
        ");
        $stmt->execute([
            ':status' => $status,
            ':receipt_id' => $receipt_id
        ]);

        $payment_id = $pdo->lastInsertId();

        $stmt2 = $pdo->prepare("
            INSERT INTO Seasonal_Payment (payment_id, cost_per_day, start_date, end_date, duration)
            VALUES (:payment_id, :cost_per_day, :start_date, :end_date, :duration)
        ");

        $stmt2->execute([
            ':payment_id' => $payment_id,
            ':cost_per_day' => $_POST['cost_per_day'],
            ':start_date' => $_POST['start_date'],
            ':end_date' => $_POST['end_date'],
            ':duration' => $_POST['duration']
        ]);

        echo json_encode([
            "success" => true,
            "type" => "seasonal",
            "payment_id" => $payment_id,
            "receipt_id" => $receipt_id,
            "payment_status" => $status
        ]);
    } else {
        throw new Exception("Invalid payment type.");
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>
