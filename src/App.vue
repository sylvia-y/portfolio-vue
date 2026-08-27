<script setup>
import { onMounted } from 'vue'

// 인라인 이벤트 함수 핸들러
const changeLang = (lang) => {
  if (typeof window.changeLang === 'function') {
    window.changeLang(lang)
  }
}

onMounted(() => {
  // /js/main.js 동적 로드 (public/js/main.js 기준)
  const script = document.createElement('script')
  script.src = '/js/main.js'
  script.async = true
  document.body.appendChild(script)
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
              <a href="#about">ABOUT</a>
            </li>
            <li class="depth1">
              <a href="#tech">tech</a>
            </li>
            <li class="depth1">
              <a href="#project">PROJECT</a>
            </li>
            <li class="depth1">
              <a href="#game">game</a>
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
      <section id="home" class="view home sec-main">
        <div class="main-title area">
          <img class="logo" src="/images/intro_logo.png" alt="인트로_로고">
          <a class="scroll-link" href="#about">
            <img src="/images/wheel.png" alt="scroll">
          </a>
        </div>
        <div class="intro_smile">
          <img class="smile_img" src="/images/intro_smile.png" alt="인트로_스마일">
        </div>
      </section>
      <section id="about" class="view about">
        <div class="about-cover">
          <div class="cover">
            <h2><span>SOFTWARE DEVELOPER SOFTWARE DEVELOPER SOFTWARE DEVELOPER SOFTWARE DEVELOPER 
            SOFTWARE DEVELOPER SOFTWARE DEVELOPER SOFTWARE DEVELOPER SOFTWARE DEVELOPER SOFTWARE DEVELOPER 
            SOFTWARE DEVELOPER SOFTWARE DEVELOPER SOFTWARE DEVELOPER SOFTWARE DEVELOPER SOFTWARE DEVELOPER
            </span></h2>
          </div>
          <div class="cover">
            <h2><span>CREATIVE NIMBLE CREATIVE NIMBLE CREATIVE NIMBLE CREATIVE NIMBLE CREATIVE NIMBLE CREATIVE
                NIMBLE
                CREATIVE NIMBLE CREATIVE NIMBLE CREATIVE NIMBLE CREATIVE NIMBLE CREATIVE NIMBLE CREATIVE
                NIMBLE
              </span></h2>
          </div>
        </div>
        <h2 class="view__title">About</h2>

        <div class="contents-box">
          <div class="about-box js-tilt-containe" data-aos="fade-up" data-aos-delay="50" data-aos-duration="500">
            <div class="about-img"></div>
            <p data-key="about-desc">
              <strong><span>YU YEONG KWAK</span></strong><br>
              안녕하세요, <span>기술적 성장을 통해 가치 창출을 지향하는 곽유영</span>입니다.<br>
              사범계열 교육 배경을 기반으로 <span>사용자 중심 사고</span>와 <span>문제 정의 능력</span>을 체화했으며,
              업무 효율을 높이기 위한 기술 학습 과정에서 프로그래밍의 매력에 깊이 매료되어 개발자로 전향했습니다.<br>
              저는 단순히 요구사항을 구현하는 것을 넘어,<br>
              <span>코드 리팩토링을 통해 시스템의 지속 가능성을 최우선</span>하며<br>
              더 나은 구조는 없는지 끊임없이 고민합니다.<br>
              저의 <span>핵심 강점은 효율적인 협업 역량을 극대화</span>하는 것입니다.<br>
              개발자는 다른 부서들과도 함께 일해야 하므로,<br>
              <span>Notion과 같은 협업 툴</span>을 능숙하게 활용하여<br>
              <span>투명한 태스크 관리와 워크플로우 효율화</span>를 실현합니다.<br>
              기술적 숙련도와 더불어, <span>모두의 역량을 최대로 이끌어내는 협업 문화</span>를 통해<br>
              서비스의 완성도를 높이고 나날이 성장하는 개발자로 기여하겠습니다.
            </p>
          </div>
          <div class="about-info" data-aos="fade-right" data-aos-delay="500" data-aos-duration="500">
            <div data-key="about-info"></div>
          </div>
        </div>
      </section>
      <section id="tech" class="view tech">
        <h2 class="view__title">Tech Stack</h2>
        <div class="contents-box">
          <div class="tech-inner-wrap" data-key="tech-inner-wrap" style="width: 100%;"></div>
        </div>
      </section>
      <section id="project" class="view project">
        <h2 class="view__title">Project</h2>
        <div class="background"></div>
        <div class="contents-box swiper">
          <div class="swiper-wrapper" data-key="project-section"></div>

          <div class="swiper-pagination"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </section>
      <section id="game" class="view game">
        <h2 class="view__title">game</h2>
        <div class="scroll-txt">↓ SCROLL DOWN HERE </div>
        <div class="contents-box">
          <div class="game-slider">
            <div class="game-slider__wrp swiper-wrapper">
              <div class="game-slider__item swiper-slide">
                <div class="game-slider__img">
                  <img src="/images/mockup/hangman.jpg" alt="HANGMAN GAME">
                </div>
                <div class="game-slider__content">
                  <span class="game-slider__code">REACT를 사용하여 구현</span>
                  <div class="game-slider__title">HANGMAN GAME</div>
                  <div class="game-slider__text">
                    <p>여러명이 다 같이 할 수 있는 행맨게임</p>
                    <p>게임 시작 시 입력한 단어의 글자 수에 맞춰 폼이 생성됩니다.</p>
                    <p>게임 방법 : 한 명이 게임을 할 단어를 입력하면,<br>
                      다른 사람이 주어진 기회 안에<br>알파벳을 하나씩 선택해가며 단어를 맞춥니다.</p>
                  </div>
                  <a href="/hangman/" class="btn game-slider__button" target="_blank" rel="noopener noreferrer">
                    <span>GO TO PAGE</span>
                  </a>
                </div>
              </div>
              <div class="game-slider__item swiper-slide">
                <div class="game-slider__img">
                  <img src="/images/mockup/octopus.jpg" alt="OCTOPUS GAME">
                </div>
                <div class="game-slider__content">
                  <span class="game-slider__code">JS를 사용하여 구현</span>
                  <div class="game-slider__title">OCTOPUS GAME</div>
                  <div class="game-slider__text">
                    <p>문어가 먹물을 쏴서 상어를 제거하는 게임</p>
                    <p>게임 방법 : 방향키로 이동을 하며 space bar를 눌러<br>상어를 향해 먹물을 쏏니다.</p>
                    <p>수정사항 : 방어 가능한 아이템을 추가할 예정입니다.</p>
                  </div>
                  <a href="/octopus/" class="btn game-slider__button" target="_blank" rel="noopener noreferrer">
                    <span class="btn-text">GO TO PAGE</span>
                  </a>
                </div>
              </div>
              <div class="game-slider__item swiper-slide">
                <div class="game-slider__img">
                  <img src="/images/mockup/tetris.jpg" alt="TETRIS GAME">
                </div>
                <div class="game-slider__content">
                  <span class="game-slider__code">JS를 사용하여 구현</span>
                  <div class="game-slider__title">TETRIS GAME</div>
                  <div class="game-slider__text">
                    <p>방향키로 블럭을 움직이는 테트리스 게임</p>
                    <p>게임 방법 : space bar와 방향키를 사용하여<br>블럭을 맞추면 되는 게임입니다.</p>
                    <p>수정사항 : space bar를 계속 누르고 있을 경우<br>오류가 생기는 부분을 수정할 예정입니다.</p>
                  </div>
                  <a href="/tetris/" class="btn game-slider__button" target="_blank" rel="noopener noreferrer">
                    <span>GO TO PAGE</span>
                  </a>
                </div>
              </div>
              <div class="game-slider__item swiper-slide">
                <div class="game-slider__img">
                  <img src="/images/mockup/peng.jpg" alt="PENGUIN GAME">
                </div>
                <div class="game-slider__content">
                  <span class="game-slider__code">JS를 사용하여 구현</span>
                  <div class="game-slider__title">PENGUIN GAME</div>
                  <div class="game-slider__text">
                    <p>펭귄이 등장하는 얼음들을 점프하여 뛰어넘어가는 게임</p>
                    <p>게임 방법 : space bar를 눌러 얼음이 다가올 때 점프를 합니다.</p>
                    <p>수정사항 : 펭귄의 점프 속도와 범위를 수정할 예정입니다.</p>
                  </div>
                  <a href="https://portfolio-vue-tan.vercel.app/penguin" class="btn game-slider__button" target="_blank" rel="noopener noreferrer">
                    <span>GO TO PAGE</span>
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
        <h2 class="view__title">contact</h2>
        <div class="sec-contents sec-contact">
          <div class="contact-desc">
            <h3 class="ft-feat">
              If you<br>have any<br>
              questions,<br>
              please<br>feel free<br>
              to contact me.<br>
            </h3>
            <h3>E-mail</h3>
            <h4>yududdl12@naver.com</h4>
            <h3>Portfolio</h3>
            <h4>http://sylvia.dothome.co.kr</h4>
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
              Thank you for appreciating my portfolio!Thank you for appreciating my portfolio!Thank you for appreciating my portfolio
            </span></h2>
        </div>
      </section>
      <div class="ft-wrap">
        <h2>본 페이지는 상업적 목적이 아닌
          개인 포트폴리오용으로 제작되었습니다.</h2>
        <p> © 2026 YU-YEONG. All Rights Reserved.</p>
      </div>
    </footer>
    <button class="top-btn">
      TOP<span class="sound-only">위로가기</span>
    </button>
  </div>
</template>