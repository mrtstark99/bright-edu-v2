<?php
/**
 * Q&A Page - Optimized Threads Style with Popup Post Creator & Details View Focus
 */
$page_title = 'Hỏi đáp - Cộng đồng Bright Education';
require_once 'includes/header.php';

$is_logged_in = isLoggedIn();
$current_user_name = $is_logged_in ? $_SESSION['user_name'] ?? 'Thành viên' : '';
$is_admin = $is_logged_in && (isAdmin() || isEditor());
?>

<!-- Premium Minimalist Threads Style CSS -->
<style>
    /* Thread connection line perfectly centered with the avatars */
    .thread-line {
        position: absolute;
        top: 56px;
        bottom: 24px;
        left: 38px;
        width: 2px;
        background-color: #f1f5f9;
        border-radius: 99px;
        z-index: 10;
    }
    .threads-card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .threads-card:hover {
        border-color: #e2e8f0;
        box-shadow: 0 10px 30px -10px rgba(13, 36, 62, 0.06);
    }
    .action-icon-btn {
        color: #94a3b8;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .action-icon-btn:hover {
        color: #0d243e;
        transform: scale(1.1);
    }
    .action-icon-btn.liked {
        color: #ef4444 !important;
        animation: heartBeat 0.3s ease;
    }
    @keyframes heartBeat {
        0% { transform: scale(1); }
        35% { transform: scale(1.25); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .tag-badge {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .tag-badge:hover {
        background-color: #0d243e;
        color: #ffffff;
        border-color: #0d243e;
    }
    .tag-badge.active {
        background-color: #0d243e;
        color: #ffffff;
        border-color: #0d243e;
    }

    /* Gradient Background Presets */
    .bg-threads-dark {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    .bg-threads-sunrise {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
    }
    .bg-threads-neon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .bg-threads-ocean {
        background: linear-gradient(135deg, #2af598 0%, #009ad0 100%);
    }
    .bg-threads-sakura {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
    }
    .bg-threads-lavender {
        background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
    }
</style>

<main class="pt-20 bg-slate-50 min-h-screen">
    <section class="max-w-6xl mx-auto px-5 lg:px-8 mt-12 mb-20">
        
        <div class="grid grid-cols-1 md:grid-cols-[240px_1fr] gap-8 items-start">
            
            <!-- Left Sidebar: Category tags list -->
            <aside class="sticky top-28 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-soft hidden md:block">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></div>
                    <h3 class="text-[11px] font-black uppercase tracking-wider text-slate-400">Chủ đề thảo luận</h3>
                </div>
                <div class="flex flex-col gap-1.5" id="sidebar_tags_list">
                    <button class="tag-badge text-left px-3.5 py-2.5 rounded-xl text-[13.5px] font-bold text-slate-600 bg-slate-50 hover:bg-slate-100 border border-transparent transition-all active active-all" data-tag="">
                        <i class="bi bi-grid mr-2 text-xs text-slate-400"></i> Tất cả chủ đề
                    </button>
                    <button class="tag-badge text-left px-3.5 py-2.5 rounded-xl text-[13.5px] font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-100 transition-all" data-tag="#tuyensinh">#tuyensinh</button>
                    <button class="tag-badge text-left px-3.5 py-2.5 rounded-xl text-[13.5px] font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-100 transition-all" data-tag="#hocphi">#hocphi</button>
                    <button class="tag-badge text-left px-3.5 py-2.5 rounded-xl text-[13.5px] font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-100 transition-all" data-tag="#visanhat">#visanhat</button>
                    <button class="tag-badge text-left px-3.5 py-2.5 rounded-xl text-[13.5px] font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-100 transition-all" data-tag="#vieclam">#vieclam</button>
                    <button class="tag-badge text-left px-3.5 py-2.5 rounded-xl text-[13.5px] font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-100 transition-all" data-tag="#nhatngu">#nhatngu</button>
                    <button class="tag-badge text-left px-3.5 py-2.5 rounded-xl text-[13.5px] font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-100 transition-all" data-tag="#cuocsong">#cuocsong</button>
                </div>
            </aside>

            <!-- Right Feed: Content area -->
            <div class="max-w-[620px] w-full mx-auto md:mx-0 flex flex-col gap-6">
                
                <!-- Title & tag list for mobile -->
                <div class="md:hidden">
                    <h2 class="text-xl font-black text-primary font-display mb-3">Hỏi đáp Cộng đồng</h2>
                    <div class="flex gap-2 overflow-x-auto pb-3 select-none scrollbar-hide" id="mobile_tags_list">
                        <button class="tag-badge shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 bg-white border border-slate-200 active active-all" data-tag="">Tất cả</button>
                        <button class="tag-badge shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 bg-white border border-slate-200" data-tag="#tuyensinh">#tuyensinh</button>
                        <button class="tag-badge shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 bg-white border border-slate-200" data-tag="#hocphi">#hocphi</button>
                        <button class="tag-badge shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 bg-white border border-slate-200" data-tag="#visanhat">#visanhat</button>
                        <button class="tag-badge shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 bg-white border border-slate-200" data-tag="#vieclam">#vieclam</button>
                        <button class="tag-badge shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 bg-white border border-slate-200" data-tag="#nhatngu">#nhatngu</button>
                        <button class="tag-badge shrink-0 px-3.5 py-1.5 rounded-full text-xs font-bold text-slate-600 bg-white border border-slate-200" data-tag="#cuocsong">#cuocsong</button>
                    </div>
                </div>

                <!-- One post creation trigger button -->
                <div class="bg-white p-4.5 rounded-[1.5rem] border border-slate-100 shadow-soft">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200 shadow-xs">
                            <i class="bi bi-person-fill text-slate-400 text-xl"></i>
                        </div>
                        <?php if ($is_logged_in): ?>
                        <button id="btn_open_post_modal" class="flex-1 bg-slate-50 hover:bg-slate-100/75 border border-slate-200 text-slate-500 rounded-full px-5 py-3 text-left text-xs font-bold flex items-center justify-between transition-colors shadow-xs">
                            <span>Bạn có thắc mắc gì về du học Nhật Bản? Đăng bài ngay...</span>
                            <i class="bi bi-pencil-square text-slate-400 text-base"></i>
                        </button>
                        <?php else: ?>
                        <a href="/login?redirect=/qa" class="flex-1 bg-slate-50 hover:bg-slate-100/75 border border-slate-200 text-slate-500 rounded-full px-5 py-3 text-left text-xs font-bold flex items-center justify-between transition-colors shadow-xs">
                            <span>Đăng nhập để đặt câu hỏi thảo luận...</span>
                            <i class="bi bi-box-arrow-in-right text-slate-400 text-base"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Back button to return to list -->
                <div id="details_back_bar" class="hidden mb-2">
                    <button id="btn_close_details" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-primary py-2 px-4 bg-white border border-slate-100 rounded-full shadow-soft transition-colors">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách câu hỏi
                    </button>
                </div>

                <!-- Feed Thread Cards -->
                <div id="feed_container" class="flex flex-col gap-4">
                    <!-- Loading Spinner -->
                    <div id="feed_loading" class="text-center py-16 bg-white rounded-[2rem] border border-slate-100 shadow-soft">
                        <i class="bi bi-arrow-repeat text-3xl text-primary/30 animate-spin inline-block"></i>
                        <p class="text-slate-400 mt-3 text-xs font-semibold">Đang tải các thảo luận...</p>
                    </div>
                </div>

            </div>
        </div>

    </section>
</main>

<!-- Popup Modal for Creating a Question -->
<?php if ($is_logged_in): ?>
<div id="post_modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <!-- Overlay Backdrop -->
    <div id="post_modal_backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-xs transition-opacity duration-300"></div>
    
    <!-- Modal Box -->
    <div id="post_modal_card" class="bg-white w-full max-w-[540px] rounded-[2rem] p-6 shadow-hard relative z-10 mx-4 transform scale-95 opacity-0 transition-all duration-300">
        <!-- Close Button top right -->
        <button id="btn_close_post_modal" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-black transition-colors">
            <i class="bi bi-x-lg text-xs"></i>
        </button>

        <h3 class="text-base font-black text-slate-900 font-display mb-4 pb-3 border-b border-slate-100">Đặt câu hỏi cộng đồng</h3>
        
        <div class="flex gap-4">
            <!-- User Avatar -->
            <div class="w-12 h-12 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200 shadow-xs">
                <i class="bi bi-person-fill text-slate-400 text-2xl"></i>
            </div>
            <div class="flex-1">
                <div class="font-bold text-[14px] text-slate-900 mb-1"><?= htmlspecialchars($current_user_name) ?></div>
                
                <!-- Background wrapper preview -->
                <div id="editor_bg_wrapper" class="rounded-2xl transition-all duration-350 p-0">
                    <textarea id="post_content" rows="4" placeholder="Bạn đang thắc mắc điều gì về du học Nhật Bản?..." class="w-full bg-transparent text-[15px] placeholder-slate-400 focus:outline-none resize-none py-2 px-1 leading-relaxed transition-all duration-300"></textarea>
                </div>
                
                <!-- Colors Preset Panel -->
                <div class="flex items-center gap-2 mt-3 mb-1 select-none">
                    <span class="text-[10px] font-black uppercase text-slate-400 mr-1">Hình nền:</span>
                    <button class="w-5 h-5 rounded-full border border-slate-300 bg-white flex items-center justify-center text-[10px] text-slate-400 hover:scale-110 active:scale-95 transition-all btn-bg-preset" data-bg="" title="Mặc định"><i class="bi bi-slash-circle"></i></button>
                    <button class="w-5 h-5 rounded-full bg-threads-dark hover:scale-110 active:scale-95 transition-all btn-bg-preset" data-bg="bg-threads-dark" title="Tối giản"></button>
                    <button class="w-5 h-5 rounded-full bg-threads-sunrise hover:scale-110 active:scale-95 transition-all btn-bg-preset" data-bg="bg-threads-sunrise" title="Bình minh"></button>
                    <button class="w-5 h-5 rounded-full bg-threads-neon hover:scale-110 active:scale-95 transition-all btn-bg-preset" data-bg="bg-threads-neon" title="Neon"></button>
                    <button class="w-5 h-5 rounded-full bg-threads-ocean hover:scale-110 active:scale-95 transition-all btn-bg-preset" data-bg="bg-threads-ocean" title="Đại dương"></button>
                    <button class="w-5 h-5 rounded-full bg-threads-sakura hover:scale-110 active:scale-95 transition-all btn-bg-preset" data-bg="bg-threads-sakura" title="Sakura"></button>
                    <button class="w-5 h-5 rounded-full bg-threads-lavender hover:scale-110 active:scale-95 transition-all btn-bg-preset" data-bg="bg-threads-lavender" title="Lavender"></button>
                </div>

                <!-- Attachment image preview -->
                <div id="image_preview_container" class="hidden relative mt-3 rounded-2xl overflow-hidden border border-slate-100 max-h-64 shadow-xs select-none">
                    <img id="image_preview" src="" class="w-full h-full object-cover">
                    <button id="btn_remove_image" class="absolute top-3 right-3 bg-black/60 hover:bg-black/85 text-white rounded-full p-1.5 w-8 h-8 flex items-center justify-center transition-colors">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>

                <!-- Actions Footer -->
                <div class="flex justify-between items-center mt-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <button id="btn_trigger_file" class="text-slate-400 hover:text-primary transition-colors p-1" title="Đính kèm ảnh">
                            <i class="bi bi-image text-lg"></i>
                        </button>
                        <input type="file" id="file_input" class="hidden" accept="image/*">
                        
                        <select id="post_tag" class="bg-slate-50 border border-slate-200 text-[11px] font-bold text-slate-500 rounded-full px-3 py-1 focus:outline-none focus:border-slate-350 transition-colors">
                            <option value="">Chọn chủ đề</option>
                            <option value="#tuyensinh">#tuyensinh</option>
                            <option value="#hocphi">#hocphi</option>
                            <option value="#visanhat">#visanhat</option>
                            <option value="#vieclam">#vieclam</option>
                            <option value="#nhatngu">#nhatngu</option>
                            <option value="#cuocsong">#cuocsong</option>
                        </select>
                    </div>
                    <button id="btn_post_question" class="bg-primary text-white font-bold text-xs px-6 py-2.5 rounded-full hover:bg-slate-800 active:scale-95 transition-all shadow-sm">Đăng câu hỏi</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedContainer = document.getElementById('feed_container');
    const loadingIndicator = document.getElementById('feed_loading');
    
    // Modal Post Elements
    const postModal = document.getElementById('post_modal');
    const postModalCard = document.getElementById('post_modal_card');
    const btnOpenPostModal = document.getElementById('btn_open_post_modal');
    const btnClosePostModal = document.getElementById('btn_close_post_modal');
    const postModalBackdrop = document.getElementById('post_modal_backdrop');
    
    const btnPost = document.getElementById('btn_post_question');
    const postContent = document.getElementById('post_content');
    const postTag = document.getElementById('post_tag');
    const fileInput = document.getElementById('file_input');
    const btnTriggerFile = document.getElementById('btn_trigger_file');
    const imagePreviewContainer = document.getElementById('image_preview_container');
    const imagePreview = document.getElementById('image_preview');
    const btnRemoveImage = document.getElementById('btn_remove_image');
    const editorBgWrapper = document.getElementById('editor_bg_wrapper');
    
    const detailsBackBar = document.getElementById('details_back_bar');
    const btnCloseDetails = document.getElementById('btn_close_details');

    // Auth context
    const isLoggedIn = <?= $is_logged_in ? 'true' : 'false' ?>;
    const isAdmin = <?= $is_admin ? 'true' : 'false' ?>;
    const currentUserName = <?= json_encode($current_user_name) ?>;

    let selectedTag = '';
    let selectedFile = null;
    let viewingQuestionId = null;
    let currentBgStyle = '';

    // Check URL parameters for qid
    const urlParams = new URLSearchParams(window.location.search);
    const qidParam = urlParams.get('qid');
    if (qidParam) {
        viewingQuestionId = parseInt(qidParam);
        detailsBackBar.classList.remove('hidden');
    }

    // Modal popup triggers
    if (btnOpenPostModal) {
        btnOpenPostModal.addEventListener('click', () => {
            postModal.classList.remove('hidden');
            setTimeout(() => {
                postModalCard.classList.remove('scale-95', 'opacity-0');
                postModalCard.classList.add('scale-100', 'opacity-100');
            }, 50);
        });
    }

    function hidePostModal() {
        if (postModalCard) {
            postModalCard.classList.remove('scale-100', 'opacity-100');
            postModalCard.classList.add('scale-95', 'opacity-0');
        }
        setTimeout(() => {
            if (postModal) postModal.classList.add('hidden');
        }, 200);
    }

    if (btnClosePostModal) btnClosePostModal.addEventListener('click', hidePostModal);
    if (postModalBackdrop) postModalBackdrop.addEventListener('click', hidePostModal);

    // Load Feed
    function loadFeed() {
        if (viewingQuestionId) {
            loadSingleQuestion(viewingQuestionId);
            return;
        }

        if (loadingIndicator) loadingIndicator.style.display = 'block';
        
        let url = '/api/qa_action?action=get_feed';
        if (selectedTag) {
            url += '&tag=' + encodeURIComponent(selectedTag);
        }

        fetch(url)
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

    // Load Single Question Details
    function loadSingleQuestion(qid) {
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        
        const params = new URLSearchParams();
        params.append('action', 'get_question');
        params.append('question_id', qid);

        fetch('/api/qa_action', {
            method: 'POST',
            body: params
        })
        .then(res => res.json())
        .then(res => {
            if (loadingIndicator) loadingIndicator.style.display = 'none';
            if (res.status === 'success') {
                renderSingleQuestion(res.data);
            } else {
                feedContainer.innerHTML = `
                    <div class="text-center text-slate-400 py-16 bg-white rounded-[2rem] border border-slate-100 shadow-soft">
                        <i class="bi bi-exclamation-triangle text-4xl text-slate-300 mb-3 block"></i>
                        <span class="text-xs font-bold text-slate-500">Câu hỏi không tồn tại hoặc đã bị gỡ bỏ.</span>
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error(err);
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        });
    }

    // Render single question
    function renderSingleQuestion(q) {
        feedContainer.querySelectorAll('.threads-card').forEach(el => el.remove());
        const card = createThreadCard(q, true); // true = Detailed view with comments/interactions
        feedContainer.appendChild(card);
    }

    // Render feed
    function renderFeed(questions) {
        feedContainer.querySelectorAll('.threads-card').forEach(el => el.remove());
        const emptyState = feedContainer.querySelector('.empty-state');
        if (emptyState) emptyState.remove();
        
        if (questions.length === 0) {
            feedContainer.insertAdjacentHTML('beforeend', `
                <div class="empty-state text-center text-slate-400 py-16 bg-white rounded-[2rem] border border-slate-100 shadow-soft">
                    <i class="bi bi-chat-left-text text-4xl text-slate-200 mb-3 block"></i>
                    <span class="text-xs font-bold text-slate-500">Chưa có thắc mắc nào ở chủ đề này.</span>
                </div>
            `);
            return;
        }

        questions.forEach(q => {
            const card = createThreadCard(q, false); // false = Feed view (No comments, no likes shown)
            feedContainer.appendChild(card);
        });
    }

    // Generate HTML for card (Feeds view vs Detailed view logic)
    function createThreadCard(q, isDetailedView = false) {
        const div = document.createElement('div');
        div.className = 'threads-card bg-white p-6 rounded-[2rem] border border-slate-100 shadow-soft relative';
        div.dataset.id = q.id;

        const timeString = formatTime(q.created_at);
        const hasReplies = q.answers && q.answers.length > 0;

        // Image Attachment
        let attachmentHtml = '';
        if (q.image && !q.bg_style) {
            attachmentHtml = `
                <div class="mt-3 rounded-2xl overflow-hidden border border-slate-100 shadow-xs max-h-96">
                    <img src="/uploads/${q.image}" class="w-full object-cover max-h-96" alt="Ảnh đính kèm">
                </div>
            `;
        }

        // Main Text Content
        let contentHtml = '';
        if (q.bg_style) {
            contentHtml = `
                <div class="${q.bg_style} text-white font-extrabold text-base md:text-lg flex items-center justify-center text-center p-8 rounded-2xl min-h-[220px] shadow-sm leading-relaxed mb-1 whitespace-pre-wrap cursor-pointer btn-view-details">
                    <span>${escapeHtml(q.content)}</span>
                </div>
            `;
        } else {
            contentHtml = `
                <div class="text-[15px] text-slate-800 leading-relaxed mb-1 whitespace-pre-wrap cursor-pointer btn-view-details">${escapeHtml(q.content)}</div>
            `;
        }

        // Tag
        let tagHtml = '';
        if (q.tags) {
            tagHtml = `<span class="inline-block text-xs font-bold text-orange-600 hover:underline mr-2 cursor-pointer btn-tag-filter" data-tag="${q.tags}">${q.tags}</span>`;
        }

        // Footer Actions & Comments area (DIFFERENT between Feed view and Detailed View)
        let footerHtml = '';
        if (isDetailedView) {
            // Detailed View: Shows Likes count, Comments list, Like buttons, and comment input box
            let repliesHtml = '';
            if (hasReplies) {
                repliesHtml = q.answers.map(ans => {
                    const isAdminReply = ans.user_role === 'admin' || ans.user_role === 'editor' || ans.author_name.includes('Admin');
                    return `
                        <!-- Reply Item -->
                        <div class="flex gap-4 mt-5 pt-4 border-t border-slate-50 relative" data-ans-id="${ans.id}">
                            <div class="flex justify-center flex-shrink-0 w-12">
                                <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shadow-xs">
                                    <i class="bi bi-person-fill text-slate-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-[13.5px] text-slate-900">${escapeHtml(ans.author_name)}</span>
                                    ${isAdminReply ? `<span class="bg-black text-[9px] font-black text-white px-2 py-0.5 rounded-full uppercase tracking-wider scale-90">Admin</span>` : ''}
                                    <span class="text-[11px] text-slate-400 ml-auto">${formatTime(ans.created_at)}</span>
                                </div>
                                <div class="text-[14px] text-slate-700 leading-relaxed">${escapeHtml(ans.content)}</div>
                                
                                <div class="flex items-center gap-4 mt-2">
                                    <button class="action-icon-btn btn-like-answer flex items-center gap-1.5 text-xs" data-id="${ans.id}">
                                        <i class="bi bi-heart"></i>
                                        <span class="font-bold text-[11px]">${ans.likes_count > 0 ? `Thích (${ans.likes_count})` : 'Thích'}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            footerHtml = `
                <!-- Likes / Share buttons -->
                <div class="flex items-center gap-5 mt-4 text-slate-500 border-b border-slate-50 pb-3">
                    <button class="action-icon-btn btn-like-question flex items-center gap-1.5 text-sm" data-id="${q.id}">
                        <i class="bi bi-heart"></i>
                        <span class="text-[12px] font-bold q-like-count">${q.likes_count || 'Thích'}</span>
                    </button>
                    
                    <button class="action-icon-btn btn-comment-focus flex items-center gap-1.5 text-sm">
                        <i class="bi bi-chat"></i>
                        <span class="text-[12px] font-bold">${q.answers ? q.answers.length : 0}</span>
                    </button>
                    
                    <button class="action-icon-btn btn-share-post flex items-center text-sm" data-id="${q.id}">
                        <i class="bi bi-share"></i>
                    </button>
                </div>

                <!-- Nested Replies -->
                <div class="replies-list">
                    ${repliesHtml}
                </div>

                <!-- Inline Reply Input -->
                ${isLoggedIn ? `
                <div class="flex gap-4 mt-5 pt-4 border-t border-slate-100 relative z-20">
                    <div class="flex justify-center flex-shrink-0 w-12">
                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shadow-xs">
                            <i class="bi bi-person-fill text-slate-400 text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 flex gap-2">
                        <input type="text" class="ans_content flex-1 bg-slate-50 hover:bg-slate-100/75 focus:bg-white border border-slate-100 rounded-full px-4.5 py-2 text-[13px] placeholder-slate-400 focus:outline-none focus:border-slate-350 transition-all" placeholder="Viết phản hồi của bạn..." data-qid="${q.id}">
                        <button class="btn-post-answer bg-primary text-white hover:bg-slate-800 rounded-full px-5 py-2 text-[11px] font-bold transition-all" data-qid="${q.id}">Gửi</button>
                    </div>
                </div>
                ` : `
                <div class="text-center py-3 mt-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <a href="/login?redirect=/qa" class="text-xs font-bold text-slate-500 hover:text-black">Đăng nhập để bình luận cuộc thảo luận này</a>
                </div>
                `}
            `;
        } else {
            // Feed list view: Hide likes, comments count, replies. Only show single "Đọc thảo luận" button
            footerHtml = `
                <div class="flex justify-end items-center mt-4 pt-3 border-t border-slate-50">
                    <button class="btn-view-details flex items-center gap-1 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-black font-bold text-[11.5px] px-4.5 py-2 rounded-full transition-colors">
                        Đọc thảo luận <i class="bi bi-chevron-right text-[10px]"></i>
                    </button>
                </div>
            `;
        }

        div.innerHTML = `
            ${(isDetailedView && hasReplies) ? '<div class="thread-line"></div>' : ''}
            
            <div class="flex gap-4">
                <!-- Left Avatar -->
                <div class="flex flex-col items-center flex-shrink-0 w-12">
                    <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shadow-xs">
                        <i class="bi bi-person-fill text-slate-400 text-2xl"></i>
                    </div>
                </div>
                
                <!-- Right Body -->
                <div class="flex-1 min-w-0">
                    <!-- Title & Time -->
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-bold text-[14.5px] text-slate-900 hover:underline cursor-pointer btn-view-details">${escapeHtml(q.author_name)}</span>
                        <div class="flex items-center gap-1">
                            ${tagHtml}
                            <span class="text-[12px] text-slate-400">${timeString}</span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    ${contentHtml}
                    
                    <!-- Image -->
                    ${attachmentHtml}
                    
                    <!-- Footer Block -->
                    ${footerHtml}
                </div>
            </div>
        `;

        return div;
    }

    // Color presets select handler
    document.querySelectorAll('.btn-bg-preset').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const bgClass = this.dataset.bg;
            
            editorBgWrapper.className = 'rounded-2xl transition-all duration-350 p-0';
            postContent.className = 'w-full bg-transparent text-[15px] placeholder-slate-400 focus:outline-none resize-none py-2 px-1 leading-relaxed transition-all duration-300';
            
            if (bgClass) {
                editorBgWrapper.classList.add(bgClass, 'p-6', 'min-h-[160px]', 'flex', 'items-center', 'justify-center', 'text-center');
                postContent.classList.add('text-white', 'font-extrabold', 'text-base', 'md:text-lg', 'text-center', 'placeholder-white/60');
                currentBgStyle = bgClass;
                
                if (btnRemoveImage) btnRemoveImage.click();
            } else {
                currentBgStyle = '';
            }
        });
    });

    // Format Time Helper
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

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Photo Attachments handlers
    if (btnTriggerFile && fileInput) {
        btnTriggerFile.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (file) {
                selectedFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                    
                    const defaultBgBtn = document.querySelector('.btn-bg-preset[data-bg=""]');
                    if (defaultBgBtn) defaultBgBtn.click();
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (btnRemoveImage) {
        btnRemoveImage.addEventListener('click', (e) => {
            e.preventDefault();
            selectedFile = null;
            fileInput.value = '';
            imagePreview.src = '';
            imagePreviewContainer.classList.add('hidden');
        });
    }

    // Submit Question from modal
    if (btnPost && postContent) {
        btnPost.addEventListener('click', () => {
            const content = postContent.value.trim();
            const tagValue = postTag ? postTag.value : '';

            if (!content) {
                alert('Vui lòng nhập nội dung thắc mắc');
                return;
            }

            btnPost.disabled = true;
            btnPost.textContent = 'Đang đăng...';

            const formData = new FormData();
            formData.append('action', 'post_question');
            formData.append('content', content);
            formData.append('tags', tagValue);
            formData.append('bg_style', currentBgStyle);
            if (selectedFile) {
                formData.append('image', selectedFile);
            }

            fetch('/api/qa_action', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                btnPost.disabled = false;
                btnPost.textContent = 'Đăng câu hỏi';
                if (res.status === 'success') {
                    postContent.value = '';
                    if (postTag) postTag.value = '';
                    
                    const defaultBgBtn = document.querySelector('.btn-bg-preset[data-bg=""]');
                    if (defaultBgBtn) defaultBgBtn.click();
                    if (btnRemoveImage) btnRemoveImage.click();
                    
                    hidePostModal(); // Hide modal
                    loadFeed(); // Reload list
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                btnPost.disabled = false;
                btnPost.textContent = 'Đăng câu hỏi';
            });
        });
    }

    // Sidebar Tag Filters
    document.querySelectorAll('.tag-badge').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            document.querySelectorAll('.tag-badge').forEach(b => {
                b.classList.remove('active', 'bg-slate-50', 'bg-slate-100', 'bg-primary', 'text-white');
            });
            
            this.classList.add('active');
            if (this.dataset.tag) {
                this.classList.add('bg-primary', 'text-white');
            }
            
            selectedTag = this.dataset.tag;
            viewingQuestionId = null;
            detailsBackBar.classList.add('hidden');
            
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.pushState({}, '', cleanUrl);

            loadFeed();
        });
    });

    // Close Details view
    if (btnCloseDetails) {
        btnCloseDetails.addEventListener('click', () => {
            viewingQuestionId = null;
            detailsBackBar.classList.add('hidden');
            
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.pushState({}, '', cleanUrl);

            loadFeed();
        });
    }

    // Click Delegation inside feed area
    feedContainer.addEventListener('click', (e) => {
        if (e.target.closest('.btn-comment-focus')) {
            const card = e.target.closest('.threads-card');
            const input = card.querySelector('.ans_content');
            if (input) input.focus();
        }

        if (e.target.closest('.btn-view-details')) {
            const card = e.target.closest('.threads-card');
            const qid = card.dataset.id;
            viewingQuestionId = parseInt(qid);
            detailsBackBar.classList.remove('hidden');

            const newUrl = window.location.origin + window.location.pathname + '?qid=' + qid;
            window.history.pushState({ qid: qid }, '', newUrl);
            
            loadFeed();
        }

        if (e.target.closest('.btn-tag-filter')) {
            const tagBtn = e.target.closest('.btn-tag-filter');
            const tag = tagBtn.dataset.tag;
            
            const matchedBadge = Array.from(document.querySelectorAll('.tag-badge')).find(b => b.dataset.tag === tag);
            if (matchedBadge) {
                matchedBadge.click();
            } else {
                selectedTag = tag;
                loadFeed();
            }
        }

        if (e.target.closest('.btn-share-post')) {
            const btn = e.target.closest('.btn-share-post');
            const qid = btn.dataset.id;
            const shareUrl = window.location.origin + window.location.pathname + '?qid=' + qid;
            
            navigator.clipboard.writeText(shareUrl).then(() => {
                const tooltip = document.createElement('div');
                tooltip.className = 'fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-primary text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg z-50 animate-bounce';
                tooltip.textContent = 'Đã sao chép liên kết câu hỏi này!';
                document.body.appendChild(tooltip);
                setTimeout(() => tooltip.remove(), 2500);
            }).catch(err => {
                alert('Không thể sao chép liên kết: ' + err);
            });
        }

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
                    btn.innerHTML = `<i class="bi bi-heart-fill text-red-500"></i> <span class="font-bold text-[11px]">(${res.likes})</span>`;
                    btn.classList.add('liked');
                }
            });
        }

        if (e.target.closest('.btn-post-answer')) {
            const btn = e.target.closest('.btn-post-answer');
            const qid = btn.dataset.qid;
            const card = btn.closest('.threads-card');
            postAnswer(card, qid);
        }
    });

    feedContainer.addEventListener('keypress', (e) => {
        if (e.target.classList.contains('ans_content') && e.key === 'Enter') {
            const qid = e.target.dataset.qid;
            const card = e.target.closest('.threads-card');
            postAnswer(card, qid);
        }
    });

    function postAnswer(card, qid) {
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
                loadFeed();
            } else {
                alert(res.message);
            }
        });
    }

    // Single post view state
    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.qid) {
            viewingQuestionId = parseInt(e.state.qid);
            detailsBackBar.classList.remove('hidden');
        } else {
            viewingQuestionId = null;
            detailsBackBar.classList.add('hidden');
        }
        loadFeed();
    });

    loadFeed();
});
</script>

<?php require_once 'includes/footer.php'; ?>
