<?php
require_once dirname(__DIR__) . '/config/config.php';

// Disable error output for API
ini_set('display_errors', 0);
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

$db = Database::getInstance();

switch ($action) {
    case 'get_feed':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $stmt = $db->prepare("SELECT * FROM qa_questions WHERE status = 'active' ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $questions = $stmt->fetchAll();
        
        foreach ($questions as &$q) {
            $ans_stmt = $db->prepare("SELECT * FROM qa_answers WHERE question_id = :qid AND status = 'active' ORDER BY created_at ASC");
            $ans_stmt->execute(['qid' => $q['id']]);
            $q['answers'] = $ans_stmt->fetchAll();
        }
        
        echo json_encode(['status' => 'success', 'data' => $questions]);
        break;
        
    case 'post_question':
        if (!isLoggedIn()) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để đặt câu hỏi']);
            break;
        }
        $content = $_POST['content'] ?? '';
        $user_id = $_SESSION['user_id'];
        $author_name = $_SESSION['user_name'];
        
        if (empty(trim($content))) {
            echo json_encode(['status' => 'error', 'message' => 'Nội dung không được để trống']);
            break;
        }
        
        $stmt = $db->prepare("INSERT INTO qa_questions (user_id, author_name, content) VALUES (:uid, :author, :content)");
        $stmt->execute([
            'uid' => $user_id,
            'author' => htmlspecialchars($author_name),
            'content' => htmlspecialchars($content)
        ]);
        
        echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
        break;
        
    case 'post_answer':
        if (!isAdmin()) {
            echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền trả lời câu hỏi']);
            break;
        }
        $question_id = $_POST['question_id'] ?? 0;
        $content = $_POST['content'] ?? '';
        $user_id = $_SESSION['user_id'];
        // Use a generic name for admin replies or their actual name
        $author_name = 'Bright Education (Admin)'; 
        
        if (empty(trim($content)) || !$question_id) {
            echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
            break;
        }
        
        $stmt = $db->prepare("INSERT INTO qa_answers (question_id, user_id, author_name, content) VALUES (:qid, :uid, :author, :content)");
        $stmt->execute([
            'qid' => $question_id,
            'uid' => $user_id,
            'author' => htmlspecialchars($author_name),
            'content' => htmlspecialchars($content)
        ]);
        
        echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
        break;
        
    case 'like_question':
        if (!isLoggedIn()) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thích']);
            break;
        }
        $question_id = $_POST['question_id'] ?? 0;
        $stmt = $db->prepare("UPDATE qa_questions SET likes_count = likes_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $question_id]);
        
        $stmt2 = $db->prepare("SELECT likes_count FROM qa_questions WHERE id = :id");
        $stmt2->execute(['id' => $question_id]);
        $new_likes = $stmt2->fetchColumn();
        
        echo json_encode(['status' => 'success', 'likes' => $new_likes]);
        break;
        
    case 'like_answer':
        if (!isLoggedIn()) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thích']);
            break;
        }
        $answer_id = $_POST['answer_id'] ?? 0;
        $stmt = $db->prepare("UPDATE qa_answers SET likes_count = likes_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $answer_id]);
        
        $stmt2 = $db->prepare("SELECT likes_count FROM qa_answers WHERE id = :id");
        $stmt2->execute(['id' => $answer_id]);
        $new_likes = $stmt2->fetchColumn();
        
        echo json_encode(['status' => 'success', 'likes' => $new_likes]);
        break;
        
    default:
        echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
}
