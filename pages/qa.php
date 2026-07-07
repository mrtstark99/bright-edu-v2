<?php
/**
 * Q&A Page - Facebook Feed Style
 */
$page_title = 'Hỏi đáp - Cộng đồng Bright Education';
require_once 'includes/header.php';

$is_logged_in = isLoggedIn();
$current_user_name = $is_logged_in ? $_SESSION['user_name'] ?? 'Thành viên' : '';
$is_admin = $is_logged_in && (isAdmin() || isEditor());
?>

<div class="min-h-screen bg-[#f0f2f5] pt-28 pb-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Post creation box -->
        <?php if ($is_logged_in): ?>
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden">
                    <i class="bi bi-person-fill text-slate-400 text-xl"></i>
                </div>
                <div class="flex-1">
                    <textarea id="post_content" rows="2" placeholder="Bạn có câu hỏi gì về du học Nhật Bản?" class="w-full bg-slate-100 rounded-xl px-4 py-3 text-[15px] focus:outline-none focus:ring-2 focus:ring-primary/20 resize-none"></textarea>
                </div>
            </div>
            <div class="border-t border-slate-100 mt-3 pt-3 flex justify-end">
                <button id="btn_post_question" class="bg-primary text-white font-semibold px-6 py-1.5 rounded-md hover:bg-primary/90 transition-colors">Đăng câu hỏi</button>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 text-primary mb-4">
                <i class="bi bi-question-circle text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">Bạn có thắc mắc?</h3>
            <p class="text-slate-600 mb-5">Vui lòng đăng nhập để gửi câu hỏi của bạn. Chuyên gia của Bright Education sẽ giải đáp sớm nhất!</p>
            <a href="/login?redirect=/qa" class="inline-block bg-primary text-white font-semibold px-8 py-2.5 rounded-xl hover:bg-primary/90 transition-colors shadow-soft hover:-translate-y-0.5">Đăng nhập ngay</a>
        </div>
        <?php endif; ?>

        <!-- Feed container -->
        <div id="feed_container" class="space-y-6">
            <!-- Loading indicator -->
            <div id="feed_loading" class="text-center py-8">
                <i class="bi bi-arrow-repeat text-2xl text-slate-400 animate-spin inline-block"></i>
                <p class="text-slate-500 mt-2 text-sm">Đang tải...</p>
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
    const postAuthorName = document.getElementById('post_author_name');

    // Current user context
    const isLoggedIn = <?= $is_logged_in ? 'true' : 'false' ?>;
    const isAdmin = <?= $is_admin ? 'true' : 'false' ?>;
    const currentUserName = <?= json_encode($current_user_name) ?>;

    // Load feed
    function loadFeed() {
        loadingIndicator.style.display = 'block';
        fetch('/api/qa_action', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_feed'
        })
        .then(res => res.json())
        .then(res => {
            loadingIndicator.style.display = 'none';
            if (res.status === 'success') {
                renderFeed(res.data);
            } else {
                alert('Có lỗi xảy ra khi tải dữ liệu');
            }
        })
        .catch(err => {
            console.error(err);
            loadingIndicator.style.display = 'none';
        });
    }

    // Render feed
    function renderFeed(questions) {
        // Remove old cards
        feedContainer.querySelectorAll('.qa-card').forEach(el => el.remove());
        
        if (questions.length === 0) {
            feedContainer.insertAdjacentHTML('beforeend', '<div class="qa-card text-center text-slate-500 py-10 bg-white rounded-xl shadow-sm">Chưa có câu hỏi nào. Hãy là người đầu tiên!</div>');
            return;
        }

        questions.forEach(q => {
            const card = createQuestionCard(q);
            feedContainer.appendChild(card);
        });
    }

    // Create question card HTML
    function createQuestionCard(q) {
        const div = document.createElement('div');
        div.className = 'qa-card bg-white rounded-xl shadow-sm overflow-hidden';
        div.dataset.id = q.id;

        const timeString = formatTime(q.created_at);

        let answersHtml = '';
        if (q.answers && q.answers.length > 0) {
            answersHtml = q.answers.map(ans => `
                <div class="flex gap-2 mt-3 group" data-ans-id="${ans.id}">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden">
                        <i class="bi bi-person-fill text-slate-400 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-primary/5 border border-primary/10 rounded-2xl px-4 py-3 inline-block relative">
                            <div class="absolute -top-3 -right-3 bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm flex items-center gap-1">
                                <i class="bi bi-check-circle-fill"></i> Quản trị viên
                            </div>
                            <div class="font-semibold text-sm text-primary mb-1">${escapeHtml(ans.author_name)}</div>
                            <div class="text-[15px] text-slate-800">${escapeHtml(ans.content)}</div>
                        </div>
                        <div class="text-[12px] text-slate-500 mt-1 ml-2 flex gap-3">
                            <span class="font-semibold cursor-pointer hover:underline text-slate-600 btn-like-answer" data-id="${ans.id}">Thích ${ans.likes_count > 0 ? `(${ans.likes_count})` : ''}</span>
                            <span>${formatTime(ans.created_at)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        div.innerHTML = `
            <div class="p-4">
                <!-- Header -->
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden">
                        <i class="bi bi-person-fill text-slate-400 text-xl"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-[15px] text-slate-900">${escapeHtml(q.author_name)}</div>
                        <div class="text-[13px] text-slate-500">${timeString} <i class="bi bi-globe-americas ml-1"></i></div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="text-[15px] text-slate-800 mb-4 whitespace-pre-wrap">${escapeHtml(q.content)}</div>
                
                <!-- Stats -->
                ${q.likes_count > 0 || (q.answers && q.answers.length > 0) ? `
                <div class="flex justify-between items-center text-[13px] text-slate-500 py-2 border-b border-slate-100">
                    <div class="flex items-center gap-1">
                        ${q.likes_count > 0 ? `<div class="w-4 h-4 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px]"><i class="bi bi-hand-thumbs-up-fill"></i></div> <span class="q-like-count">${q.likes_count}</span>` : '<span class="q-like-count hidden"></span>'}
                    </div>
                    <div>
                        ${q.answers && q.answers.length > 0 ? `${q.answers.length} bình luận` : ''}
                    </div>
                </div>
                ` : '<div class="border-b border-slate-100 hidden-stats"><span class="q-like-count hidden">0</span></div>'}

                <!-- Actions -->
                <div class="flex px-2 py-1 gap-1 border-b border-slate-100">
                    <button class="flex-1 flex items-center justify-center gap-2 py-2 rounded-md hover:bg-slate-50 text-slate-600 font-semibold text-[15px] transition-colors btn-like-question" data-id="${q.id}">
                        <i class="bi bi-hand-thumbs-up"></i> Thích
                    </button>
                    ${isAdmin ? `
                    <button class="flex-1 flex items-center justify-center gap-2 py-2 rounded-md hover:bg-slate-50 text-slate-600 font-semibold text-[15px] transition-colors btn-comment-focus">
                        <i class="bi bi-reply"></i> Trả lời
                    </button>
                    ` : ''}
                </div>

                <!-- Comments Area -->
                <div class="pt-4">
                    <div class="comments-list ${isAdmin ? 'mb-4' : ''}">
                        ${answersHtml}
                    </div>
                    
                    ${isAdmin ? `
                    <!-- Add comment input -->
                    <div class="flex gap-2">
                        <div class="w-8 h-8 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden">
                            <i class="bi bi-person-fill text-slate-400 text-sm"></i>
                        </div>
                        <div class="flex-1 bg-slate-100 rounded-2xl flex flex-col relative overflow-hidden">
                            <input type="text" class="ans_content w-full bg-transparent px-4 py-2.5 text-[15px] focus:outline-none" placeholder="Viết câu trả lời dưới danh nghĩa quản trị..." data-qid="${q.id}">
                            <button class="btn-post-answer absolute right-2 bottom-1.5 text-primary hover:bg-slate-200 p-1.5 rounded-full w-8 h-8 flex items-center justify-center transition-colors" data-qid="${q.id}">
                                <i class="bi bi-send-fill text-sm"></i>
                            </button>
                        </div>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        return div;
    }

    // Helper to format time
    function formatTime(dateString) {
        const date = new Date(dateString.replace(' ', 'T'));
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        
        if (diffMins < 1) return 'Vừa xong';
        if (diffMins < 60) return `${diffMins} phút trước`;
        const diffHours = Math.floor(diffMins / 60);
        if (diffHours < 24) return `${diffHours} giờ trước`;
        const diffDays = Math.floor(diffHours / 24);
        if (diffDays < 7) return `${diffDays} ngày trước`;
        return date.toLocaleDateString('vi-VN');
    }

    // Helper to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Post Question
    if (btnPost) {
        btnPost.addEventListener('click', () => {
            const content = postContent.value.trim();

            if (!content) {
                alert('Vui lòng nhập nội dung');
                return;
            }

            const btnText = btnPost.innerHTML;
            btnPost.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i>';
            btnPost.disabled = true;

            const params = new URLSearchParams();
            params.append('action', 'post_question');
            params.append('content', content);

            fetch('/api/qa_action', {
                method: 'POST',
                body: params
            })
            .then(res => res.json())
            .then(res => {
                btnPost.innerHTML = btnText;
                btnPost.disabled = false;
                if (res.status === 'success') {
                    postContent.value = '';
                    loadFeed(); // Reload feed to show new post
                } else {
                    alert(res.message);
                }
            });
        });
    }

    // Event delegation for feed interactions
    feedContainer.addEventListener('click', (e) => {
        // Focus comment input
        if (e.target.closest('.btn-comment-focus')) {
            const card = e.target.closest('.qa-card');
            const input = card.querySelector('.ans_content');
            if (input) input.focus();
        }

        // Like question
        if (e.target.closest('.btn-like-question')) {
            const btn = e.target.closest('.btn-like-question');
            const qid = btn.dataset.id;
            
            // Optimistic UI update could be here, but let's just call API and re-render the count
            const params = new URLSearchParams();
            params.append('action', 'like_question');
            params.append('question_id', qid);

            fetch('/api/qa_action', { method: 'POST', body: params })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    const card = btn.closest('.qa-card');
                    let countEl = card.querySelector('.q-like-count');
                    if (countEl) {
                        countEl.textContent = res.likes;
                        countEl.classList.remove('hidden');
                        countEl.parentElement.innerHTML = `<div class="w-4 h-4 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px]"><i class="bi bi-hand-thumbs-up-fill"></i></div> <span class="q-like-count">${res.likes}</span>`;
                        // Remove hidden from stats container if it was hidden
                        const hiddenStats = card.querySelector('.hidden-stats');
                        if (hiddenStats) {
                            hiddenStats.className = 'flex justify-between items-center text-[13px] text-slate-500 py-2 border-b border-slate-100';
                            hiddenStats.innerHTML = `<div class="flex items-center gap-1"><div class="w-4 h-4 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px]"><i class="bi bi-hand-thumbs-up-fill"></i></div> <span class="q-like-count">${res.likes}</span></div><div></div>`;
                        }
                    }
                    
                    // Highlight button
                    btn.classList.add('text-primary');
                    btn.querySelector('i').classList.replace('bi-hand-thumbs-up', 'bi-hand-thumbs-up-fill');
                }
            });
        }

        // Like answer
        if (e.target.closest('.btn-like-answer')) {
            const btn = e.target.closest('.btn-like-answer');
            const ansId = btn.dataset.id;
            
            const params = new URLSearchParams();
            params.append('action', 'like_answer');
            params.append('answer_id', ansId);

            fetch('/api/qa_action', { method: 'POST', body: params })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    btn.textContent = `Thích (${res.likes})`;
                    btn.classList.add('text-primary');
                }
            });
        }

        // Post answer
        if (e.target.closest('.btn-post-answer')) {
            const btn = e.target.closest('.btn-post-answer');
            const qid = btn.dataset.qid;
            const card = btn.closest('.qa-card');
            postAnswer(card, qid);
        }
    });

    // Enter to post answer
    feedContainer.addEventListener('keypress', (e) => {
        if (e.target.classList.contains('ans_content') && e.key === 'Enter') {
            const qid = e.target.dataset.qid;
            const card = e.target.closest('.qa-card');
            postAnswer(card, qid);
        }
    });

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
                loadFeed(); // Reload to show new answer
            } else {
                alert(res.message);
            }
        });
    }

    // Initial load
    loadFeed();
});
</script>

<?php require_once 'includes/footer.php'; ?>
