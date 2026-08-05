<?php
/**
 * Q&A Page - Threads Style
 */
$page_title = 'Hỏi đáp - Cộng đồng Bright Education';
require_once 'includes/header.php';

$is_logged_in = isLoggedIn();
$current_user_name = $is_logged_in ? $_SESSION['user_name'] ?? 'Thành viên' : '';
$is_admin = $is_logged_in && (isAdmin() || isEditor());
?>

<!-- Minimalist Threads Style CSS -->
<style>
    .thread-line {
        position: absolute;
        top: 48px;
        bottom: 12px;
        left: 20px;
        width: 2px;
        background-color: #e2e8f0;
        border-radius: 1px;
    }
    .threads-card {
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s ease;
    }
    .threads-card:hover {
        background-color: #fafafa;
    }
    .action-icon-btn {
        color: #64748b;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .action-icon-btn:hover {
        color: #000000;
        transform: scale(1.12);
    }
    .action-icon-btn.liked {
        color: #ef4444 !important;
        animation: heartBeat 0.3s ease;
    }
    @keyframes heartBeat {
        0% { transform: scale(1); }
        35% { transform: scale(1.3); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .threads-input:focus {
        box-shadow: none;
    }
</style>

<div class="min-h-screen bg-white pt-24 pb-12">
    <!-- Threads Centered Narrow Container -->
    <div class="max-w-[620px] mx-auto px-4">
        
        <!-- Header Page Title -->
        <div class="text-center py-6 mb-4 border-b border-slate-100">
            <h1 class="text-2xl font-black tracking-tight text-slate-900 font-display">Hỏi đáp Cộng đồng</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-widest">Bright Threads</p>
        </div>
        
        <!-- Create Post Section (Inline avatar layout like Threads) -->
        <?php if ($is_logged_in): ?>
        <div class="py-4 border-b border-slate-100 mb-6">
            <div class="flex gap-3">
                <!-- User Avatar -->
                <div class="w-11 h-11 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200 shadow-sm">
                    <i class="bi bi-person-fill text-slate-400 text-xl"></i>
                </div>
                <div class="flex-1">
                    <div class="font-bold text-[14px] text-slate-800 mb-1"><?= htmlspecialchars($current_user_name) ?></div>
                    <textarea id="post_content" rows="2" placeholder="Bạn đang thắc mắc điều gì về du học Nhật Bản?..." class="threads-input w-full bg-transparent text-[15px] placeholder-slate-400 focus:outline-none resize-none py-1"></textarea>
                    
                    <div class="flex justify-between items-center mt-3 pt-2 border-t border-slate-50">
                        <span class="text-xs font-semibold text-slate-400">Bất kỳ ai cũng có thể trả lời</span>
                        <button id="btn_post_question" class="bg-black text-white font-bold text-xs px-5 py-2 rounded-full hover:bg-slate-850 active:scale-95 transition-all shadow-sm">Đăng bài</button>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-slate-50 rounded-2xl p-6 mb-8 text-center border border-slate-100">
            <h3 class="text-base font-bold text-slate-800 mb-1">Gửi thắc mắc của bạn</h3>
            <p class="text-xs text-slate-500 mb-4">Đăng nhập để đặt câu hỏi. Ban quản trị và cộng đồng sẽ hỗ trợ bạn.</p>
            <a href="/login?redirect=/qa" class="inline-flex bg-black text-white font-bold text-xs px-6 py-2.5 rounded-full hover:bg-slate-850 active:scale-95 transition-all shadow-sm">Đăng nhập</a>
        </div>
        <?php endif; ?>

        <!-- Feed Threads Container -->
        <div id="feed_container" class="space-y-1">
            <!-- Loading Spinner -->
            <div id="feed_loading" class="text-center py-12">
                <i class="bi bi-arrow-repeat text-2xl text-slate-300 animate-spin inline-block"></i>
                <p class="text-slate-400 mt-2 text-xs font-medium">Đang tải các cuộc thảo luận...</p>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedContainer = document.getElementById('feed_container');
    const loadingIndicator = document.getElementById('feed_loading');
    const btnPost = document.getElementById('btn_post_question');
    const postContent = document.getElementById('post_content');

    // Authentication & Authorization Context
    const isLoggedIn = <?= $is_logged_in ? 'true' : 'false' ?>;
    const isAdmin = <?= $is_admin ? 'true' : 'false' ?>;
    const currentUserName = <?= json_encode($current_user_name) ?>;

    // Fetch feed from API
    function loadFeed() {
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        fetch('/api/qa_action', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_feed'
        })
        .then(res => res.json())
        .then(res => {
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            if (res.status === 'success') {
                renderFeed(res.data);
            } else {
                console.error('Lỗi khi tải dữ liệu');
            }
        })
        .catch(err => {
            console.error(err);
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        });
    }

    // Render all questions and replies
    function renderFeed(questions) {
        // Clear previous items
        feedContainer.querySelectorAll('.threads-card').forEach(el => el.remove());
        const emptyState = feedContainer.querySelector('.empty-state');
        if (emptyState) emptyState.remove();
        
        if (questions.length === 0) {
            feedContainer.insertAdjacentHTML('beforeend', `
                <div class="empty-state text-center text-slate-400 py-12 bg-slate-50 rounded-2xl border border-slate-100">
                    <i class="bi bi-chat-text text-3xl text-slate-300 mb-2 block"></i>
                    <span class="text-xs font-medium">Chưa có câu hỏi nào. Bắt đầu cuộc thảo luận đầu tiên!</span>
                </div>
            `);
            return;
        }

        questions.forEach(q => {
            const card = createThreadCard(q);
            feedContainer.appendChild(card);
        });
    }

    // Generate HTML for one thread card
    function createThreadCard(q) {
        const div = document.createElement('div');
        div.className = 'threads-card py-5 px-1 relative';
        div.dataset.id = q.id;

        const timeString = formatTime(q.created_at);
        const hasReplies = q.answers && q.answers.length > 0;

        // Render replies if exists
        let repliesHtml = '';
        if (hasReplies) {
            repliesHtml = q.answers.map(ans => `
                <!-- Reply Row -->
                <div class="flex gap-3 mt-4 relative" data-ans-id="${ans.id}">
                    <!-- Left Column: Smaller Avatar -->
                    <div class="flex flex-col items-center flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden relative shadow-sm">
                            <i class="bi bi-person-fill text-slate-400 text-xs"></i>
                        </div>
                    </div>
                    <!-- Right Column: Reply Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="font-bold text-[13.5px] text-slate-900">${escapeHtml(ans.author_name)}</span>
                            <span class="bg-black text-[9px] font-black text-white px-1.5 py-0.5 rounded-full uppercase tracking-wider scale-90">Admin</span>
                            <span class="text-[11px] text-slate-400 ml-auto">${formatTime(ans.created_at)}</span>
                        </div>
                        <div class="text-[14px] text-slate-700 leading-relaxed">${escapeHtml(ans.content)}</div>
                        
                        <!-- Reply Interactions -->
                        <div class="flex items-center gap-4 mt-2">
                            <button class="action-icon-btn btn-like-answer flex items-center gap-1 text-[12px]" data-id="${ans.id}">
                                <i class="bi bi-heart"></i>
                                <span>Thích ${ans.likes_count > 0 ? `<span class="font-bold">(${ans.likes_count})</span>` : ''}</span>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        div.innerHTML = `
            <!-- Thread connecting line on the left side -->
            ${hasReplies ? '<div class="thread-line"></div>' : ''}
            
            <div class="flex gap-3">
                <!-- Left Column: Main Avatar -->
                <div class="flex flex-col items-center flex-shrink-0">
                    <div class="w-11 h-11 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shadow-sm">
                        <i class="bi bi-person-fill text-slate-400 text-lg"></i>
                    </div>
                </div>
                
                <!-- Right Column: Post Body -->
                <div class="flex-1 min-w-0">
                    <!-- User & Time Header -->
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-[14px] text-slate-900 hover:underline cursor-pointer">${escapeHtml(q.author_name)}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-[12px] text-slate-400">${timeString}</span>
                        </div>
                    </div>
                    
                    <!-- Post Text -->
                    <div class="text-[14.5px] text-slate-800 leading-relaxed mb-3 whitespace-pre-wrap">${escapeHtml(q.content)}</div>
                    
                    <!-- Threads Style Interaction Buttons -->
                    <div class="flex items-center gap-5 py-1 text-slate-500">
                        <button class="action-icon-btn btn-like-question flex items-center gap-1.5 text-sm" data-id="${q.id}">
                            <i class="bi bi-heart"></i>
                            <span class="text-[12px] font-semibold q-like-count">${q.likes_count || 'Thích'}</span>
                        </button>
                        
                        <button class="action-icon-btn btn-comment-focus flex items-center gap-1.5 text-sm">
                            <i class="bi bi-chat"></i>
                            <span class="text-[12px] font-semibold">${q.answers ? q.answers.length : 0}</span>
                        </button>
                        
                        <button class="action-icon-btn flex items-center text-sm" onclick="alert('Đã chia sẻ liên kết câu hỏi này!')">
                            <i class="bi bi-share"></i>
                        </button>
                    </div>

                    <!-- Nested Replies Container -->
                    <div class="replies-list">
                        ${repliesHtml}
                    </div>

                    <!-- Admin Inline Reply Box -->
                    ${isAdmin ? `
                    <div class="flex gap-2.5 mt-4 pt-3 border-t border-slate-50">
                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden shadow-xs">
                            <i class="bi bi-person-fill text-slate-400 text-xs"></i>
                        </div>
                        <div class="flex-1 flex gap-2">
                            <input type="text" class="ans_content flex-1 bg-slate-50 hover:bg-slate-100/75 focus:bg-white border border-slate-100 rounded-full px-4 py-1.5 text-[13px] placeholder-slate-400 focus:outline-none focus:border-slate-300 transition-all" placeholder="Nhập câu trả lời admin..." data-qid="${q.id}">
                            <button class="btn-post-answer bg-black text-white hover:bg-slate-800 rounded-full px-4 py-1 text-[11px] font-bold transition-all" data-qid="${q.id}">Gửi</button>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        return div;
    }

    // Format Timestamp relative to now
    function formatTime(dateString) {
        const date = new Date(dateString.replace(' ', 'T'));
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        
        if (diffMins < 1) return 'Vừa xong';
        if (diffMins < 60) return `${diffMins} phút`;
        const diffHours = Math.floor(diffMins / 60);
        if (diffHours < 24) return `${diffHours} giờ`;
        const diffDays = Math.floor(diffHours / 24);
        if (diffDays < 7) return `${diffDays} ngày`;
        return date.toLocaleDateString('vi-VN', { day: 'numeric', month: 'short' });
    }

    // Escape special HTML chars to prevent XSS
    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Event handler for publishing a new question
    if (btnPost && postContent) {
        btnPost.addEventListener('click', () => {
            const content = postContent.value.trim();

            if (!content) {
                alert('Vui lòng nhập nội dung thắc mắc');
                return;
            }

            btnPost.disabled = true;
            btnPost.textContent = 'Đang đăng...';

            const params = new URLSearchParams();
            params.append('action', 'post_question');
            params.append('content', content);

            fetch('/api/qa_action', {
                method: 'POST',
                body: params
            })
            .then(res => res.json())
            .then(res => {
                btnPost.disabled = false;
                btnPost.textContent = 'Đăng bài';
                if (res.status === 'success') {
                    postContent.value = '';
                    loadFeed(); // Refresh the list
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                btnPost.disabled = false;
                btnPost.textContent = 'Đăng bài';
            });
        });
    }

    // Event delegation for various inline clicks (Like, Comment focus)
    feedContainer.addEventListener('click', (e) => {
        // Focus reply input field
        if (e.target.closest('.btn-comment-focus')) {
            const card = e.target.closest('.threads-card');
            const input = card.querySelector('.ans_content');
            if (input) input.focus();
        }

        // Like/Heart a Question
        if (e.target.closest('.btn-like-question')) {
            const btn = e.target.closest('.btn-like-question');
            const qid = btn.dataset.id;
            
            const params = new URLSearchParams();
            params.append('action', 'like_question');
            params.append('question_id', qid);

            fetch('/api/qa_action', { method: 'POST', body: params })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    const countEl = btn.querySelector('.q-like-count');
                    if (countEl) {
                        countEl.textContent = res.likes;
                    }
                    btn.classList.add('liked');
                    btn.querySelector('i').className = 'bi bi-heart-fill';
                }
            });
        }

        // Like/Heart a specific Reply/Answer
        if (e.target.closest('.btn-like-answer')) {
            const btn = e.target.closest('.btn-like-answer');
            const ansId = btn.dataset.id;
            
            const params = new URLSearchParams();
            params.append('action', 'like_answer');
            params.append('answer_id', ansId);

            fetch('/api/qa_action', { method: 'POST', body: params })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    btn.innerHTML = `<i class="bi bi-heart-fill text-red-500"></i> <span class="font-bold">(${res.likes})</span>`;
                    btn.classList.add('liked');
                }
            });
        }

        // Submit Reply (Admin only button)
        if (e.target.closest('.btn-post-answer')) {
            const btn = e.target.closest('.btn-post-answer');
            const qid = btn.dataset.qid;
            const card = btn.closest('.threads-card');
            postAnswer(card, qid);
        }
    });

    // Handle pressing Enter inside the reply input field
    feedContainer.addEventListener('keypress', (e) => {
        if (e.target.classList.contains('ans_content') && e.key === 'Enter') {
            const qid = e.target.dataset.qid;
            const card = e.target.closest('.threads-card');
            postAnswer(card, qid);
        }
    });

    // Core post reply function
    function postAnswer(card, qid) {
        if (!isAdmin) return;
        
        const inputContent = card.querySelector('.ans_content');
        if (!inputContent) return;
        
        const content = inputContent.value.trim();
        if (!content) return;

        const params = new URLSearchParams();
        params.append('action', 'post_answer');
        params.append('question_id', qid);
        params.append('content', content);

        fetch('/api/qa_action', {
            method: 'POST',
            body: params
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                inputContent.value = '';
                loadFeed(); // Reload the whole feed structure
            } else {
                alert(res.message);
            }
        });
    }

    // Initial Load of feed
    loadFeed();
});
</script>

<?php require_once 'includes/footer.php'; ?>
