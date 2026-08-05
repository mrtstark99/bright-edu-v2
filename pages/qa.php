<?php
/**
 * Q&A Page - Optimized Threads Style with Popup Post Creator & Details View Focus
 */
$page_title = 'Góc chia sẻ kiến thức du học - Bright Education';
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
        top: 68px;
        bottom: 24px;
        left: 47px;
        width: 2px;
        background: linear-gradient(to bottom, #e2e8f0 0%, #f8fafc 100%);
        border-radius: 99px;
        z-index: 1;
    }
    .threads-card {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 2;
        position: relative;
        cursor: pointer;
    }
    .threads-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px -4px rgba(1, 53, 103, 0.08);
        background-color: rgba(248, 250, 252, 0.5);
    }
    .threads-card.active {
        border-color: #0d243e !important;
        box-shadow: 0 12px 32px -4px rgba(1, 53, 103, 0.08) !important;
        background-color: #f8fafc;
    }
    .tag-badge {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .tag-badge:hover {
        background-color: #f1f5f9;
        color: #0d243e;
        transform: translateY(-1px);
    }
    .tag-badge.active {
        background: #0d243e !important;
        color: #ffffff !important;
        border-color: #0d243e !important;
        box-shadow: 0 4px 12px rgba(13, 36, 62, 0.15) !important;
    }

    /* Instagram-style Left Sidebar Menu */
    .insta-menu-btn {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        align-self: flex-start;
        padding: 8px 12px;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background: transparent;
        cursor: pointer;
        border: none;
    }
    .insta-menu-btn:hover {
        color: #0f172a;
        transform: translateX(4px);
    }
    .insta-menu-btn.active {
        background-color: transparent !important;
        color: #0d243e !important;
        font-weight: 700 !important;
        border-bottom: 2px solid #0d243e !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        padding-bottom: 4px !important;
    }

    /* Sticky details panel with custom scrollbar */
    .details-panel-sticky {
        position: sticky;
        top: 90px;
        max-height: calc(100vh - 110px);
        overflow-y: auto;
    }
    .details-panel-sticky::-webkit-scrollbar {
        width: 6px;
    }
    .details-panel-sticky::-webkit-scrollbar-track {
        background: transparent;
    }
    .details-panel-sticky::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 99px;
    }

    /* Skeleton Loading Animations */
    .skeleton-avatar {
        width: 48px;
        height: 48px;
        border-radius: 9999px;
        background: #e2e8f0;
    }
    .skeleton-line {
        height: 12px;
        background: #e2e8f0;
        border-radius: 4px;
    }
    .skeleton-line.short { width: 35%; }
    .skeleton-line.medium { width: 65%; }
    .skeleton-line.long { width: 100%; }
    
    .skeleton-card {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem 1.25rem;
        display: flex;
        gap: 1rem;
        position: relative;
        animation: pulse 1.8s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .4; }
    }

    /* Social actions bar */
    .social-action-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 15px;
        border-radius: 99px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .social-action-btn:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }
    .social-action-btn.liked {
        color: #ef4444;
    }
    .social-action-btn.liked:hover {
        background-color: #fef2f2;
    }
    .social-action-btn.comment-btn:hover {
        color: #3b82f6;
        background-color: #eff6ff;
    }
    .social-action-btn.share-btn:hover {
        color: #10b981;
        background-color: #ecfdf5;
    }
    .social-action-btn i {
        font-size: 15px;
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .social-action-btn:hover i {
        transform: scale(1.15);
    }
    .social-action-btn.liked i {
        animation: heartBeat 0.35s ease-in-out;
    }

    @keyframes heartBeat {
        0% { transform: scale(1); }
        35% { transform: scale(1.3); }
        70% { transform: scale(0.85); }
        100% { transform: scale(1); }
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

    /* Fix alignment for extra large screens to align posts with header content */
    @media (min-width: 1760px) {
        .qa-sidebar {
            position: fixed !important;
            left: calc(50vw - 880px) !important;
            right: auto !important;
            width: 240px !important;
            top: 80px !important;
            bottom: 0 !important;
            z-index: 40;
            border-right: 1px solid #f1f5f9 !important;
            border-left: none !important;
        }
        .qa-main-container {
            max-width: 80rem !important; /* max-w-7xl (1280px) */
            margin-left: auto !important;
            margin-right: auto !important;
            width: 100% !important;
        }
    }
</style>

<main class="pt-20 bg-white min-h-screen">
    <section class="w-full border-t border-slate-100">
        
        <div class="flex flex-col md:flex-row min-h-[calc(100vh-80px)] qa-layout-wrapper">
            
            <!-- Left Sidebar -->
            <aside class="qa-sidebar w-[240px] shrink-0 border-r border-slate-100 pl-6 pr-5 py-6 hidden md:block sticky top-20 max-h-[calc(100vh-80px)] overflow-y-auto">
                <!-- User Profile Card -->
                <?php if ($is_logged_in): ?>
                <div class="flex items-center gap-3 mb-6 px-1">
                    <div class="w-10 h-10 rounded-full bg-primary-600 text-white font-extrabold flex items-center justify-center shadow-xs border border-white uppercase text-sm select-none flex-shrink-0">
                        <span><?= mb_substr($current_user_name, 0, 1, 'UTF-8') ?></span>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-[13px] text-slate-900 truncate leading-tight"><?= htmlspecialchars($current_user_name) ?></h4>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">
                            <?= $is_admin ? 'Ban quản trị' : 'Thành viên' ?>
                        </span>
                    </div>
                </div>
                <?php else: ?>
                <div class="border border-slate-100 bg-slate-50/30 p-4 rounded-xl text-center mb-6 mr-1">
                    <div class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center mx-auto mb-2">
                        <i class="bi bi-people text-primary text-sm"></i>
                    </div>
                    <h4 class="font-bold text-xs text-slate-950 mb-0.5">Cộng đồng</h4>
                    <p class="text-[10px] text-slate-500 leading-relaxed mb-3">Đăng nhập để thảo luận cùng Bright Education.</p>
                    <a href="/login?redirect=/qa" class="inline-block w-full py-1 bg-primary text-white rounded-full text-[11px] font-bold hover:bg-slate-800 transition-all shadow-sm">
                        Đăng nhập
                    </a>
                </div>
                <?php endif; ?>

                <!-- Category tags list -->
                <div class="py-2">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                        <div class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></div>
                        <h3 class="text-[11px] font-black uppercase tracking-wider text-slate-400">Danh mục kiến thức</h3>
                    </div>
                    <div class="flex flex-col gap-1" id="sidebar_tags_list">
                        <button class="tag-badge text-left px-3.5 py-2 rounded-xl text-[13px] font-bold text-slate-600 hover:bg-slate-50 border border-transparent transition-all active active-all" data-tag="">
                            <i class="bi bi-grid mr-2 text-xs text-slate-400"></i> Tất cả chủ đề
                        </button>
                        <button class="tag-badge text-left px-3.5 py-2 rounded-xl text-[13px] font-bold text-slate-600 hover:bg-slate-50 border border-transparent transition-all" data-tag="#tuyensinh">
                            <i class="bi bi-mortarboard mr-2 text-xs text-orange-500"></i> #tuyensinh
                        </button>
                        <button class="tag-badge text-left px-3.5 py-2 rounded-xl text-[13px] font-bold text-slate-600 hover:bg-slate-50 border border-transparent transition-all" data-tag="#hocphi">
                            <i class="bi bi-wallet2 mr-2 text-xs text-emerald-500"></i> #hocphi
                        </button>
                        <button class="tag-badge text-left px-3.5 py-2 rounded-xl text-[13px] font-bold text-slate-600 hover:bg-slate-50 border border-transparent transition-all" data-tag="#visanhat">
                            <i class="bi bi-passport mr-2 text-xs text-blue-500"></i> #visanhat
                        </button>
                        <button class="tag-badge text-left px-3.5 py-2 rounded-xl text-[13px] font-bold text-slate-600 hover:bg-slate-50 border border-transparent transition-all" data-tag="#vieclam">
                            <i class="bi bi-briefcase mr-2 text-xs text-amber-500"></i> #vieclam
                        </button>
                        <button class="tag-badge text-left px-3.5 py-2 rounded-xl text-[13px] font-bold text-slate-600 hover:bg-slate-50 border border-transparent transition-all" data-tag="#nhatngu">
                            <i class="bi bi-translate mr-2 text-xs text-indigo-500"></i> #nhatngu
                        </button>
                        <button class="tag-badge text-left px-3.5 py-2 rounded-xl text-[13px] font-bold text-slate-600 hover:bg-slate-50 border border-transparent transition-all" data-tag="#cuocsong">
                            <i class="bi bi-heart-pulse mr-2 text-xs text-rose-500"></i> #cuocsong
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Center-aligned Container for Feed + Details -->
            <div class="flex-1 flex justify-center qa-main-container">
                <div class="w-full max-w-7xl flex flex-col md:flex-row min-h-full">
                    
                    <!-- Middle Feed Column (List of posts) -->
                    <div id="feed_column" class="w-full md:w-[450px] shrink-0 border-r border-slate-100 p-5 flex flex-col gap-5">
                        
                        <!-- Title & tag list for mobile -->
                        <div class="md:hidden">
                            <h2 class="text-xl font-black text-primary font-display mb-3">Góc Chia sẻ Kiến thức</h2>
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

                        <!-- One post creation trigger button (Only for Admin/Editor) -->
                        <?php if ($is_admin): ?>
                        <div class="bg-slate-50/50 p-4 border border-slate-100 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-600 text-white font-extrabold flex items-center justify-center shadow-xs border border-white uppercase text-sm select-none flex-shrink-0">
                                    <span><?= mb_substr($current_user_name, 0, 1, 'UTF-8') ?></span>
                                </div>
                                <button id="btn_open_post_modal" class="flex-1 bg-white hover:bg-slate-50 border border-slate-200 text-slate-500 rounded-full px-5 py-2.5 text-left text-[13px] font-medium flex items-center justify-between transition-all duration-200 shadow-xs focus:outline-none">
                                    <span>Đăng bài chia sẻ kiến thức mới...</span>
                                    <i class="bi bi-pencil-square text-slate-400 text-base"></i>
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                                <button onclick="document.getElementById('btn_open_post_modal').click(); document.getElementById('btn_trigger_file').click();" class="flex items-center gap-2 px-3 py-1.5 hover:bg-white rounded-xl transition-colors text-xs font-semibold text-slate-500">
                                    <i class="bi bi-image text-emerald-550 text-base"></i>
                                    <span>Đính kèm ảnh</span>
                                </button>
                                <button onclick="document.getElementById('btn_open_post_modal').click(); document.getElementById('post_tag').focus();" class="flex items-center gap-2 px-3 py-1.5 hover:bg-white rounded-xl transition-colors text-xs font-semibold text-slate-500">
                                    <i class="bi bi-tags text-orange-500 text-base"></i>
                                    <span>Chọn chủ đề</span>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Feed Cards -->
                        <div id="feed_container" class="flex flex-col">
                            <!-- Loading Spinner -->
                            <div id="feed_loading" class="text-center py-16 bg-white rounded-2xl border border-slate-100/80">
                                <i class="bi bi-arrow-repeat text-3xl text-primary/30 animate-spin inline-block"></i>
                                <p class="text-slate-400 mt-3 text-xs font-semibold">Đang tải danh sách bài viết...</p>
                            </div>
                        </div>

                    </div>

                    <!-- Right Details Column (Detailed Content of selected post) -->
                    <div id="details_column" class="details-panel-sticky flex-1 p-6 hidden md:block">
                        <!-- Back button to return to list (Visible on mobile/tablet only when viewing details) -->
                        <div id="details_back_bar" class="hidden mb-4">
                            <button id="btn_close_details" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-primary py-2 px-4 bg-white border border-slate-200 rounded-full shadow-soft transition-colors">
                                <i class="bi bi-arrow-left"></i> Quay lại danh sách bài viết
                            </button>
                        </div>

                        <!-- Single Post Detailed View Container -->
                        <div id="details_container" class="max-w-3xl">
                             <!-- Empty state / Selected post details -->
                             <div class="flex flex-col items-center justify-center text-center py-24 text-slate-400 select-none">
                                 <i class="bi bi-journal-text text-5xl mb-4 text-slate-200"></i>
                                 <p class="text-xs font-semibold text-slate-500">Chọn một bài viết ở danh sách bên trái để xem nội dung chi tiết và bình luận.</p>
                             </div>
                        </div>
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
    <div id="post_modal_backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300"></div>
    
    <!-- Modal Box -->
    <div id="post_modal_card" class="bg-white w-full max-w-[540px] rounded-[2rem] p-6 shadow-hard relative z-10 mx-4 transform scale-95 opacity-0 transition-all duration-300">
        <!-- Close Button top right -->
        <button id="btn_close_post_modal" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-black transition-colors">
            <i class="bi bi-x-lg text-xs"></i>
        </button>

        <h3 class="text-base font-black text-slate-900 font-display mb-4 pb-3 border-b border-slate-100">Chia sẻ kiến thức du học</h3>
        
        <div class="flex gap-4">
            <!-- User Avatar -->
            <div class="w-12 h-12 rounded-full bg-primary-600 text-white font-extrabold flex items-center justify-center shadow-xs border border-white uppercase text-base select-none flex-shrink-0">
                <span><?= mb_substr($current_user_name, 0, 1, 'UTF-8') ?></span>
            </div>
            <div class="flex-1">
                <div class="font-bold text-[14px] text-slate-900 mb-1"><?= htmlspecialchars($current_user_name) ?></div>
                
                <!-- Background wrapper preview -->
                <div id="editor_bg_wrapper" class="rounded-2xl transition-all duration-350 p-0">
                    <textarea id="post_content" rows="4" placeholder="Viết nội dung chia sẻ kiến thức du học Nhật Bản..." class="w-full bg-transparent text-[15px] placeholder-slate-400 focus:outline-none resize-none py-2 px-1 leading-relaxed transition-all duration-300"></textarea>
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
                    <button id="btn_post_question" class="bg-primary text-white font-bold text-xs px-6 py-2.5 rounded-full hover:bg-slate-800 active:scale-95 transition-all shadow-sm">Đăng bài viết</button>
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
    function getAvatarHtml(name, size = 12) {
        if (!name) name = 'Thành viên';
        const initial = name.trim().charAt(0).toUpperCase();
        
        let hash = 0;
        for (let i = 0; i < name.length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        
        const colors = [
            { bg: '#eff6ff', text: '#1e40af' }, // blue
            { bg: '#ecfdf5', text: '#065f46' }, // emerald
            { bg: '#f5f3ff', text: '#5b21b6' }, // violet
            { bg: '#fdf2f8', text: '#9d174d' }, // pink
            { bg: '#fffbeb', text: '#92400e' }, // amber
            { bg: '#f0fdfa', text: '#115e59' }, // teal
            { bg: '#fff1f2', text: '#9f1239' }, // rose
            { bg: '#f0f9ff', text: '#075985' }  // sky
        ];
        const color = colors[Math.abs(hash) % colors.length];
        
        let sizeClasses = 'w-12 h-12 text-base';
        if (size === 10) sizeClasses = 'w-10 h-10 text-sm';
        if (size === 9) sizeClasses = 'w-9 h-9 text-xs';
        if (size === 8) sizeClasses = 'w-8 h-8 text-[10px]';
        if (size === 12) sizeClasses = 'w-11 h-11 text-base'; // Align w-11 or similar

        return `<div class="${sizeClasses} rounded-full font-bold flex items-center justify-center uppercase select-none flex-shrink-0" style="background-color: ${color.bg}; color: ${color.text}; border: 1px solid rgba(0,0,0,0.03);">
            <span>${initial}</span>
        </div>`;
    }

    // Skeleton screen builder
    function getSkeletonHtml() {
        let html = '';
        for (let i = 0; i < 3; i++) {
            html += `
            <div class="skeleton-card bg-white border border-slate-100/80 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="skeleton-avatar w-10 h-10 rounded-full"></div>
                    <div class="flex-1 space-y-1.5">
                        <div class="skeleton-line short" style="height:10px"></div>
                        <div class="skeleton-line" style="width:25%;height:8px"></div>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="skeleton-line long"></div>
                    <div class="skeleton-line medium"></div>
                </div>
                <div class="flex justify-between items-center pt-3.5 mt-4 border-t border-slate-100">
                    <div class="flex gap-4">
                        <div class="skeleton-line w-12"></div>
                        <div class="skeleton-line w-12"></div>
                    </div>
                    <div class="skeleton-line w-20"></div>
                </div>
            </div>
            `;
        }
        return html;
    }

    function showSkeletonLoader() {
        feedContainer.innerHTML = getSkeletonHtml();
    }

    // Custom styled pill tag
    function getStyledTagHtml(tag) {
        if (!tag) return '';
        const cleanTag = tag.trim().toLowerCase();
        const mapping = {
            '#tuyensinh': 'bg-orange-50/80 text-orange-600 border border-orange-100/50',
            '#hocphi': 'bg-emerald-50/80 text-emerald-600 border border-emerald-100/50',
            '#visanhat': 'bg-blue-50/80 text-blue-600 border border-blue-100/50',
            '#vieclam': 'bg-amber-50/80 text-amber-600 border border-amber-100/50',
            '#nhatngu': 'bg-indigo-50/80 text-indigo-600 border border-indigo-100/50',
            '#cuocsong': 'bg-rose-50/80 text-rose-600 border border-rose-100/50'
        };
        const classes = mapping[cleanTag] || 'bg-slate-100 text-slate-600 border border-slate-200/50';
        return `<span class="inline-block text-[11px] font-bold px-2.5 py-0.5 rounded-full ${classes} hover:scale-102 hover:shadow-xs transition-all cursor-pointer btn-tag-filter" data-tag="${tag}">${tag}</span>`;
    }

    // Get verified / Admin role badge
    function getRoleBadge(name) {
        if (!name) return '';
        const cleanName = name.toLowerCase();
        if (cleanName.includes('admin') || cleanName.includes('editor') || cleanName.includes('bright education')) {
            return `<span class="inline-flex items-center gap-0.5 bg-primary text-[9px] font-black text-white px-2 py-0.5 rounded-full uppercase tracking-wider">
                <i class="bi bi-patch-check-fill text-[9px]"></i> Admin
            </span>`;
        }
        return '';
    }

    // Highlight active card helper
    function highlightActiveCard(qid) {
        document.querySelectorAll('#feed_container .threads-card').forEach(card => {
            if (parseInt(card.dataset.id) === parseInt(qid)) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }

    // CSS Initials Avatar Generator Helper
    function getAvatarHtml(name, size = 12) {
        if (!name) name = 'Thành viên';
        const initial = name.trim().charAt(0).toUpperCase();
        
        // Hash function for colors
        let hash = 0;
        for (let i = 0; i < name.length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        
        const bgColors = [
            'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)', // Blue
            'linear-gradient(135deg, #10b981 0%, #047857 100%)', // Emerald
            'linear-gradient(135deg, #6366f1 0%, #4338ca 100%)', // Indigo
            'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)', // Violet
            'linear-gradient(135deg, #ec4899 0%, #be185d 100%)', // Pink
            'linear-gradient(135deg, #f59e0b 0%, #b45309 100%)', // Amber
            'linear-gradient(135deg, #14b8a6 0%, #0f766e 100%)', // Teal
            'linear-gradient(135deg, #f43f5e 0%, #be123c 100%)', // Rose
            'linear-gradient(135deg, #06b6d4 0%, #0369a1 100%)'  // Cyan
        ];
        const gradient = bgColors[Math.abs(hash) % bgColors.length];
        
        let sizeClasses = 'w-12 h-12 text-[15px]';
        if (size === 10) sizeClasses = 'w-10 h-10 text-sm';
        if (size === 9) sizeClasses = 'w-9 h-9 text-xs';
        if (size === 8) sizeClasses = 'w-8 h-8 text-[10px]';

        return `<div class="${sizeClasses} rounded-full text-white font-extrabold flex items-center justify-center shadow-xs border border-white uppercase select-none flex-shrink-0" style="background: ${gradient};">
            <span>${initial}</span>
        </div>`;
    }

    // Skeleton screen builder
    function getSkeletonHtml() {
        let html = '';
        for (let i = 0; i < 3; i++) {
            html += `
            <div class="skeleton-card">
                <div class="skeleton-avatar"></div>
                <div class="flex-1 space-y-3 py-1">
                    <div class="flex justify-between items-center">
                        <div class="skeleton-line short"></div>
                        <div class="skeleton-line w-16"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="skeleton-line long"></div>
                        <div class="skeleton-line medium"></div>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-slate-50 mt-4">
                        <div class="skeleton-line w-20"></div>
                        <div class="skeleton-line w-20"></div>
                    </div>
                </div>
            </div>
            `;
        }
        return html;
    }

    function showSkeletonLoader() {
        feedContainer.innerHTML = getSkeletonHtml();
    }

    // Custom styled pill tag
    function getStyledTagHtml(tag) {
        if (!tag) return '';
        const cleanTag = tag.trim().toLowerCase();
        const mapping = {
            '#tuyensinh': 'bg-orange-50/80 text-orange-600 border border-orange-100/50',
            '#hocphi': 'bg-emerald-50/80 text-emerald-600 border border-emerald-100/50',
            '#visanhat': 'bg-blue-50/80 text-blue-600 border border-blue-100/50',
            '#vieclam': 'bg-amber-50/80 text-amber-600 border border-amber-100/50',
            '#nhatngu': 'bg-indigo-50/80 text-indigo-600 border border-indigo-100/50',
            '#cuocsong': 'bg-rose-50/80 text-rose-600 border border-rose-100/50'
        };
        const classes = mapping[cleanTag] || 'bg-slate-100 text-slate-600 border border-slate-200/50';
        return `<span class="inline-block text-[11px] font-bold px-2.5 py-0.5 rounded-full ${classes} hover:scale-102 hover:shadow-xs transition-all cursor-pointer btn-tag-filter" data-tag="${tag}">${tag}</span>`;
    }

    // Get verified / Admin role badge
    function getRoleBadge(name) {
        if (!name) return '';
        const cleanName = name.toLowerCase();
        if (cleanName.includes('admin') || cleanName.includes('editor') || cleanName.includes('bright education')) {
            return `<span class="inline-flex items-center gap-0.5 bg-slate-900 text-[9.5px] font-black text-white px-2 py-0.5 rounded-full uppercase tracking-wider scale-90">
                <i class="bi bi-shield-check text-orange-400"></i> Admin
            </span>`;
        }
        return `<span class="bg-slate-50 border border-slate-100 text-[9.5px] font-bold text-slate-500 px-2 py-0.5 rounded-full uppercase tracking-wider scale-90">Thành viên</span>`;
    }

    // Highlight active card helper
    function highlightActiveCard(qid) {
        document.querySelectorAll('#feed_container .threads-card').forEach(card => {
            if (parseInt(card.dataset.id) === parseInt(qid)) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }

    // Load Feed
    function loadFeed() {
        showSkeletonLoader();
        
        let url = '/api/qa_action?action=get_feed';
        if (selectedTag) {
            url += '&tag=' + encodeURIComponent(selectedTag);
        }

        fetch(url)
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                renderFeed(res.data);
                
                const isDesktop = window.innerWidth >= 768;
                if (viewingQuestionId) {
                    selectPost(viewingQuestionId, false);
                } else if (isDesktop && res.data.length > 0) {
                    // Auto select first post on desktop if none selected
                    selectPost(res.data[0].id, false);
                } else if (isDesktop) {
                    showEmptyDetails();
                }
            } else {
                console.error('Lỗi khi tải dữ liệu');
            }
        })
        .catch(err => {
            console.error(err);
            feedContainer.innerHTML = `
                <div class="text-center py-16 bg-white rounded-[2rem] border border-slate-100 shadow-soft">
                    <p class="text-red-500 font-semibold">Đã xảy ra lỗi khi tải luồng thảo luận. Vui lòng thử lại sau.</p>
                </div>
            `;
        });
    }

    // Load Single Question Details
    function loadSingleQuestion(qid, showSkeleton = true) {
        const detailsContainer = document.getElementById('details_container');
        if (showSkeleton) {
            detailsContainer.innerHTML = `
                <div class="text-center py-20 bg-white">
                    <i class="bi bi-arrow-repeat text-3xl text-primary/30 animate-spin inline-block"></i>
                    <p class="text-slate-400 mt-3 text-xs font-semibold">Đang tải nội dung chi tiết...</p>
                </div>
            `;
        }
        
        const params = new URLSearchParams();
        params.append('action', 'get_question');
        params.append('question_id', qid);

        fetch('/api/qa_action', {
            method: 'POST',
            body: params
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                renderSingleQuestion(res.data);
                highlightActiveCard(qid);
            } else {
                detailsContainer.innerHTML = `
                    <div class="text-center text-slate-400 py-16 bg-white select-none">
                        <i class="bi bi-exclamation-triangle text-4xl text-slate-350 mb-3 block"></i>
                        <span class="text-xs font-bold text-slate-500">Bài viết không tồn tại hoặc đã bị gỡ bỏ.</span>
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error(err);
        });
    }

    // Render single question into details panel
    function renderSingleQuestion(q) {
        const detailsContainer = document.getElementById('details_container');
        detailsContainer.innerHTML = '';
        const card = createThreadCard(q, true); // true = Detailed view with comments
        detailsContainer.appendChild(card);
    }

    function showEmptyDetails() {
        const detailsContainer = document.getElementById('details_container');
        detailsContainer.innerHTML = `
             <div class="flex flex-col items-center justify-center text-center py-24 text-slate-400 select-none">
                 <i class="bi bi-journal-text text-5xl mb-4 text-slate-200"></i>
                 <p class="text-xs font-semibold text-slate-500">Chọn một bài viết ở danh sách bên trái để xem nội dung chi tiết và bình luận.</p>
             </div>
        `;
    }

    // Select Post controller (Handles desktop split vs mobile view)
    function selectPost(qid, updateUrlState = true) {
        viewingQuestionId = qid;
        
        if (updateUrlState) {
            const newUrl = window.location.origin + window.location.pathname + '?qid=' + qid;
            window.history.pushState({ qid: qid }, '', newUrl);
        }

        const isDesktop = window.innerWidth >= 768;
        if (!isDesktop) {
            // Hide feed and show details on mobile
            document.getElementById('feed_column').classList.add('hidden');
            const detailsCol = document.getElementById('details_column');
            detailsCol.classList.remove('hidden');
            detailsCol.classList.add('block');
            detailsBackBar.classList.remove('hidden');
        } else {
            // Keep both visible on desktop
            document.getElementById('feed_column').classList.remove('hidden');
            document.getElementById('details_column').classList.remove('hidden');
            detailsBackBar.classList.add('hidden');
        }

        loadSingleQuestion(qid);
    }

    // Render feed
    function renderFeed(questions) {
        feedContainer.innerHTML = '';
        
        if (questions.length === 0) {
            feedContainer.insertAdjacentHTML('beforeend', `
                <div class="empty-state text-center text-slate-400 py-16 bg-white rounded-2xl border border-slate-100 shadow-soft">
                    <i class="bi bi-chat-left-text text-4xl text-slate-200 mb-3 block text-primary/10"></i>
                    <span class="text-xs font-bold text-slate-500">Chưa có bài viết nào ở danh sách này.</span>
                </div>
            `);
            return;
        }

        questions.forEach(q => {
            const card = createThreadCard(q, false); // false = Feed list view
            feedContainer.appendChild(card);
        });
    }

    // Generate HTML for card (Feeds view vs Detailed view logic)
    function createThreadCard(q, isDetailedView = false) {
        const div = document.createElement('div');
        div.className = isDetailedView 
            ? 'detailed-card relative pb-2' 
            : 'threads-card border-b border-slate-100 p-5 relative';
        div.dataset.id = q.id;

        const timeString = formatTime(q.created_at);
        const hasReplies = q.answers && q.answers.length > 0;

        // Image Attachment
        let attachmentHtml = '';
        if (q.image && !q.bg_style) {
            attachmentHtml = `
                <div class="mt-4 rounded-2xl overflow-hidden border border-slate-100 shadow-xs max-h-96 hover:shadow-md transition-shadow duration-300">
                    <img src="/uploads/${q.image}" class="w-full object-cover max-h-96 transition-transform hover:scale-[1.005] duration-300" alt="Ảnh đính kèm">
                </div>
            `;
        }

        // Main Text Content
        let contentHtml = '';
        if (q.bg_style) {
            contentHtml = `
                <div class="${q.bg_style} text-white font-extrabold text-base md:text-lg flex items-center justify-center text-center p-8 rounded-2xl min-h-[220px] shadow-sm leading-relaxed mb-1 whitespace-pre-wrap transition-transform hover:scale-[1.005] duration-200">
                    <span>${escapeHtml(q.content)}</span>
                </div>
            `;
        } else {
            contentHtml = `
                <div class="text-[15px] text-slate-800 leading-relaxed mb-1 whitespace-pre-wrap">${escapeHtml(q.content)}</div>
            `;
        }

        // Tag
        let tagHtml = '';
        if (q.tags) {
            tagHtml = getStyledTagHtml(q.tags);
        }

        // Footer Actions & Comments area
        let footerHtml = '';
        if (isDetailedView) {
            let repliesHtml = '';
            if (hasReplies) {
                repliesHtml = q.answers.map(ans => {
                    const isAdminReply = ans.user_role === 'admin' || ans.user_role === 'editor' || ans.author_name.includes('Admin');
                    const replyAvatar = getAvatarHtml(ans.author_name, 9);
                    return `
                        <!-- Reply Item -->
                        <div class="flex gap-4 mt-5 pt-4 border-t border-slate-50 relative z-10" data-ans-id="${ans.id}">
                             <div class="flex justify-center flex-shrink-0 w-12">
                                 ${replyAvatar}
                             </div>
                             <div class="flex-1 min-w-0">
                                 <div class="flex items-center gap-2 mb-1">
                                     <span class="font-bold text-[13.5px] text-slate-900">${escapeHtml(ans.author_name)}</span>
                                     ${isAdminReply ? `<span class="bg-slate-900 text-[9px] font-black text-white px-2 py-0.5 rounded-full uppercase tracking-wider scale-90 flex items-center gap-0.5"><i class="bi bi-shield-check text-orange-400"></i> Admin</span>` : ''}
                                     <span class="text-[11px] text-slate-400 ml-auto">${formatTime(ans.created_at)}</span>
                                 </div>
                                 <div class="text-[14px] text-slate-700 leading-relaxed">${escapeHtml(ans.content)}</div>
                                 
                                 <div class="flex items-center gap-4 mt-2">
                                     <button class="action-icon-btn btn-like-answer flex items-center gap-1.5 text-xs text-slate-400 hover:text-red-500 transition-colors" data-id="${ans.id}">
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
                <div class="flex items-center justify-between mt-4 text-slate-500 border-b border-slate-50 pb-3">
                     <div class="flex items-center gap-2">
                         <button class="social-action-btn btn-like-question" data-id="${q.id}">
                             <i class="bi bi-heart"></i>
                             <span class="q-like-count">${q.likes_count || '0'} Thích</span>
                         </button>
                         
                         <button class="social-action-btn comment-btn btn-comment-focus">
                             <i class="bi bi-chat"></i>
                             <span>${q.answers ? q.answers.length : 0} Phản hồi</span>
                         </button>
                     </div>
                     
                     <button class="social-action-btn share-btn btn-share-post" data-id="${q.id}" title="Chia sẻ liên kết">
                         <i class="bi bi-share"></i>
                         <span>Chia sẻ</span>
                     </button>
                </div>

                <!-- Nested Replies -->
                <div class="replies-list relative">
                    ${repliesHtml}
                </div>

                <!-- Inline Reply Input -->
                ${isLoggedIn ? `
                <div class="flex gap-4 mt-5 pt-4 border-t border-slate-100 relative z-20">
                    <div class="flex justify-center flex-shrink-0 w-12">
                        ${getAvatarHtml(currentUserName, 10)}
                    </div>
                    <div class="flex-1 flex gap-2">
                        <input type="text" class="ans_content flex-1 bg-slate-50 hover:bg-slate-100/75 focus:bg-white border border-slate-200/60 rounded-full px-4.5 py-2 text-[13px] placeholder-slate-400 focus:outline-none focus:border-slate-350 transition-all shadow-xs" placeholder="Viết phản hồi của bạn..." data-qid="${q.id}">
                        <button class="btn-post-answer bg-primary text-white hover:bg-slate-800 rounded-full px-5 py-2 text-[11px] font-bold transition-all shadow-sm" data-qid="${q.id}">Gửi</button>
                    </div>
                </div>
                ` : `
                <div class="text-center py-4 mt-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <span class="text-xs font-semibold text-slate-500">Bạn cần <a href="/login?redirect=/qa" class="text-primary hover:underline font-bold">đăng nhập</a> hoặc <a href="/register?redirect=/qa" class="text-primary hover:underline font-bold">tạo tài khoản</a> để bình luận bài viết.</span>
                </div>
                `}
            `;
        } else {
            const answersCount = q.answers ? q.answers.length : 0;
            footerHtml = `
                <div class="flex justify-between items-center mt-4 pt-3 border-t border-slate-50">
                    <div class="flex items-center gap-4 text-xs font-bold text-slate-400 select-none">
                        <span class="flex items-center gap-1.5 hover:text-red-500 transition-colors"><i class="bi bi-heart"></i> ${q.likes_count || 0}</span>
                        <span class="flex items-center gap-1.5 hover:text-primary transition-colors"><i class="bi bi-chat"></i> ${answersCount}</span>
                    </div>
                    <button class="btn-view-details flex items-center gap-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-black font-bold text-[11.5px] px-4.5 py-2.5 rounded-full transition-colors shadow-xs">
                        Xem chi tiết <i class="bi bi-chevron-right text-[10px]"></i>
                    </button>
                </div>
            `;
        }

        div.innerHTML = `
            ${(isDetailedView && hasReplies) ? '<div class="thread-line"></div>' : ''}
            
            <div class="flex gap-4">
                <!-- Left Avatar -->
                <div class="flex flex-col items-center flex-shrink-0 w-12">
                    ${getAvatarHtml(q.author_name, 12)}
                </div>
                
                <!-- Right Body -->
                <div class="flex-1 min-w-0">
                    <!-- Title & Time -->
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-[14.5px] text-slate-900">${escapeHtml(q.author_name)}</span>
                            ${getRoleBadge(q.author_name)}
                        </div>
                        <div class="flex items-center gap-2">
                            ${tagHtml}
                            <span class="text-[12px] text-slate-400 font-medium">${timeString}</span>
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
            
            // Toggle active ring styling
            document.querySelectorAll('.btn-bg-preset').forEach(b => b.classList.remove('ring-2', 'ring-primary', 'ring-offset-1'));
            this.classList.add('ring-2', 'ring-primary', 'ring-offset-1');

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

    // Escape HTML string
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
                alert('Vui lòng nhập nội dung chia sẻ');
                return;
            }

            btnPost.disabled = true;
            btnPost.textContent = 'Đang chia sẻ...';

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
                btnPost.textContent = 'Đăng bài viết';
                if (res.status === 'success') {
                    postContent.value = '';
                    if (postTag) postTag.value = '';
                    
                    const defaultBgBtn = document.querySelector('.btn-bg-preset[data-bg=""]');
                    if (defaultBgBtn) defaultBgBtn.click();
                    if (btnRemoveImage) btnRemoveImage.click();
                    
                    hidePostModal(); // Hide modal
                    
                    // Reload feed, select the newly added post
                    fetch('/api/qa_action?action=get_feed')
                    .then(r => r.json())
                    .then(feedRes => {
                        if (feedRes.status === 'success') {
                            renderFeed(feedRes.data);
                            if (feedRes.data.length > 0) {
                                selectPost(feedRes.data[0].id, true);
                            }
                        }
                    });
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                btnPost.disabled = false;
                btnPost.textContent = 'Đăng bài viết';
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
            
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.pushState({}, '', cleanUrl);

            loadFeed();
        });
    });

    // Close Details view (Mobile only back trigger)
    if (btnCloseDetails) {
        btnCloseDetails.addEventListener('click', () => {
            viewingQuestionId = null;
            
            // Show feed, hide details on mobile
            document.getElementById('feed_column').classList.remove('hidden');
            document.getElementById('details_column').classList.add('hidden');
            detailsBackBar.classList.add('hidden');
            
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.pushState({}, '', cleanUrl);
        });
    }

    // Selection Click delegation in feed area
    feedContainer.addEventListener('click', (e) => {
        const card = e.target.closest('.threads-card');
        if (!card) return;

        // Skip selection if clicked inside inline actions/tags
        if (e.target.closest('.btn-tag-filter') || e.target.closest('.action-icon-btn') || e.target.closest('.social-action-btn') || e.target.closest('a')) {
            // Handle tag click to filter
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
            return;
        }

        const qid = card.dataset.id;
        if (qid) {
            selectPost(parseInt(qid), true);
        }
    });

    // Event delegation inside details column
    const detailsCol = document.getElementById('details_column');
    if (detailsCol) {
        detailsCol.addEventListener('click', (e) => {
            if (e.target.closest('.btn-comment-focus')) {
                const input = detailsCol.querySelector('.ans_content');
                if (input) input.focus();
            }

            if (e.target.closest('.btn-share-post')) {
                const btn = e.target.closest('.btn-share-post');
                const qid = btn.dataset.id;
                const shareUrl = window.location.origin + window.location.pathname + '?qid=' + qid;
                
                navigator.clipboard.writeText(shareUrl).then(() => {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-primary text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-lg z-50 animate-bounce';
                    tooltip.textContent = 'Đã sao chép liên kết chia sẻ!';
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
                        // Refresh details view locally
                        loadSingleQuestion(qid, false);
                        // Refresh feed list counts in background
                        syncFeedListSilent();
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
                        if (viewingQuestionId) {
                            loadSingleQuestion(viewingQuestionId, false);
                        }
                    }
                });
            }

            if (e.target.closest('.btn-post-answer')) {
                const btn = e.target.closest('.btn-post-answer');
                const qid = btn.dataset.qid;
                postAnswer(detailsCol, qid);
            }
        });

        detailsCol.addEventListener('keypress', (e) => {
            if (e.target.classList.contains('ans_content') && e.key === 'Enter') {
                const qid = e.target.dataset.qid;
                postAnswer(detailsCol, qid);
            }
        });
    }

    function syncFeedListSilent() {
        let url = '/api/qa_action?action=get_feed';
        if (selectedTag) {
            url += '&tag=' + encodeURIComponent(selectedTag);
        }
        fetch(url)
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                renderFeed(res.data);
                if (viewingQuestionId) {
                    highlightActiveCard(viewingQuestionId);
                }
            }
        });
    }

    function postAnswer(container, qid) {
        const inputContent = container.querySelector('.ans_content');
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
                // Refresh detailed content
                loadSingleQuestion(qid, false);
                // Sync feed count in background
                syncFeedListSilent();
            } else {
                alert(res.message);
            }
        });
    }

    // Single post view state
    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.qid) {
            selectPost(parseInt(e.state.qid), false);
        } else {
            viewingQuestionId = null;
            const isDesktop = window.innerWidth >= 768;
            if (!isDesktop) {
                document.getElementById('feed_column').classList.remove('hidden');
                document.getElementById('details_column').classList.add('hidden');
                detailsBackBar.classList.add('hidden');
            } else {
                loadFeed();
            }
        }
    });

    loadFeed();
});
</script>

<?php require_once 'includes/footer.php'; ?>
