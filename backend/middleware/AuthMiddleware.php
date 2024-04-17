<?php
class AuthMiddleware {
    public function handle($request, $response) {
        
        $authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
        if (!$authenticated) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }
    }
}
?>
