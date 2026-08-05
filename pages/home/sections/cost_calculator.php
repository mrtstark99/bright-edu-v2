    <section id="cost" class="bg-slate-50 py-20 lg:py-28 relative">
      <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-sand-200 to-transparent"></div>
      <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-16">
          <span class="text-primary font-bold tracking-wider uppercase text-xs mb-3 block">Minh báº¡ch chi phÃ­</span>
          <h2 class="text-3xl sm:text-4xl font-bold text-primary font-display">Tá»•ng chi phÃ­ dá»± kiáº¿n nÄƒm Ä‘áº§u</h2>
          <p class="mt-4 text-lg text-muted">HÃ£y tÃ¹y chá»‰nh cÃ¡c lá»±a chá»n dÆ°á»›i Ä‘Ã¢y Ä‘á»ƒ xem chi tiáº¿t dá»± toÃ¡n vÃ  chuáº©n bá»‹ tÃ i chÃ­nh vá»¯ng vÃ ng cho lá»™ trÃ¬nh du há»c cá»§a báº¡n.</p>
        </div>

        <style>
          .wiz-panel { display: none; }
          .wiz-panel.wiz-active { display: flex; flex-direction: column; gap: 12px; animation: wizFade 0.22s ease; }
          @keyframes wizFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

          .calc-item input:checked + .calc-box {
            border-color: var(--color-primary, #0d243e);
            background-color: rgba(13, 36, 62, 0.04);
          }
          .calc-item input:checked + .calc-box .radio-dot {
            transform: scale(1);
          }

          .calc-item input:checked + .calc-box .calc-details {
            display: block;
            animation: slideDown 0.22s ease-out;
          }
          @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
          }
        </style>

        <div id="calculator">
          <div class="flex flex-col lg:flex-row gap-8 items-stretch">

            <!-- Left: Step Wizard -->
            <div class="w-full lg:w-2/3 flex">
              <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col w-full reveal">

                <!-- Progress -->
                <div class="flex items-center justify-between mb-5">
                  <span class="text-xs font-bold text-muted uppercase tracking-widest">BÆ°á»›c <span id="wiz-num-label">1</span> / 6</span>
                  <div class="flex gap-1.5" id="wiz-pips">
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-primary" style="width:2rem" data-pip="0"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="1"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="2"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="3"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="4"></div>
                    <div class="h-1.5 rounded-full transition-all duration-300 bg-slate-200" style="width:1rem" data-pip="5"></div>
                  </div>
                </div>

                <!-- Step heading -->
                <h4 class="text-lg font-bold text-midnight flex items-center gap-3 mb-1">
                  <span class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-bold shrink-0" id="wiz-badge">1</span>
                  <span id="wiz-title-text">Chá»n Há»‡ du há»c Nháº­t Báº£n</span>
                </h4>
                <p class="text-sm text-muted mb-5 pl-11" style="min-height:18px" id="wiz-subtitle"></p>

                <!-- Panels -->
                <div class="flex-1" id="wiz-panels">

                  <!-- Panel 0: Há»‡ du há»c -->
                  <div class="wiz-panel wiz-active" data-panel="0">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="TrÆ°á»ng Nháº­t Ngá»¯" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">TrÆ°á»ng Nháº­t Ngá»¯</div>
                              <div class="text-xs text-muted mt-0.5">Lá»™ trÃ¬nh há»c tiáº¿ng Nháº­t táº­p trung tá»« 1.5 - 2 nÄƒm táº¡i Nháº­t Báº£n</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">Phá»• biáº¿n nháº¥t</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lá»™ trÃ¬nh:</strong> Há»c tiáº¿ng táº¡i VN (6-8 thÃ¡ng) â†’ Sang Nháº­t há»c TrÆ°á»ng Nháº­t ngá»¯ (1-2 nÄƒm) â†’ Há»c lÃªn Senmon/Äáº¡i há»c hoáº·c Ä‘á»•i visa Ä‘i lÃ m.</p>
                          <p><strong>YÃªu cáº§u:</strong> Tá»‘t nghiá»‡p THPT (trá»‘ng dÆ°á»›i 5 nÄƒm), GPA &gt; 6.0, tiáº¿ng Nháº­t tá»‘i thiá»ƒu N5 (hoáº·c há»c cáº¥p tá»‘c táº¡i Bright).</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="TrÆ°á»ng Senmon" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">TrÆ°á»ng Senmon</div>
                              <div class="text-xs text-muted mt-0.5">ÄÃ o táº¡o nghá» thá»±c chiáº¿n 2 nÄƒm chuyÃªn sÃ¢u (IT, Du lá»‹ch...)</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">HÆ°á»›ng nghiá»‡p</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lá»™ trÃ¬nh:</strong> ÄÃ o táº¡o nghá» thá»±c hÃ nh 2 nÄƒm chuyÃªn sÃ¢u (IT, Du lá»‹ch, Thiáº¿t káº¿, KhÃ¡ch sáº¡n...) â†’ Äi lÃ m ngay vá»›i visa ká»¹ sÆ°/nhÃ¢n vÄƒn quá»‘c táº¿.</p>
                          <p><strong>YÃªu cáº§u:</strong> Tá»‘t nghiá»‡p THPT, trÃ¬nh Ä‘á»™ tiáº¿ng Nháº­t tá»‘i thiá»ƒu N2 hoáº·c tá»‘t nghiá»‡p khÃ³a há»c táº¡i TrÆ°á»ng Nháº­t ngá»¯ bÃªn Nháº­t.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="TrÆ°á»ng Äáº¡i Há»c" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">TrÆ°á»ng Äáº¡i Há»c</div>
                              <div class="text-xs text-muted mt-0.5">Há»‡ cá»­ nhÃ¢n chÃ­nh quy táº¡i cÃ¡c trÆ°á»ng Ä‘áº¡i há»c hÃ ng Ä‘áº§u Nháº­t Báº£n</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">ChÃ­nh quy</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lá»™ trÃ¬nh:</strong> Há»c cá»­ nhÃ¢n chÃ­nh quy 4 nÄƒm táº¡i Nháº­t â†’ Nháº­n báº±ng Cá»­ nhÃ¢n quá»‘c táº¿ â†’ CÆ¡ há»™i thÄƒng tiáº¿n cao vÃ  Ä‘á»‹nh cÆ° lÃ¢u dÃ i.</p>
                          <p><strong>YÃªu cáº§u:</strong> Tá»‘t nghiá»‡p THPT, Ä‘iá»ƒm GPA &gt; 6.5, thi ká»³ thi EJU hoáº·c chá»©ng chá»‰ tiáº¿ng Nháº­t tá»‘i thiá»ƒu N2.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="ChÆ°Æ¡ng TrÃ¬nh Há»c Bá»•ng" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">ChÆ°Æ¡ng TrÃ¬nh Há»c Bá»•ng</div>
                              <div class="text-xs text-muted mt-0.5">Há»c bá»•ng bÃ¡o, Ä‘iá»u dÆ°á»¡ng... Miá»…n 100% há»c phÃ­ & KTX</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">Há»c bá»•ng</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lá»™ trÃ¬nh:</strong> Doanh nghiá»‡p tÃ i trá»£ 100% há»c phÃ­ & KTX â†’ Vá»«a há»c vá»«a lÃ m bÃ¡n thá»i gian táº¡i doanh nghiá»‡p báº£o trá»£ â†’ LÃ m viá»‡c chÃ­nh thá»©c tá»« 3-5 nÄƒm sau tá»‘t nghiá»‡p.</p>
                          <p><strong>YÃªu cáº§u:</strong> Tá»‘t nghiá»‡p THPT, sá»©c khá»e tá»‘t, cam káº¿t tuÃ¢n thá»§ há»£p Ä‘á»“ng lao Ä‘á»™ng vÃ  há»c táº­p cá»§a Ä‘Æ¡n vá»‹ tÃ i trá»£ há»c bá»•ng.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_system" value="Há»‡ Äáº¡i Há»c Tiáº¿ng Anh" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Há»‡ Äáº¡i Há»c Tiáº¿ng Anh</div>
                              <div class="text-xs text-muted mt-0.5">ChÆ°Æ¡ng trÃ¬nh E-Track giáº£ng dáº¡y 100% báº±ng tiáº¿ng Anh</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">E-Track</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Lá»™ trÃ¬nh:</strong> Há»c cá»­ nhÃ¢n chÃ­nh quy 4 nÄƒm báº±ng 100% tiáº¿ng Anh táº¡i cÃ¡c trÆ°á»ng Ä‘áº¡i há»c quá»‘c táº¿ á»Ÿ Nháº­t â†’ Há»c thÃªm tiáº¿ng Nháº­t song song.</p>
                          <p><strong>YÃªu cáº§u:</strong> Tá»‘t nghiá»‡p THPT, Ä‘iá»ƒm GPA &gt; 7.0, chá»©ng chá»‰ tiáº¿ng Anh (IELTS &gt; 5.5 hoáº·c TOEFL iBT &gt; 75 hoáº·c tÆ°Æ¡ng Ä‘Æ°Æ¡ng).</p>
                        </div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 1: GÃ³i dá»‹ch vá»¥ -->
                  <div class="wiz-panel" data-panel="1">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_package" value="15000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">TiÃªu Chuáº©n</div>
                              <div class="text-xs text-muted mt-0.5">Xá»­ lÃ½ há»“ sÆ¡ cÆ¡ báº£n</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0" id="calc-package-price-text">15.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Dá»‹ch vá»¥ bao gá»“m:</strong> Dá»‹ch thuáº­t cÃ´ng chá»©ng há»“ sÆ¡, giáº£i trÃ¬nh tÃ i chÃ­nh cháº·t cháº½, luyá»‡n phá»ng váº¥n visa vÃ  trÆ°á»ng, há»— trá»£ ná»™p COE vÃ  Ä‘áº·t vÃ© mÃ¡y bay.</p>
                        </div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 2: KhÃ³a há»c tiáº¿ng Nháº­t -->
                  <div class="wiz-panel" data-panel="2">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_course" value="0" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Tá»± há»c / ÄÃ£ cÃ³ N4</div>
                              <div class="text-xs text-muted mt-0.5">DÃ nh cho há»c sinh Ä‘Ã£ Ä‘á»§ trÃ¬nh Ä‘á»™</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">0Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Ná»™i dung há»— trá»£:</strong> Bright Education há»— trá»£ kiá»ƒm tra nÄƒng lá»±c Ä‘áº§u vÃ o miá»…n phÃ­. ThÃ­ch há»£p cho há»c sinh tá»± há»c táº¡i nhÃ  hoáº·c Ä‘Ã£ cÃ³ chá»©ng chá»‰ tiáº¿ng Nháº­t JLPT N4 trá»Ÿ lÃªn.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_course" value="10000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">CÆ¡ báº£n 3 thÃ¡ng</div>
                              <div class="text-xs text-muted mt-0.5">ChÆ°Æ¡ng trÃ¬nh chuáº©n N5</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">10.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Chi tiáº¿t khÃ³a há»c:</strong> ÄÃ o táº¡o cáº¥p tá»‘c 3 thÃ¡ng (5 buá»•i/tuáº§n). Há»c báº£ng chá»¯ cÃ¡i, ngá»¯ phÃ¡p ná»n táº£ng N5, phÃ¡t Ã¢m chuáº©n vÃ  pháº£n xáº¡ giao tiáº¿p cÆ¡ báº£n.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_course" value="15000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">ChuyÃªn sÃ¢u 6 thÃ¡ng</div>
                              <div class="text-xs text-muted mt-0.5">Luyá»‡n thi JLPT N4</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">15.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Chi tiáº¿t khÃ³a há»c:</strong> ÄÃ o táº¡o bÃ¡n trÃº 6 thÃ¡ng tá»« N5 lÃªn N4. TÃ­ch há»£p luyá»‡n thi cÃ¡c chá»©ng chá»‰ JLPT/NAT-TEST, ká»¹ nÄƒng phá»ng váº¥n há»c bá»•ng vÃ  xin viá»‡c lÃ m thÃªm táº¡i Nháº­t.</p>
                        </div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 3: TrÆ°á»ng Nháº­t Ngá»¯ -->
                  <div class="wiz-panel" data-panel="3">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_school" value="110000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">TrÆ°á»ng á»Ÿ tá»‰nh xa</div>
                              <div class="text-xs text-muted mt-0.5">Hokkaido, Ibaraki, Oita... Há»c phÃ­ vÃ  sinh hoáº¡t phÃ­ Ä‘á»u ráº¥t ráº».</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 110.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Äáº·c Ä‘iá»ƒm vÃ¹ng:</strong> Há»c phÃ­ chá»‰ khoáº£ng 60 - 70 Man/nÄƒm. Tiá»n thuÃª phÃ²ng chá»‰ khoáº£ng 2 - 3 Man/thÃ¡ng. ThÃ­ch há»£p cho há»c viÃªn muá»‘n tiáº¿t kiá»‡m ngÃ¢n sÃ¡ch ban Ä‘áº§u tá»‘i Ä‘a.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_school" value="125000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">ThÃ nh phá»‘ cá»¡ trung</div>
                              <div class="text-xs text-muted mt-0.5">Fukuoka, Chiba, Saitama... Dá»… tÃ¬m viá»‡c lÃ m, chi phÃ­ vá»«a pháº£i.</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 125.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Äáº·c Ä‘iá»ƒm vÃ¹ng:</strong> Há»c phÃ­ 70 - 75 Man/nÄƒm. Äáº§y Ä‘á»§ viá»‡c lÃ m thÃªm phong phÃº nhÆ°ng giÃ¡ cáº£ sinh hoáº¡t nháº¹ nhÃ ng hÆ¡n nhiá»u so vá»›i ná»™i Ä‘Ã´ Tokyo.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_school" value="135000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="flex items-center gap-2">
                                <span class="font-bold text-midnight text-sm sm:text-base">Ngoáº¡i Ã´ Tokyo / Osaka</span>
                                <span class="bg-amber-400 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded-full">Phá»• biáº¿n</span>
                              </div>
                              <div class="text-xs text-muted mt-0.5">CÃ¡ch trung tÃ¢m 30-40p tÃ u. CÃ¢n báº±ng tá»‘t giá»¯a chi phÃ­ vÃ  cÆ¡ há»™i.</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 135.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Äáº·c Ä‘iá»ƒm vÃ¹ng:</strong> Há»c phÃ­ 75 - 80 Man/nÄƒm. Lá»±a chá»n tá»‘i Æ°u Ä‘á»ƒ tiáº¿p cáº­n cÆ¡ há»™i viá»‡c lÃ m lá»›n táº¡i trung tÃ¢m nhÆ°ng váº«n giá»¯ má»©c sinh hoáº¡t phÃ­ á»Ÿ má»©c dá»… chá»‹u.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_school" value="145000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Trung tÃ¢m Tokyo / Osaka</div>
                              <div class="text-xs text-muted mt-0.5">Sáº§m uáº¥t, nhiá»u cÆ¡ há»™i viá»‡c lÃ m lÆ°Æ¡ng cao nhÆ°ng há»c phÃ­ Ä‘áº¯t.</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 145.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Äáº·c Ä‘iá»ƒm vÃ¹ng:</strong> Há»c phÃ­ &gt; 80 Man/nÄƒm. Chi phÃ­ thuÃª nhÃ  Ä‘áº¯t Ä‘á» nháº¥t. BÃ¹ láº¡i, Ä‘Ã¢y lÃ  trung tÃ¢m sáº§m uáº¥t vá»›i vÃ´ vÃ n cÆ¡ há»™i lÃ m thÃªm lÆ°Æ¡ng cao vÃ  dá»… tÃ¬m viá»‡c dÃ i háº¡n.</p>
                        </div>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 4: Sinh hoáº¡t ban Ä‘áº§u -->
                  <div class="wiz-panel" data-panel="4">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_living" value="30000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">Tiáº¿t Kiá»‡m</div>
                              <div class="text-xs text-muted mt-0.5">KTX chung 4 ngÆ°á»i + 10 Man tiá»n máº·t phÃ²ng thÃ¢n</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 30.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Chi tiáº¿t ngÃ¢n sÃ¡ch:</strong> Khoáº£ng 8 Man Ä‘Ã³ng trÆ°á»›c KTX chung cá»c vÃ  tiá»n nhÃ  2-3 thÃ¡ng + 10 Man chi tiÃªu Äƒn uá»‘ng tá»‘i thiá»ƒu thÃ¡ng Ä‘áº§u tiÃªn.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_living" value="45000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-4">
                          <div class="flex items-center gap-4">
                            <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                              <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                            </div>
                            <div>
                              <div class="font-bold text-midnight text-sm sm:text-base">CÆ¡ Báº£n</div>
                              <div class="text-xs text-muted mt-0.5">KTX tiÃªu chuáº©n 2 ngÆ°á»i + 12 Man tiá»n máº·t phÃ²ng thÃ¢n</div>
                            </div>
                          </div>
                          <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 45.000.000Ä‘</div>
                        </div>
                        <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                          <p><strong>Chi tiáº¿t ngÃ¢n sÃ¡ch:</strong> Khoáº£ng 15 Man Ä‘Ã³ng trÆ°á»›c KTX phÃ²ng Ä‘Ã´i tiÃªu chuáº©n 3 thÃ¡ng + 12 Man chi tiÃªu Äƒn uá»‘ng, Ä‘i láº¡i thoáº£i mÃ¡i hÆ¡n trong thá»i gian Ä‘áº§u.</p>
                        </div>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_living" value="60000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-355 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                          <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                          </div>
                          <div>
                            <div class="font-bold text-midnight text-sm sm:text-base">Thoáº£i MÃ¡i</div>
                            <div class="text-xs text-muted mt-0.5">ThuÃª phÃ²ng riÃªng + 15 Man tiá»n máº·t phÃ²ng thÃ¢n</div>
                          </div>
                        </div>
                        <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">~ 60.000.000Ä‘</div>
                      </div>
                      <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                        <p><strong>Chi tiáº¿t ngÃ¢n sÃ¡ch:</strong> Khoáº£ng 25 Man Ä‘Ã³ng trÆ°á»›c tiá»n thuÃª phÃ²ng riÃªng (cá»c + lá»… + tiá»n nhÃ  1 thÃ¡ng) + 15 Man chi tiÃªu dÆ° dáº£.</p>
                      </div>
                    </label>
                  </div>

                  <!-- Panel 5: Thá»§ tá»¥c khÃ¡c -->
                  <div class="wiz-panel" data-panel="5">
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_other" value="8650000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                          <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                          </div>
                          <div>
                            <div class="font-bold text-midnight text-sm sm:text-base">Tháº¥p Nháº¥t</div>
                            <div class="text-xs text-muted mt-0.5">Tá»•ng cÃ¡c má»©c tháº¥p nháº¥t (SÄƒn vÃ© giÃ¡ ráº»)</div>
                          </div>
                        </div>
                        <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">8.650.000Ä‘</div>
                      </div>
                      <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                        <p><strong>Bao gá»“m:</strong> PhÃ­ khÃ¡m lao (~1.5M), lá»‡ phÃ­ visa (~1.3M) cá»™ng thÃªm vÃ© mÃ¡y bay giÃ¡ ráº» má»™t chiá»u (bay transit hoáº·c hÃ£ng giÃ¡ ráº»).</p>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_other" value="13000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                          <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                          </div>
                          <div>
                            <div class="font-bold text-midnight text-sm sm:text-base">Trung BÃ¬nh</div>
                            <div class="text-xs text-muted mt-0.5">Chi tiÃªu há»£p lÃ½, vÃ© mÃ¡y bay phá»• thÃ´ng</div>
                          </div>
                        </div>
                        <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">13.000.000Ä‘</div>
                      </div>
                      <div class="calc-details border-t border-slate-100 pt-3 text-xs text-slate-500 space-y-1.5 hidden">
                        <p><strong>Bao gá»“m:</strong> Lá»‡ phÃ­ báº¯t buá»™c vÃ  vÃ© mÃ¡y bay bay tháº³ng phá»• thÃ´ng cá»§a cÃ¡c hÃ£ng hÃ ng khÃ´ng uy tÃ­n nhÆ° Vietnam Airlines.</p>
                      </div>
                    </label>
                    <label class="calc-item cursor-pointer w-full block">
                      <input type="radio" name="calc_other" value="17000000" class="sr-only">
                      <div class="calc-box p-4 rounded-2xl border-2 border-slate-200 transition-all hover:border-slate-350 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                          <div class="w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all">
                            <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform radio-dot"></div>
                          </div>
                          <div>
                            <div class="font-bold text-midnight text-sm sm:text-base">Dá»± TÃ­nh An ToÃ n</div>
                            <div class="text-xs text-muted mt-0.5">Tá»•ng má»©c cao nháº¥t, bay tháº³ng giá» Ä‘áº¹p</div>
                          </div>
                        </div>
                        <div class="text-sm sm:text-base text-primary font-black text-right shrink-0">17.000.000Ä‘</div>
                      </div>
                    </label>
                  </div>

                </div><!-- /wiz-panels -->

                <!-- Footer nav -->
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between" style="min-height:36px">
                  <button id="wiz-back" class="text-sm text-muted hover:text-primary transition-colors flex items-center gap-1.5" type="button" style="visibility:hidden">
                    <i class="bi bi-arrow-left text-xs"></i> Quay láº¡i
                  </button>
                  <div class="flex items-center gap-4">
                    <span class="text-xs text-slate-400 italic hidden sm:inline" id="wiz-hint">Chá»n má»™t má»¥c Ä‘á»ƒ tiáº¿p tá»¥c â†’</span>
                    <button id="wiz-next" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-primary text-white hover:bg-slate-800 transition-all flex items-center gap-1 opacity-50 pointer-events-none" type="button">
                      Tiáº¿p tá»¥c <i class="bi bi-arrow-right text-[10px]"></i>
                    </button>
                  </div>
                </div>

              </div>
            </div><!-- /left -->

            <!-- Right: Sticky Receipt -->
            <div class="w-full lg:w-1/3 reveal reveal-delay-200">
              <div class="sticky top-24 bg-midnight text-white rounded-3xl p-8 shadow-xl border-t-4 border-sage-500 relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute -left-16 -bottom-16 w-40 h-40 bg-sage-500/10 rounded-full blur-2xl"></div>
                <h4 class="text-xl font-bold font-display mb-6 border-b border-white/10 pb-4 flex items-center gap-2 relative z-10">
                  <i class="bi bi-receipt text-sage-400"></i> Phiáº¿u Dá»± ToÃ¡n
                </h4>
                <div class="space-y-4 mb-8 text-[15px] relative z-10">
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">Há»‡ du há»c:</span>
                    <span class="font-semibold text-right text-sage-300" id="summary_system">ChÆ°a chá»n</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">1. Dá»‹ch vá»¥ Bright:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_package">ChÆ°a chá»n</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">2. KhÃ³a há»c VN:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_course">ChÆ°a chá»n</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">3. Há»c phÃ­ trÆ°á»ng:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_school">ChÆ°a chá»n</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">4. Ä‚n á»Ÿ ban Ä‘áº§u:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_living">ChÆ°a chá»n</span>
                  </div>
                  <div class="flex justify-between items-start gap-4">
                    <span class="text-white/70">5. Thá»§ tá»¥c khÃ¡c:</span>
                    <span class="font-semibold text-right text-slate-300" id="summary_other">ChÆ°a chá»n</span>
                  </div>
                </div>
                <div class="border-t border-white/10 pt-6 mt-6 relative z-10">
                  <div class="text-sm text-sage-300 font-bold tracking-widest uppercase mb-1">Tá»•ng Cáº§n Chuáº©n Bá»‹</div>
                  <div class="text-4xl font-black text-white font-display tracking-tight break-words">
                    <span id="summary_total">0</span><span class="text-xl ml-1 text-white/70 font-medium">VNÄ</span>
                  </div>
                  <p class="text-xs text-white/50 mt-3 italic">*Báº£ng dá»± toÃ¡n mang tÃ­nh tham kháº£o. Chi phÃ­ thá»±c táº¿ phá»¥ thuá»™c tá»· giÃ¡ YÃªn vÃ  nhu cáº§u tiÃªu dÃ¹ng.</p>
                </div>
                <a href="/contact" class="w-full mt-8 block text-center bg-sage-500 hover:bg-sage-400 text-white rounded-xl py-4 font-bold transition-colors shadow-lg relative z-10">
                  ÄÄƒng kÃ½ tÆ° váº¥n lá»™ trÃ¬nh nÃ y <i class="bi bi-arrow-right ml-1"></i>
                </a>
              </div>
            </div>

          </div>
        </div>

        <script>
          document.addEventListener('DOMContentLoaded', function() {

            const STEPS = [
              { title: 'Chá»n Há»‡ du há»c Nháº­t Báº£n',                    sub: '',                                                              name: 'calc_system'  },
              { title: 'Chá»n gÃ³i Dá»‹ch vá»¥ Bright Education',          sub: '',                                                              name: 'calc_package' },
              { title: 'ChÆ°Æ¡ng trÃ¬nh há»c Tiáº¿ng Nháº­t táº¡i Viá»‡t Nam',    sub: '',                                                              name: 'calc_course'  },
              { title: 'Lá»±a chá»n TrÆ°á»ng Nháº­t Ngá»¯ (NÄƒm Ä‘áº§u tiÃªn)',    sub: '',                                                              name: 'calc_school'  },
              { title: 'Chi phÃ­ sinh hoáº¡t ban Ä‘áº§u táº¡i Nháº­t',          sub: '',                                                              name: 'calc_living'  },
              { title: 'Chi phÃ­ thá»§ tá»¥c khÃ¡c táº¡i VN',                 sub: 'Gá»“m: KhÃ¡m lao phá»•i, Thi JLPT, Há»™ chiáº¿u, VÃ© mÃ¡y bay',         name: 'calc_other'   },
            ];

            let currentStep = 0;

            const fmt   = v => new Intl.NumberFormat('vi-VN').format(v) + 'Ä‘';
            const fmtNS = v => new Intl.NumberFormat('vi-VN').format(v);

            function updateSummary() {
              const systemEl = document.querySelector('input[name="calc_system"]:checked');
              const systemVal = systemEl ? systemEl.value : 'ChÆ°a chá»n';
              document.getElementById('summary_system').textContent = systemVal;

              const get = name => { const el = document.querySelector(`input[name="${name}"]:checked`); return el ? parseInt(el.value) : -1; };
              const vals = STEPS.slice(1).map(s => get(s.name));
              const ids  = ['summary_package','summary_course','summary_school','summary_living','summary_other'];
              vals.forEach((v, i) => {
                document.getElementById(ids[i]).textContent = v === -1 ? 'ChÆ°a chá»n' : fmt(v);
              });
              const total = vals.filter(v => v !== -1).reduce((a, b) => a + b, 0);
              const el    = document.getElementById('summary_total');
              const cur   = parseInt(el.textContent.replace(/\./g, '')) || 0;
              animateValue(el, cur, total, 400);
            }

            function animateValue(obj, start, end, dur) {
              let t0 = null;
              const tick = ts => {
                if (!t0) t0 = ts;
                const p = Math.min((ts - t0) / dur, 1);
                const e = 1 - Math.pow(1 - p, 3);
                obj.innerHTML = fmtNS(Math.floor(e * (end - start) + start));
                if (p < 1) requestAnimationFrame(tick); else obj.innerHTML = fmtNS(end);
              };
              requestAnimationFrame(tick);
            }

            function goToStep(idx) {
              document.querySelectorAll('.wiz-panel').forEach(p => p.classList.remove('wiz-active'));
              document.querySelector(`.wiz-panel[data-panel="${idx}"]`).classList.add('wiz-active');
              currentStep = idx;

              document.getElementById('wiz-num-label').textContent  = idx + 1;
              document.getElementById('wiz-badge').textContent       = idx + 1;
              document.getElementById('wiz-title-text').textContent  = STEPS[idx].title;
              document.getElementById('wiz-subtitle').textContent    = STEPS[idx].sub;

              document.querySelectorAll('[data-pip]').forEach(pip => {
                const i = parseInt(pip.dataset.pip);
                pip.style.backgroundColor = i <= idx ? 'var(--color-primary, #0d243e)' : '#e2e8f0';
                pip.style.width = i === idx ? '2rem' : (i < idx ? '1.5rem' : '1rem');
              });

              document.getElementById('wiz-back').style.visibility  = idx === 0 ? 'hidden' : 'visible';
              document.getElementById('wiz-hint').textContent        = idx < 5 ? 'Chá»n má»™t má»¥c Ä‘á»ƒ tiáº¿p tá»¥c â†’' : 'ÄÃ£ hoÃ n thÃ nh táº¥t cáº£ cÃ¡c bÆ°á»›c âœ“';

              // Check if a radio is checked in the active step to enable/disable Next button
              const hasChecked = document.querySelector(`.wiz-panel[data-panel="${idx}"] input[type="radio"]:checked`) !== null;
              const nextBtn = document.getElementById('wiz-next');
              if (hasChecked) {
                nextBtn.classList.remove('opacity-50', 'pointer-events-none');
              } else {
                nextBtn.classList.add('opacity-50', 'pointer-events-none');
              }

              if (idx === 5) {
                nextBtn.innerHTML = 'HoÃ n thÃ nh <i class="bi bi-check-circle ml-1"></i>';
              } else {
                nextBtn.innerHTML = 'Tiáº¿p tá»¥c <i class="bi bi-arrow-right text-[10px]"></i>';
              }
            }

            const PRICES = {
              'TrÆ°á»ng Nháº­t Ngá»¯': 15000000,
              'TrÆ°á»ng Senmon': 30000000,
              'TrÆ°á»ng Äáº¡i Há»c': 30000000,
              'ChÆ°Æ¡ng TrÃ¬nh Há»c Bá»•ng': 15000000,
              'Há»‡ Äáº¡i Há»c Tiáº¿ng Anh': 30000000
            };

            document.querySelectorAll('input[name="calc_system"]').forEach(input => {
              input.addEventListener('change', function() {
                const val = this.value;
                const price = PRICES[val] || 15000000;
                
                // Update Step 1 (calc_package) input value and price text
                const packageInput = document.querySelector('input[name="calc_package"]');
                if (packageInput) {
                  packageInput.value = price;
                  // Reset it to force re-selection of Step 1 if the user navigates back & changes
                  packageInput.checked = false;
                }
                const priceText = document.getElementById('calc-package-price-text');
                if (priceText) {
                  priceText.textContent = new Intl.NumberFormat('vi-VN').format(price) + 'Ä‘';
                }
                
                updateSummary();
              });
            });

            document.querySelectorAll('#calculator input[type="radio"]').forEach(input => {
              input.addEventListener('change', function() {
                updateSummary();
                const nextBtn = document.getElementById('wiz-next');
                nextBtn.classList.remove('opacity-50', 'pointer-events-none');
              });
            });

            document.getElementById('wiz-back').addEventListener('click', () => {
              if (currentStep > 0) goToStep(currentStep - 1);
            });

            document.getElementById('wiz-next').addEventListener('click', () => {
              if (currentStep < 5) {
                goToStep(currentStep + 1);
              } else {
                // Last step: scroll to contact
                const contactSec = document.getElementById('contact');
                if (contactSec) {
                  contactSec.scrollIntoView({ behavior: 'smooth' });
                }
              }
            });

            goToStep(0);
            updateSummary();
          });
        </script>
      </div>
    </section>