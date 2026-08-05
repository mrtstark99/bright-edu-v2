<?php
require_once dirname(__DIR__) . '/config/config.php';

// Disable error output for API
ini_set('display_errors', 0);
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

$db = Database::getInstance();

switch ($action) {
    case 'get_feed':
        $page = isset($_POST['page']) ? (int)$_POST['page'] : (isset($_GET['page']) ? (int)$_GET['page'] : 1);
        if ($page < 1) $page = 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $tag = $_POST['tag'] ?? $_GET['tag'] ?? '';
        
        // Count total for pagination
        if (!empty($tag)) {
            $count_stmt = $db->prepare("SELECT COUNT(*) FROM qa_questions WHERE status = 'active' AND tags LIKE :tag_query");
            $count_stmt->execute(['tag_query' => '%' . $tag . '%']);
            $total = (int)$count_stmt->fetchColumn();
            
            $stmt = $db->prepare("SELECT * FROM qa_questions WHERE status = 'active' AND tags LIKE :tag_query ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':tag_query', '%' . $tag . '%', PDO::PARAM_STR);
        } else {
            $count_stmt = $db->prepare("SELECT COUNT(*) FROM qa_questions WHERE status = 'active'");
            $count_stmt->execute();
            $total = (int)$count_stmt->fetchColumn();
            
            $stmt = $db->prepare("SELECT * FROM qa_questions WHERE status = 'active' ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $questions = $stmt->fetchAll();
        
        foreach ($questions as &$q) {
            // Join users to check if answer is by admin
            $ans_stmt = $db->prepare("
                SELECT a.*, u.role as user_role 
                FROM qa_answers a 
                LEFT JOIN users u ON a.user_id = u.id 
                WHERE a.question_id = :qid AND a.status = 'active' 
                ORDER BY a.created_at ASC
            ");
            $ans_stmt->execute(['qid' => $q['id']]);
            $q['answers'] = $ans_stmt->fetchAll();
        }
        
        $total_pages = max(1, ceil($total / $limit));
        
        echo json_encode([
            'status' => 'success', 
            'data' => $questions,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'total_count' => $total
        ]);
        break;
        
    case 'get_question':
        $qid = $_POST['question_id'] ?? $_GET['question_id'] ?? 0;
        if (!$qid) {
            echo json_encode(['status' => 'error', 'message' => 'ID câu hỏi không hợp lệ']);
            break;
        }
        
        $stmt = $db->prepare("SELECT * FROM qa_questions WHERE id = :id AND status = 'active'");
        $stmt->execute(['id' => $qid]);
        $q = $stmt->fetch();
        
        if (!$q) {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy câu hỏi']);
            break;
        }
        
        $ans_stmt = $db->prepare("
            SELECT a.*, u.role as user_role 
            FROM qa_answers a 
            LEFT JOIN users u ON a.user_id = u.id 
            WHERE a.question_id = :qid AND a.status = 'active' 
            ORDER BY a.created_at ASC
        ");
        $ans_stmt->execute(['qid' => $q['id']]);
        $q['answers'] = $ans_stmt->fetchAll();
        
        echo json_encode(['status' => 'success', 'data' => $q]);
        break;
        
    case 'post_question':
        if (!isLoggedIn()) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thực hiện']);
            break;
        }
        if (!isAdmin() && !isEditor()) {
            echo json_encode(['status' => 'error', 'message' => 'Chỉ có Ban quản trị mới được quyền chia sẻ bài viết kiến thức']);
            break;
        }
        $content = $_POST['content'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $user_id = $_SESSION['user_id'];
        $author_name = $_SESSION['user_name'];
        
        if (empty(trim($content))) {
            echo json_encode(['status' => 'error', 'message' => 'Nội dung không được để trống']);
            break;
        }
        
        // Handle image upload
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $upload_res = uploadImage($_FILES['image'], 'qa');
            if ($upload_res['success']) {
                $image_path = $upload_res['filepath'];
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi tải ảnh: ' . $upload_res['message']]);
                break;
            }
        }
        
        $bg_style = $_POST['bg_style'] ?? '';
        
        $stmt = $db->prepare("INSERT INTO qa_questions (user_id, author_name, content, image, tags, bg_style) VALUES (:uid, :author, :content, :image, :tags, :bg_style)");
        $stmt->execute([
            'uid' => $user_id,
            'author' => htmlspecialchars($author_name),
            'content' => htmlspecialchars($content),
            'image' => $image_path,
            'tags' => htmlspecialchars($tags),
            'bg_style' => htmlspecialchars($bg_style)
        ]);
        
        echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
        break;
        
    case 'post_answer':
        if (!isLoggedIn()) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để bình luận']);
            break;
        }
        $question_id = $_POST['question_id'] ?? 0;
        $content = $_POST['content'] ?? '';
        $user_id = $_SESSION['user_id'];
        
        // Define display name
        if (isAdmin() || isEditor()) {
            $author_name = 'Bright Education (Admin)';
        } else {
            $author_name = $_SESSION['user_name'];
        }
        
        if (empty(trim($content)) || !$question_id) {
            echo json_encode(['status' => 'error', 'message' => 'Nội dung trả lời không được để trống']);
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
