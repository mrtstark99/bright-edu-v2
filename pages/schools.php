<?php
$page_title = "Danh sách Trường Nhật Ngữ - Bright Education";
include 'includes/header.php';

// Read data
$json_data = file_get_contents(__DIR__ . '/../schools_data.json');
$data = json_decode($json_data, true);
$regions = $data['regions'];
$schools = $data['schools'];

// Escape JSON for use in JS
$schools_json = json_encode($schools, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>

<main class="pt-20 pb-24 bg-slate-50 min-h-screen">
  <!-- Hero Section -->
  <section class="relative bg-midnight overflow-hidden py-16 sm:py-24">
    <!-- Background Effects -->
    <div class="absolute inset-0 z-0">
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-sage-500/20 rounded-full blur-3xl mix-blend-screen"></div>
      <div class="absolute bottom-0 -left-24 w-72 h-72 bg-sky-500/20 rounded-full blur-3xl mix-blend-screen"></div>
      <div class="absolute inset-0 bg-[url('/assets/images/pattern-grid.svg')] opacity-5"></div>
    </div>
    
    <div class="container mx-auto px-6 lg:px-12 relative z-10">
      <div class="max-w-3xl">
        <span class="inline-flex items-center justify-center bg-white/10 text-sage-300 backdrop-blur-md px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide uppercase mb-6 border border-white/10">
          Mạng Lưới Đối Tác
        </span>
        <h1 class="text-4xl sm:text-5xl font-black text-white font-display leading-tight mb-6">
          Hệ thống các trường <br>
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-sage-300 to-sky-300">Nhật Ngữ uy tín</span>
        </h1>
        <p class="text-lg text-slate-300 max-w-2xl leading-relaxed">
          Bright Education tự hào là đại diện tuyển sinh của hàng trăm trường Nhật Ngữ chất lượng trên khắp các tỉnh thành Nhật Bản. Hãy chọn khu vực bạn muốn theo học và tìm hiểu ngôi trường phù hợp nhất.
        </p>
      </div>
    </div>
  </section>

  <section class="py-12 relative z-20 -mt-8">
    <div class="container mx-auto px-6 lg:px-12">
        <!-- Interactive Filter Panel -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="w-full md:w-auto flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="bi bi-search"></i>
                    </div>
                    <input type="text" id="searchInput" placeholder="Tìm kiếm tên trường..." class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-midnight font-medium focus:bg-white focus:border-sage-500 focus:ring-4 focus:ring-sage-500/10 transition-all outline-none">
                </div>
            </div>
            <div class="w-full md:w-auto flex items-center gap-4">
                <span class="text-sm font-bold text-slate-400 uppercase tracking-wider shrink-0">Khu vực:</span>
                <select id="regionSelect" class="w-full md:w-64 px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-midnight font-medium focus:bg-white focus:border-sage-500 focus:ring-4 focus:ring-sage-500/10 transition-all outline-none appearance-none cursor-pointer">
                    <option value="">Tất cả khu vực (<?= count($schools) ?> trường)</option>
                    <?php foreach ($regions as $region): ?>
                        <option value="<?= htmlspecialchars($region) ?>"><?= htmlspecialchars($region) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
  </section>

  <!-- Schools Grid -->
  <section class="pb-24">
    <div class="container mx-auto px-6 lg:px-12">
      <!-- Active Filters Display -->
      <div id="activeFilterDisplay" class="flex items-center gap-2 mb-8 hidden">
          <span class="text-sm text-muted">Đang hiển thị kết quả cho:</span>
          <span id="activeRegionBadge" class="inline-flex items-center gap-1.5 bg-sage-50 text-sage-700 py-1 px-3 rounded-full text-xs font-bold whitespace-nowrap">
            <!-- Filled by JS -->
          </span>
      </div>

      <div id="schoolsGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <!-- Schools will be rendered here by JS -->
      </div>
      
      <!-- Empty State -->
      <div id="emptyState" class="hidden text-center py-20">
          <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400 text-4xl">
              <i class="bi bi-search"></i>
          </div>
          <h3 class="text-xl font-bold text-midnight mb-2">Không tìm thấy trường nào</h3>
          <p class="text-muted max-w-md mx-auto">Không có kết quả phù hợp với từ khóa hoặc khu vực bạn chọn. Vui lòng thử lại với tiêu chí khác.</p>
          <button id="resetFilters" class="mt-6 text-sage-600 font-bold hover:text-sage-700">Xóa bộ lọc</button>
      </div>

    </div>
  </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const schools = <?= $schools_json ?>;
    const grid = document.getElementById('schoolsGrid');
    const searchInput = document.getElementById('searchInput');
    const regionSelect = document.getElementById('regionSelect');
    const emptyState = document.getElementById('emptyState');
    const activeFilterDisplay = document.getElementById('activeFilterDisplay');
    const activeRegionBadge = document.getElementById('activeRegionBadge');
    const resetFiltersBtn = document.getElementById('resetFilters');

    function renderSchools(filteredSchools) {
        grid.innerHTML = '';
        
        if (filteredSchools.length === 0) {
            grid.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        grid.classList.remove('hidden');
        emptyState.classList.add('hidden');

        filteredSchools.forEach(school => {
            const el = document.createElement('div');
            el.className = 'bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group flex flex-col h-full';
            
            let websiteHtml = school.website ? `<a href="${school.website}" target="_blank" class="text-sky-600 hover:text-sky-700 underline truncate block"><i class="bi bi-link-45deg"></i> Website trường</a>` : '<span class="text-slate-400 text-sm">Đang cập nhật</span>';
            
            el.innerHTML = `
                <div class="p-6 pb-5 bg-slate-50/50 border-b border-slate-100 flex-grow">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <span class="inline-flex items-center gap-1.5 bg-sky-50 text-sky-600 py-1 px-3 rounded-xl text-xs font-bold uppercase tracking-wider">
                            <i class="bi bi-geo-alt-fill"></i> ${school.region}
                        </span>
                        <div class="w-8 h-8 rounded-full bg-slate-200/50 flex items-center justify-center text-slate-400 group-hover:bg-sage-100 group-hover:text-sage-500 transition-colors shrink-0">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-midnight mb-1 group-hover:text-sage-600 transition-colors leading-snug">
                        ${school.name_en}
                    </h3>
                    ${school.area ? `<div class="text-sm text-slate-500 mt-2"><i class="bi bi-geo"></i> Khu vực chi tiết: ${school.area}</div>` : ''}
                </div>
                
                <div class="p-6 bg-white shrink-0 border-t border-slate-50 flex items-center justify-between">
                    <div class="text-sm font-medium">
                        ${websiteHtml}
                    </div>
                </div>
            `;
            grid.appendChild(el);
        });
    }

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const region = regionSelect.value;
        
        let filtered = schools;

        if (region) {
            filtered = filtered.filter(s => s.region === region);
            activeFilterDisplay.classList.remove('hidden');
            activeRegionBadge.innerHTML = `<i class="bi bi-geo-alt-fill"></i> Khu vực: ${region}`;
        } else {
            activeFilterDisplay.classList.add('hidden');
        }

        if (searchTerm) {
            filtered = filtered.filter(s => {
                const searchStr = `${s.name_en} ${s.area || ''} ${s.region}`.toLowerCase();
                return searchStr.includes(searchTerm);
            });
        }

        renderSchools(filtered);
    }

    searchInput.addEventListener('input', applyFilters);
    regionSelect.addEventListener('change', applyFilters);
    
    resetFiltersBtn.addEventListener('click', () => {
        searchInput.value = '';
        regionSelect.value = '';
        applyFilters();
    });

    // Initial render
    renderSchools(schools);
});
</script>

<?php include 'includes/footer.php'; ?>
