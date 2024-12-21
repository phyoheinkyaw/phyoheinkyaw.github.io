<?php
require_once 'include/db_connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$isp_id = $input['isp_id'] ?? null;

$response = ['success' => false, 'message' => '', 'data' => null];

if ($isp_id) {
    // Fetch ISP details
    $sql = "SELECT * FROM isp WHERE isp_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $isp_id);
    $stmt->execute();
    $isp_result = $stmt->get_result();

    if ($isp_result->num_rows > 0) {
        $isp = $isp_result->fetch_assoc();

        // Fetch ISP promotions
        $sql_promotions = "SELECT promotion_text FROM isp_promotion WHERE isp_id = ?";
        $stmt_promotions = $conn->prepare($sql_promotions);
        $stmt_promotions->bind_param("i", $isp_id);
        $stmt_promotions->execute();
        $promotions_result = $stmt_promotions->get_result();

        $promotions = [];
        while ($row = $promotions_result->fetch_assoc()) {
            $promotions[] = $row['promotion_text'];
        }

        // Fetch subscription plans
        $sql_plans = "SELECT plan_name, plan_speed, plan_price_per_month, plan_features FROM subscription_plan WHERE isp_id = ?";
        $stmt_plans = $conn->prepare($sql_plans);
        $stmt_plans->bind_param("i", $isp_id);
        $stmt_plans->execute();
        $plans_result = $stmt_plans->get_result();

        $plans = [];
        while ($row = $plans_result->fetch_assoc()) {
            $plans[] = $row;
        }

        // Combine ISP details with promotions and plans
        $isp['promotions'] = $promotions;
        $isp['plans'] = $plans;

        $response['success'] = true;
        $response['data'] = $isp;
    } else {
        $response['message'] = 'ISP not found.';
    }
} else {
    $response['message'] = 'Invalid ISP ID.';
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>