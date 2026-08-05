<?php
/**
 * Q&A Page - Threads Style with Sidebar, Image Uploads, and Details View
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
    .tag-badge {
        transition: all 0.2s ease;
    }
    .tag-badge:hover {
        background-color: #000000;
        color: #ffffff;
    }
    .tag-badge.active {
        background-color: #000000;
        color: #ffffff;
    }
</style>

<div class="min-h-screen bg-white pt-24 pb-12">
    <!-- Centered Container with responsive Sidebar layout -->
    <div class="max-w-5xl mx-auto px-4">
        
        <!-- Header Page Title -->
        <div class="text-center py-6 mb-8 border-b border-slate-100">
            <h1 class="text-2xl font-black tracking-tight text-slate-900 font-display">Hỏi đáp Cộng đồng</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-widest">Bright Threads</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-[220px_1fr] gap-8 items-start">
            
            <!-- Left Sidebar: Tags list -->
            <aside class="sticky top-28 bg-slate-50/50 p-5 rounded-2xl border border-slate-100 hidden md:block">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Chủ đề thảo luận</h3>
                <div class="flex flex-col gap-2" id="sidebar_tags_list">
                    <button class="tag-badge text-left px-3 py-2 rounded-xl text-[13.5px] font-semibold text-slate-600 bg-white hover:shadow-sm border border-slate-100 transition-all active active-all" data-tag="">
                        <i class="bi bi-grid-fill mr-1.5 text-xs text-slate-400"></i> Tất cả chủ đề
                    </button>
                    <button class="tag-badge text-left px-3 py-2 rounded-xl text-[13.5px] font-semibold text-slate-600 bg-white hover:shadow-sm border border-slate-100 transition-all" data-tag="#tuyensinh">
                        #tuyensinh
                    </button>
                    <button class="tag-badge text-left px-3 py-2 rounded-xl text-[13.5px] font-semibold text-slate-600 bg-white hover:shadow-sm border border-slate-100 transition-all" data-tag="#hocphi">
                        #hocphi
                    </button>
                    <button class="tag-badge text-left px-3 py-2 rounded-xl text-[13.5px] font-semibold text-slate-600 bg-white hover:shadow-sm border border-slate-100 transition-all" data-tag="#visanhat">
                        #visanhat
                    </button>
                    <button class="tag-badge text-left px-3 py-2 rounded-xl text-[13.5px] font-semibold text-slate-600 bg-white hover:shadow-sm border border-slate-100 transition-all" data-tag="#vieclam">
                        #vieclam
                    </button>
                    <button class="tag-badge text-left px-3 py-2 rounded-xl text-[13.5px] font-semibold text-slate-600 bg-white hover:shadow-sm border border-slate-100 transition-all" data-tag="#nhatngu">
                        #nhatngu
                    </button>
                    <button class="tag-badge text-left px-3 py-2 rounded-xl text-[13.5px] font-semibold text-slate-600 bg-white hover:shadow-sm border border-slate-100 transition-all" data-tag="#cuocsong">
                        #cuocsong
                    </button>
                </div>
            </aside>

            <!-- Right side: Create question and feed -->
            <div class="max-w-[620px] w-full mx-auto md:mx-0">
                
                <!-- Tags carousel for mobile view -->
                <div class="md:hidden flex gap-2 overflow-x-auto pb-4 mb-4 select-none scrollbar-hide" id="mobile_tags_list">
                    <button class="tag-badge shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-600 bg-slate-100 active active-all" data-tag="">Tất cả</button>
                    <button class="tag-badge shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-600 bg-slate-100" data-tag="#tuyensinh">#tuyensinh</button>
                    <button class="tag-badge shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-600 bg-slate-100" data-tag="#hocphi">#hocphi</button>
                    <button class="tag-badge shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-600 bg-slate-100" data-tag="#visanhat">#visanhat</button>
                    <button class="tag-badge shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-600 bg-slate-100" data-tag="#vieclam">#vieclam</button>
                    <button class="tag-badge shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-600 bg-slate-100" data-tag="#nhatngu">#nhatngu</button>
                    <button class="tag-badge shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-600 bg-slate-100" data-tag="#cuocsong">#cuocsong</button>
                </div>

                <!-- Create Post Section -->
                <?php if ($is_logged_in): ?>
                <div class="py-4 border-b border-slate-100 mb-6 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                    <div class="flex gap-3">
                        <!-- User Avatar -->
                        <div class="w-11 h-11 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-200 shadow-sm">
                            <i class="bi bi-person-fill text-slate-400 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-[14px] text-slate-800 mb-1"><?= htmlspecialchars($current_user_name) ?></div>
                            <textarea id="post_content" rows="2" placeholder="Bạn đang thắc mắc điều gì về du học Nhật Bản?..." class="threads-input w-full bg-transparent text-[15px] placeholder-slate-400 focus:outline-none resize-none py-1"></textarea>
                            
                            <!-- Image Preview Area -->
                            <div id="image_preview_container" class="hidden relative mt-2 rounded-2xl overflow-hidden max-h-60 border border-slate-100">
                                <img id="image_preview" src="" class="w-full h-full object-cover">
                                <button id="btn_remove_image" class="absolute top-2 right-2 bg-black/60 hover:bg-black/80 text-white rounded-full p-1.5 w-7 h-7 flex items-center justify-center transition-colors">
                                    <i class="bi bi-x-lg text-xs"></i>
                                </button>
                            </div>

                            <!-- Post Options & Publish -->
                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-100">
                                <div class="flex items-center gap-3">
                                    <!-- Image upload trigger -->
                                    <button id="btn_trigger_file" class="text-slate-400 hover:text-black transition-colors" title="Đính kèm ảnh">
                                        <i class="bi bi-image text-lg"></i>
                                    </button>
                                    <input type="file" id="file_input" class="hidden" accept="image/*">
                                    
                                    <!-- Tag Selector -->
                                    <select id="post_tag" class="bg-transparent border border-slate-200 text-xs font-semibold text-slate-500 rounded-full px-2 py-1 focus:outline-none focus:border-slate-400">
                                        <option value="">Chọn chủ đề</option>
                                        <option value="#tuyensinh">#tuyensinh</option>
                                        <option value="#hocphi">#hocphi</option>
                                        <option value="#visanhat">#visanhat</option>
                                        <option value="#vieclam">#vieclam</option>
                                        <option value="#nhatngu">#nhatngu</option>
                                        <option value="#cuocsong">#cuocsong</option>
                                    </select>
                                </div>
                                <button id="btn_post_question" class="bg-black text-white font-bold text-xs px-5 py-2 rounded-full hover:bg-slate-850 active:scale-95 transition-all shadow-sm">Đăng bài</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-slate-50 rounded-2xl p-6 mb-8 text-center border border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 mb-1">Gửi thắc mắc của bạn</h3>
                    <p class="text-xs text-slate-500 mb-4">Đăng nhập để đặt câu hỏi. Ban quản trị và cộng đồng sẽ hỗ trợ bạn.</p>
                    <a href="/login?redirect=/qa" class="inline-flex bg-black text-white font-bold text-xs px-6 py-2.5 rounded-full hover:bg-slate-850 active:scale-95 transition-all shadow-sm">Đăng nhập ngay</a>
                </div>
                <?php endif; ?>

                <!-- Back button from details view -->
                <div id="details_back_bar" class="hidden items-center gap-2 mb-4">
                    <button id="btn_close_details" class="flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-black py-1 px-3 bg-slate-100 rounded-full transition-colors">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách
                    </button>
                </div>

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

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedContainer = document.getElementById('feed_container');
    const loadingIndicator = document.getElementById('feed_loading');
    const btnPost = document.getElementById('btn_post_question');
    const postContent = document.getElementById('post_content');
    const postTag = document.getElementById('post_tag');
    const fileInput = document.getElementById('file_input');
    const btnTriggerFile = document.getElementById('btn_trigger_file');
    const imagePreviewContainer = document.getElementById('image_preview_container');
    const imagePreview = document.getElementById('image_preview');
    const btnRemoveImage = document.getElementById('btn_remove_image');
    
    const detailsBackBar = document.getElementById('details_back_bar');
    const btnCloseDetails = document.getElementById('btn_close_details');

    // Authentication & Authorization Context
    const isLoggedIn = <?= $is_logged_in ? 'true' : 'false' ?>;
    const isAdmin = <?= $is_admin ? 'true' : 'false' ?>;
    const currentUserName = <?= json_encode($current_user_name) ?>;

    let selectedTag = '';
    let selectedFile = null;
    let viewingQuestionId = null;

    // Check query param 'qid' on load to view details directly
    const urlParams = new URLSearchParams(window.location.search);
    const qidParam = urlParams.get('qid');
    if (qidParam) {
        viewingQuestionId = parseInt(qidParam);
        detailsBackBar.classList.remove('hidden');
    }

    // Load feed
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

    // Load single question details
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
                    <div class="text-center text-slate-400 py-12 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="bi bi-exclamation-triangle text-3xl text-slate-350 mb-2 block"></i>
                        <span class="text-xs font-medium">Không tìm thấy câu hỏi hoặc liên kết này đã bị ẩn.</span>
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error(err);
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        });
    }

    // Render single question layout
    function renderSingleQuestion(q) {
        feedContainer.querySelectorAll('.threads-card').forEach(el => el.remove());
        const card = createThreadCard(q, true);
        feedContainer.appendChild(card);
    }

    // Render all questions
    function renderFeed(questions) {
        feedContainer.querySelectorAll('.threads-card').forEach(el => el.remove());
        const emptyState = feedContainer.querySelector('.empty-state');
        if (emptyState) emptyState.remove();
        
        if (questions.length === 0) {
            feedContainer.insertAdjacentHTML('beforeend', `
                <div class="empty-state text-center text-slate-400 py-12 bg-slate-50 rounded-2xl border border-slate-100">
                    <i class="bi bi-chat-text text-3xl text-slate-300 mb-2 block"></i>
                    <span class="text-xs font-medium">Chưa có cuộc thảo luận nào về chủ đề này.</span>
                </div>
            `);
            return;
        }

        questions.forEach(q => {
            const card = createThreadCard(q, false);
            feedContainer.appendChild(card);
        });
    }

    // Generate HTML for thread card
    function createThreadCard(q, isDetailedView = false) {
        const div = document.createElement('div');
        div.className = 'threads-card py-5 px-1 relative';
        div.dataset.id = q.id;

        const timeString = formatTime(q.created_at);
        const hasReplies = q.answers && q.answers.length > 0;

        // Render attachments/image if exists
        let attachmentHtml = '';
        if (q.image) {
            attachmentHtml = `
                <div class="mt-2.5 rounded-2xl overflow-hidden border border-slate-100 max-h-96 shadow-xs select-none">
                    <img src="/uploads/${q.image}" class="w-full object-cover max-h-96" alt="Ảnh đính kèm" style="max-height: 380px;">
                </div>
            `;
        }

        // Render tag badge if exists
        let tagHtml = '';
        if (q.tags) {
            tagHtml = `<span class="inline-block text-xs font-bold text-slate-400 hover:text-black transition-colors mr-2 cursor-pointer btn-tag-filter" data-tag="${q.tags}">${q.tags}</span>`;
        }

        // Render replies list
        let repliesHtml = '';
        if (hasReplies) {
            repliesHtml = q.answers.map(ans => {
                const isAdminReply = ans.user_role === 'admin' || ans.user_role === 'editor' || ans.author_name.includes('Admin');
                return `
                    <!-- Reply Item -->
                    <div class="flex gap-3 mt-4 relative" data-ans-id="${ans.id}">
                        <!-- Avatar -->
                        <div class="flex flex-col items-center flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shadow-sm">
                                <i class="bi bi-person-fill text-slate-400 text-xs"></i>
                            </div>
                        </div>
                        <!-- Reply Body -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="font-bold text-[13px] text-slate-900">${escapeHtml(ans.author_name)}</span>
                                ${isAdminReply ? `<span class="bg-black text-[9px] font-black text-white px-1.5 py-0.5 rounded-full uppercase tracking-wider scale-90">Admin</span>` : ''}
                                <span class="text-[11px] text-slate-400 ml-auto">${formatTime(ans.created_at)}</span>
                            </div>
                            <div class="text-[14px] text-slate-700 leading-relaxed">${escapeHtml(ans.content)}</div>
                            
                            <!-- Reply Actions -->
                            <div class="flex items-center gap-4 mt-2">
                                <button class="action-icon-btn btn-like-answer flex items-center gap-1 text-[12px]" data-id="${ans.id}">
                                    <i class="bi bi-heart"></i>
                                    <span>Thích ${ans.likes_count > 0 ? `<span class="font-bold">(${ans.likes_count})</span>` : ''}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        div.innerHTML = `
            <!-- Thread connecting line -->
            ${hasReplies ? '<div class="thread-line"></div>' : ''}
            
            <div class="flex gap-3">
                <!-- Left avatar -->
                <div class="flex flex-col items-center flex-shrink-0">
                    <div class="w-11 h-11 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shadow-sm">
                        <i class="bi bi-person-fill text-slate-400 text-lg"></i>
                    </div>
                </div>
                
                <!-- Right Post Body -->
                <div class="flex-1 min-w-0">
                    <!-- Username & Time -->
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-[14px] text-slate-900 hover:underline cursor-pointer btn-view-details">${escapeHtml(q.author_name)}</span>
                        <div class="flex items-center gap-2">
                            ${tagHtml}
                            <span class="text-[12px] text-slate-400">${timeString}</span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="text-[14.5px] text-slate-800 leading-relaxed mb-1 whitespace-pre-wrap cursor-pointer btn-view-details">${escapeHtml(q.content)}</div>
                    
                    <!-- Attachment image -->
                    ${attachmentHtml}
                    
                    <!-- Actions -->
                    <div class="flex items-center gap-5 py-3 text-slate-500">
                        <button class="action-icon-btn btn-like-question flex items-center gap-1.5 text-sm" data-id="${q.id}">
                            <i class="bi bi-heart"></i>
                            <span class="text-[12px] font-semibold q-like-count">${q.likes_count || 'Thích'}</span>
                        </button>
                        
                        <button class="action-icon-btn btn-comment-focus flex items-center gap-1.5 text-sm">
                            <i class="bi bi-chat"></i>
                            <span class="text-[12px] font-semibold">${q.answers ? q.answers.length : 0}</span>
                        </button>
                        
                        <button class="action-icon-btn btn-share-post flex items-center text-sm" data-id="${q.id}">
                            <i class="bi bi-share"></i>
                        </button>
                    </div>

                    <!-- Replies area -->
                    <div class="replies-list">
                        ${repliesHtml}
                    </div>

                    <!-- Inline Reply Input -->
                    ${isLoggedIn ? `
                    <div class="flex gap-2.5 mt-4 pt-3 border-t border-slate-50">
                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center overflow-hidden shadow-xs">
                            <i class="bi bi-person-fill text-slate-400 text-xs"></i>
                        </div>
                        <div class="flex-1 flex gap-2">
                            <input type="text" class="ans_content flex-1 bg-slate-50 hover:bg-slate-100/75 focus:bg-white border border-slate-100 rounded-full px-4 py-1.5 text-[13px] placeholder-slate-400 focus:outline-none focus:border-slate-300 transition-all" placeholder="Viết phản hồi..." data-qid="${q.id}">
                            <button class="btn-post-answer bg-black text-white hover:bg-slate-800 rounded-full px-4 py-1 text-[11px] font-bold transition-all" data-qid="${q.id}">Gửi</button>
                        </div>
                    </div>
                    ` : `
                    <div class="text-center py-2.5 mt-2 bg-slate-50 rounded-xl border border-slate-100">
                        <a href="/login?redirect=/qa" class="text-[11.5px] font-bold text-slate-500 hover:text-black">Đăng nhập để tham gia cuộc trò chuyện</a>
                    </div>
                    `}
                </div>
            </div>
        `;

        return div;
    }

    // Relative Time Helper
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

    // Image Upload Handlers
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

    // Create New Post (Ajax with multipart/form-data for file upload)
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
                btnPost.textContent = 'Đăng bài';
                if (res.status === 'success') {
                    postContent.value = '';
                    if (postTag) postTag.value = '';
                    // Reset image preview
                    if (btnRemoveImage) btnRemoveImage.click();
                    loadFeed();
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

    // Sidebar & Mobile Tags Filter click
    document.querySelectorAll('.tag-badge').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update UI state
            document.querySelectorAll('.tag-badge').forEach(b => {
                b.classList.remove('active', 'bg-black', 'text-white', 'bg-slate-100');
                if (b.classList.contains('active-all')) {
                    // Reset styling to original
                }
            });
            
            this.classList.add('active');
            if (this.dataset.tag) {
                this.classList.add('bg-black', 'text-white');
            }
            
            selectedTag = this.dataset.tag;
            viewingQuestionId = null; // Return to list view
            detailsBackBar.classList.add('hidden');
            
            // Clean qid from URL history
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.pushState({}, '', cleanUrl);

            loadFeed();
        });
    });

    // Close Details View and return to list
    if (btnCloseDetails) {
        btnCloseDetails.addEventListener('click', () => {
            viewingQuestionId = null;
            detailsBackBar.classList.add('hidden');
            
            // Clean qid from URL history
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.pushState({}, '', cleanUrl);

            loadFeed();
        });
    }

    // Click delegation for feed interaction
    feedContainer.addEventListener('click', (e) => {
        // Focus reply comment box
        if (e.target.closest('.btn-comment-focus')) {
            const card = e.target.closest('.threads-card');
            const input = card.querySelector('.ans_content');
            if (input) input.focus();
        }

        // View single thread details
        if (e.target.closest('.btn-view-details')) {
            const card = e.target.closest('.threads-card');
            const qid = card.dataset.id;
            viewingQuestionId = parseInt(qid);
            detailsBackBar.classList.remove('hidden');

            // Push state history to include ?qid=ID
            const newUrl = window.location.origin + window.location.pathname + '?qid=' + qid;
            window.history.pushState({ qid: qid }, '', newUrl);
            
            loadFeed();
        }

        // Click tag inside post to filter
        if (e.target.closest('.btn-tag-filter')) {
            const tagBtn = e.target.closest('.btn-tag-filter');
            const tag = tagBtn.dataset.tag;
            
            // Find active state badge and trigger click
            const matchedBadge = Array.from(document.querySelectorAll('.tag-badge')).find(b => b.dataset.tag === tag);
            if (matchedBadge) {
                matchedBadge.click();
            } else {
                selectedTag = tag;
                loadFeed();
            }
        }

        // Copy Share Link
        if (e.target.closest('.btn-share-post')) {
            const btn = e.target.closest('.btn-share-post');
            const qid = btn.dataset.id;
            const shareUrl = window.location.origin + window.location.pathname + '?qid=' + qid;
            
            navigator.clipboard.writeText(shareUrl).then(() => {
                // Temporary tooltip alert
                const tooltip = document.createElement('div');
                tooltip.className = 'fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-black text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg z-50 animate-bounce';
                tooltip.textContent = 'Đã sao chép liên kết câu hỏi này!';
                document.body.appendChild(tooltip);
                setTimeout(() => tooltip.remove(), 2500);
            }).catch(err => {
                alert('Không thể sao chép liên kết: ' + err);
            });
        }

        // Like a Question
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

        // Like a specific Reply/Answer
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

        // Post answer
        if (e.target.closest('.btn-post-answer')) {
            const btn = e.target.closest('.btn-post-answer');
            const qid = btn.dataset.qid;
            const card = btn.closest('.threads-card');
            postAnswer(card, qid);
        }
    });

    // Enter to post answer
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

    // Listen to history popstate to allow back button navigation of single thread
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

    // Initial Load of feed
    loadFeed();
});
</script>

<?php require_once 'includes/footer.php'; ?>
