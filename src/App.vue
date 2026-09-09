<script setup>
  import { ref, onMounted, nextTick, createApp } from 'vue'
  import SalesAnalyzer from './components/SalesAnalyzer.vue'
  
  // 1. 언어 데이터 및 상태를 반응형 ref로 관리
  const currentLang = ref(localStorage.getItem('lang') || 'ko')
  const langData = ref({})
  
  let salesAnalyzerApp = null
  
  // SalesAnalyzer 마운트 함수
  const mountSalesAnalyzer = async () => {
    await nextTick() // DOM 업데이트 완료 대기
    const el = document.getElementById('sales-analyzer-mount')
    if (!el) return
  
    if (salesAnalyzerApp) {
      salesAnalyzerApp.unmount()
    }
  
    salesAnalyzerApp = createApp(SalesAnalyzer)
    salesAnalyzerApp.mount(el)
  }
  
  // 2. 다국어 로드 및 언어 변경 함수
  const changeLang = async (lang) => {
    try {
      const module = await import(`/js/lang/${lang}.js`)
      langData.value = module.default // 반응형 변수에 담으면 화면이 알아서 업데이트
      currentLang.value = lang
      localStorage.setItem('lang', lang)
  
      // DOM에 ko.js 내용이 반영된 후 SalesAnalyzer 마운트
      await mountSalesAnalyzer()
    } catch (error) {
      console.error('언어 변경 실패:', error)
    }
  }
  
  onMounted(async () => {
    // main.js 동적 로드
    if (!document.querySelector('script[src="/js/main.js"]')) {
      const script = document.createElement('script')
      script.src = '/js/main.js'
      script.async = true
      document.body.appendChild(script)
    }
  
    // 초기 언어 로드 및 마운트 실행
    await changeLang(currentLang.value)
  })
  </script>

