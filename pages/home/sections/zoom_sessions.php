    <!-- Zoom Schedule Section -->
    <?php if (!empty($zoom_slots)): ?>
    <section id="zoom-schedule" class="bg-slate-50 py-20 lg:py-28 relative">
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto mb-16 max-w-2xl text-center">
          <span class="text-orange-600 font-bold tracking-wider uppercase text-xs mb-3 block">TÆ°Æ¡ng tÃ¡c trá»±c tiáº¿p</span>
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Lá»‹ch Há»™i Tháº£o & TÆ° Váº¥n Zoom</h2>
          <p class="mt-4 text-slate-500 text-[15px]">ÄÄƒng kÃ½ tham gia miá»…n phÃ­ cÃ¡c buá»•i chia sáº» thÃ´ng tin trá»±c tuyáº¿n tá»« chuyÃªn gia Bright Education vÃ  cÃ¡c trÆ°á»ng Nháº­t ngá»¯ Ä‘á»‘i tÃ¡c.</p>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($zoom_slots as $index => $slot): 
            $dateFormatted = date('d/m/Y', strtotime($slot['scheduled_date']));
            $dayOfWeek = ['Chá»§ Nháº­t','Thá»© Hai','Thá»© Ba','Thá»© TÆ°','Thá»© NÄƒm','Thá»© SÃ¡u','Thá»© Báº£y'][date('w', strtotime($slot['scheduled_date']))];
          ?>
          <!-- Event Card -->
          <div class="bg-white rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-soft hover:shadow-medium hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between h-full reveal reveal-delay-<?php echo $index * 100; ?>">
            <div>
              <div class="flex items-center justify-between gap-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold uppercase tracking-wider">
                  <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span> LIVE
                </span>
                <span class="text-xs font-bold text-slate-400"><i class="bi bi-calendar3 mr-1"></i><?php echo $slot['time_start']; ?>, <?php echo $dayOfWeek; ?> (<?php echo $dateFormatted; ?>)</span>
              </div>
              <h3 class="text-lg font-bold text-primary font-display mt-5 mb-2 leading-snug"><?php echo htmlspecialchars($slot['title']); ?></h3>
              <p class="text-[13.5px] text-slate-500 line-clamp-3 mb-4 leading-relaxed"><?php echo htmlspecialchars($slot['description']); ?></p>
              
              <div class="border-t border-slate-100 pt-4 mt-4 space-y-2.5 text-[13px] text-muted">
                <div class="flex items-center gap-2">
                  <i class="bi bi-clock text-orange-600"></i>
                  <span>Thá»i gian: <?php echo $slot['time_start']; ?> - <?php echo $slot['time_end']; ?></span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="bi bi-people text-orange-600"></i>
                  <span>Giá»›i háº¡n: <?php echo $slot['max_participants']; ?> há»c viÃªn</span>
                </div>
                <?php if ($slot['is_free']): ?>
                <div class="flex items-center gap-2">
                  <i class="bi bi-gift text-orange-600"></i>
                  <span class="text-green-600 font-bold">HoÃ n toÃ n miá»…n phÃ­</span>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <button type="button" class="w-full mt-6 py-3 bg-primary hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 zoom-btn" data-title="<?php echo htmlspecialchars($slot['title']); ?>">
              ÄÄƒng kÃ½ tham gia qua Zoom <i class="bi bi-arrow-right"></i>
            </button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <script>
        document.addEventListener('DOMContentLoaded', function() {
          document.querySelectorAll('.zoom-btn').forEach(btn => {
            btn.addEventListener('click', function() {
              const title = this.dataset.title;
              const contactSection = document.getElementById('contact');
              if (contactSection) {
                // Focus and populate message in form
                const messageTextarea = contactSection.querySelector('textarea[name="message"]');
                const nameInput = contactSection.querySelector('input[name="name"]');
                if (messageTextarea) {
                  messageTextarea.value = `TÃ´i muá»‘n Ä‘Äƒng kÃ½ tham gia buá»•i há»™i tháº£o Zoom trá»±c tuyáº¿n: "${title}".`;
                }
                contactSection.scrollIntoView({ behavior: 'smooth' });
                if (nameInput) {
                  setTimeout(() => nameInput.focus(), 800);
                }
              }
            });
          });
        });
      </script>
    </section>
    <?php endif; ?>