<template>
  
  <div id="yy-main">
    <div id="skip">
      <a href="#yy-main-container">본문 바로가기</a>
      <a href="#yy-gnb">주메뉴 바로가기</a>
      <a href="#yy-lnb">서브메뉴 바로가기</a>
    </div>
    <div id="mouse-pointer"></div>
    <header id="yy-hd">
      <h1><a href="#"><span class="blind">YY's portfolio</span></a></h1>
      <div class="gnb-util-wrap">
        <h2 class="blind">주 메뉴</h2>
        <nav id="yy-gnb">
          <ol>
            <li class="depth1 active">
              <a href="#home">HOME</a>
            </li>
            <li class="depth1">
              <a href="#tech">TECH STACK</a>
            </li>
            <li class="depth1">
              <a href="#notes">Engineering Notes</a>
            </li>
            <li class="depth1">
              <a href="#project">PROJECT</a>
            </li>
            <li class="depth1">
              <a href="#lab">LAB</a>
            </li>
            <li class="depth1">
              <a href="#yy-ft">CONTACT</a>
            </li>
          </ol>
        </nav>
        <div class="m-button" data-aos="flip-left">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </header>
    <main id="yy-main-container">
      <div id="lang-select" style="position:fixed; top:20px; right:20px; z-index:9999;">
        <button @click="changeLang('ko')">한국어 (KR)</button>
        <button @click="changeLang('en')">English (US)</button>
      </div>
      <section id="home" class="home sec-main">
        <div class="intro_smile">
          <img class="smile_img" src="/images/intro_smile.png" alt="인트로_스마일">
        </div>
        <div class="intro_about" id="about" data-aos="fade-up" data-aos-delay="1000" data-aos-duration="1000">
          <div class="about-cover">
            <div class="cover">
              <h2><span>FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER 
              </span></h2>
            </div>
            <div class="cover">
              <h2><span>FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER FRONTEND · BACKEND ENGINEER
              </span></h2>
            </div>
          </div>
          <div class="about-box js-tilt-container" data-aos="fade-up" data-aos-delay="1000" data-aos-duration="1000">
            <div class="about-img"></div>
            <p v-html="langData['about-desc']"></p>
          </div>
          <div class="about-info" data-aos="fade-right" data-aos-delay="2000" data-aos-duration="1000">
            <div v-html="langData['about-info']"></div>
          </div>
        </div>
        
      </section>
      <section id="tech" class="view tech">
        <h2 class="view__title">Tech Stack</h2>
        <div class="contents-box">
            <div class="tech-inner-wrap" v-html="langData['tech-inner-wrap']" style="width: 100%;"></div>
        </div>
      </section>
      <section id="notes" class="view trouble">
        <h2 class="view__title trouble-title">Engineering Notes</h2>
        <div class="trouble-list"  v-html="langData['trouble-section']">
          
        </div>
      </section>
      <section id="project" class="view project">
        <h2 class="view__title">Project</h2>
        <div class="background"></div>
        <div class="contents-box swiper">
          <div class="swiper-wrapper" v-html="langData['project-section']"></div>

          <div class="swiper-pagination"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </section>
      <section id="lab" class="view game">
        <h2 class="view__title">LAB</h2>
        <div class="scroll-txt">↓ SCROLL DOWN HERE</div>
        <div class="contents-box">
          <div class="game-slider">
            <div class="game-slider__wrp swiper-wrapper">
              <!-- HANGMAN GAME -->
              <div class="game-slider__item swiper-slide">
                <div class="game-slider__img">
                  <img src="/images/mockup/hangman.jpg" alt="HANGMAN GAME">
                </div>
                <div class="game-slider__content">
                  <div  v-html="langData['lab-section-1']"></div>
                  <a href="/hangman/" class="btn game-slider__button" target="_blank" rel="noopener noreferrer">
                    <span>PLAY GAME</span>
                  </a>
                </div>
              </div>
              <!-- OCTOPUS GAME -->
              <div class="game-slider__item swiper-slide">
                <div class="game-slider__img">
                  <img src="/images/mockup/octopus.jpg" alt="OCTOPUS GAME">
                </div>
                <div class="game-slider__content">
                  <div v-html="langData['lab-section-2']"></div>
                  <a href="/octopus/" class="btn game-slider__button" target="_blank" rel="noopener noreferrer">
                    <span class="btn-text">PLAY GAME</span>
                  </a>
                </div>
              </div>
              <!-- TETRIS GAME -->
              <div class="game-slider__item swiper-slide">
                <div class="game-slider__img">
                  <img src="/images/mockup/tetris.jpg" alt="TETRIS GAME">
                </div>
                <div class="game-slider__content">
                  <div  v-html="langData['lab-section-3']" ></div>
                  <a href="/tetris/" class="btn game-slider__button" target="_blank" rel="noopener noreferrer">
                    <span>PLAY GAME</span>
                  </a>
                </div>
              </div>
      
            </div>
            <div class="game-slider__pagination"></div>
          </div>
        </div>
      </section>
    </main>
    <footer id="yy-ft" class="section sec-ft">
      <section class="view contact">
        <!-- <h2 class="view__title">contact</h2> -->
        <div class="sec-contents sec-contact">
          <div class="contact-desc">
            <h3 class="ft-feat">
              Let’s build<br>
              something<br>
              great together.<br>
            </h3>
            <h4 class="ft-subtext" style="margin-bottom: 1.5rem; color: #666; word-break: keep-all;">
              새로운 프로젝트, 협업 제안도 환영합니다.<br>
              언제든 편하게 메일을 남겨주세요!
            </h4>
            <h3>E-mail</h3>
            <h4>yududdl12@naver.com</h4>
            <h3>Download (PDF)</h3>
            <div style="margin-top:1rem;">
              <a 
                href="/YuyeongKwak_Resume_ko.pdf" download="Resume_ko.pdf"
                target="_blank"
                style="padding: 4px 10px; background:#cfff49; text-decoration: none; 
                font-size: 1.5rem; border: 2px solid #000;
                font-weight: bold; display: inline-flex; align-items: center; margin-right:1rem;"
              >
                📄이력서 (KO)
              </a>
              <a 
                href="/YuyeongKwak_Resume_en.pdf" download="Resume_en.pdf"
                target="_blank"
                style="padding: 4px 10px; background:#cfff49; text-decoration: none; 
                font-size: 1.5rem; border: 2px solid #000;
                font-weight: bold; display: inline-flex; align-items: center;"
              >
                📄Resume (EN)
              </a>
            </div>

          </div>
          <div class="contents-box glass-box">
            <div class="tools">
              <div class="circle">
                <span class="red box"></span>
              </div>
              <div class="circle">
                <span class="yellow box"></span>
              </div>
              <div class="circle">
                <span class="green box"></span>
              </div>
            </div>
            <div class="contact-form">
  <form name="contactForm" id="contactForm" action="https://formspree.io/f/xnpaerql" method="POST" autocomplete="off" class="clearfix">
    <div id="contactForm_term">
      <div class="agree_text" readonly>
        <fieldset class="contactForm_agree2">
          <label for="agree">개인정보 제공 및 활용 동의서<br>본인은 개인정보 보호법 제15조에 의거하여 본인의 개인정보(이메일)를 제공할 것을 동의합니다.</label>
          <input type="checkbox" name="agree" value="1" id="agree" required>
        </fieldset>
      </div>
    </div>

    <div id="contactForm_form" class="form_01">
      <div class="tbl_frm01 tbl_wrap">
        <ul>
          <li>
            <label for="contact_name" class="sound-only">이름 *</label>
            <input type="text" class="frm_input full_input required" id="contact_name" name="contact_name" title="Name" placeholder="Name" required>
          </li>
          <li>
            <label for="contact_email" class="sound-only">이메일 *</label>
            <input type="email" class="frm_input full_input required" id="contact_email" name="_replyto" title="Email" placeholder="Email" required>
          </li>
          <li>
            <label for="contact_subject" class="sound-only">제목 *</label>
            <input type="text" class="frm_input full_input required" id="contact_subject" name="_subject" title="Subject" placeholder="Subject" required>
          </li>
        </ul>
        <div class="contact-text">
          <label for="contact_message" class="sound-only">내용 *</label>
          <textarea id="contact_message" name="message" rows="3" title="Message" placeholder="저의 작업에 대해 궁금하신 점이 있으시면 언제든지 문의해 주세요." required></textarea>
          <input id="contact_submit" type="submit" class="btn_submit" value="SEND MAIL">
        </div>
      </div>
    </div>
  </form>
</div>
          </div>
        </div>
        <div class="ft-cover">
          <h2><span>Thank you for appreciating my portfolio!Thank you for appreciating my portfolio!Thank you for appreciating my portfolio!
              Thank you for appreciating my portfolio!Thank you for appreciating my portfolio!Thank you for appreciating my portfolio!Thank you for appreciating my portfolio!
              Thank you for appreciating my portfolio!Thank you for appreciating my portfolio!Thank you for appreciating my portfolio!
            </span></h2>
        </div>
      </section>
      <div class="ft-wrap">
        <h2>본 페이지는 상업적 목적이 아닌 개인 포트폴리오용으로 제작되었습니다.</h2>
        <p> © 2026 YU YEONG KWAK. All Rights Reserved.</p>
      </div>
    </footer>
    <button class="top-btn">
      TOP<span class="sound-only">위로가기</span>
    </button>
  </div>
</template>