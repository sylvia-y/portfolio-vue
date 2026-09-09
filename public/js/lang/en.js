export default {

  /* ---------------- ABOUT ---------------- */

  "about-title": "About",

  "about-desc": `
    <strong><span>YU YEONG KWAK ( 곽 유 영 )</span></strong><br>
    Hello, I’m Yu Yeong Kwak, a developer who <span>identifies problems in real-world services and improves user experiences and development environments through technology.</span><br>
    I am a <span>4-year developer</span> with experience developing production services and administrative systems.<br>
    I have worked with React / Vue / Remix and Java / Spring Boot / PHP, covering state management, SSR, REST APIs, real-time communication, database design and optimization.
    <span>Recently, I have been expanding my technical scope into AI-powered automation and service development.</span>
  `,

  "about-info": `
    <div class="about_info_box">
      <p>2025.12 ~ 2026.05</p>
      <p class="about_info_comp">Wavecode Co., Ltd.<span>(Development Team - Developer)</span></p>
    </div>

    <div class="about_info_box">
      <p>2025.06 ~ 2025.09</p>
      <p class="about_info_comp">DoubleDown Games (Malaysia)<span>(Advanced Development Team - Developer)</span></p>
    </div>

    <div class="about_info_box">
      <p>2024.05 ~ 2025.06</p>
      <p class="about_info_comp">Chabot Insurance<span>(R&D Team - Developer)</span></p>
    </div>

    <div class="about_info_box">
      <p>2023.11 ~ 2024.05</p>
      <p class="about_info_comp">EduCompany<span>(Content Development Team - Developer)</span></p>
    </div>

    <div class="about_info_box">
      <p>2022.09 ~ 2023.02</p>
      <p class="about_info_comp">UI/UX-based Web Publishing & Frontend Development Program</p>
    </div>

    <div class="about_info_box">
      <p>2021.03 ~ 2022.02</p>
      <p class="about_info_comp">CBS Academy for Gifted Education<span>(Creativity & Language Teacher)</span></p>
    </div>
  `,


  /* ---------------- TECH STACK ---------------- */

  "tech-inner-wrap": `

    <!-- FRONT END -->

    <div class="tech-inner" data-aos="fade-up" data-aos-delay="50">

      <h3>FRONT END</h3>

      <p class="front-end">
        Component architecture with React / TypeScript and
        state management using Zustand and React Query.
        Experienced with SSR, Native WebSocket,
        large-scale data grids, and pnpm Monorepo environments.
      </p>

      <ul class="tech-wrap">

        <li>

          <div class="tech-card"><div class="tech-img tech-img5"></div><h4>React</h4></div>

          <div class="tech-card"><div class="tech-img tech-img6"></div><h4>JavaScript</h4></div>

          <div class="tech-card"><div class="tech-img tech-img14"></div><h4>Vue</h4></div>

          <div class="tech-card"><div class="tech-img tech-img7"></div><h4>TypeScript</h4></div>

          <div class="tech-card"><div class="tech-img tech-img8"></div><h4>Sass</h4></div>

        </li>

      </ul>

    </div>


    <!-- BACK END -->

    <div class="tech-inner" data-aos="fade-up" data-aos-delay="150">

      <h3>BACK END</h3>

      <p class="back-end">
        Experience developing REST APIs with Java / Spring Boot
        and maintaining services built with PHP MVC / CodeIgniter.
        I have worked across the full data flow,
        from implementing server-side logic to integrating APIs with the frontend.
      </p>

      <ul class="tech-wrap">

        <li>

          <div class="tech-card"><div class="tech-img tech-img12"></div><h4>PHP</h4></div>

          <div class="tech-card"><div class="tech-img tech-img9"></div><h4>Java</h4></div>

          <div class="tech-card"><div class="tech-img tech-img16"></div><h4>Thymeleaf</h4></div>

          <div class="tech-card"><div class="tech-img tech-img13"></div><h4>JSP</h4></div>

        </li>

      </ul>

    </div>


    <!-- DATABASE & API -->

    <div class="tech-inner" data-aos="fade-up" data-aos-delay="250">

      <h3>DATABASE & API</h3>

      <p class="back-end">
        MySQL database schema design and optimization of complex
        JOIN queries and indexes. RESTful API communication
        and data flow design between frontend and backend.
      </p>

      <ul class="tech-wrap">

        <li>

          <div class="tech-card"><div class="tech-img tech-img17"></div><h4>MySQL</h4></div>

          <div class="tech-card"><div class="tech-img tech-img18"></div><h4>Redis</h4></div>

          <div class="tech-card"><div class="tech-img tech-img20"></div><h4>REST API</h4></div>

        </li>

      </ul>

    </div>


    <!-- DEVOPS & TOOLS -->

    <div class="tech-inner" data-aos="fade-up" data-aos-delay="350">

      <h3>DEVOPS & COWORK</h3>

      <p class="back-end">
        Version control using Git / GitLab and Lazygit.
        Familiar with Linux / AWS server environments
        and collaborative workflows using Notion.
      </p>

      <ul class="tech-wrap">

        <li>

          <div class="tech-card"><div class="tech-img tech-img21"></div><h4>Git/GitLab</h4></div>

          <div class="tech-card"><div class="tech-img tech-img22"></div><h4>AWS</h4></div>

          <div class="tech-card"><div class="tech-img tech-img23"></div><h4>Linux</h4></div>

          <div class="tech-card"><div class="tech-img tech-img24"></div><h4>Notion</h4></div>

        </li>

      </ul>

    </div>


    <!-- DESIGN -->

    <div class="tech-inner" data-aos="fade-up" data-aos-delay="450">

      <h3>DESIGN</h3>

      <p class="design">
        UI/UX visual reference interpretation and interface implementation
        using Figma, Photoshop, and Illustrator, with a focus on
        visual asset alignment and consistency.
      </p>

      <ul class="tech-wrap">

        <li>

          <div class="tech-card"><div class="tech-img tech-img15"></div><h4>Figma</h4></div>

          <div class="tech-card"><div class="tech-img tech-img1"></div><h4>Photoshop</h4></div>

          <div class="tech-card"><div class="tech-img tech-img2"></div><h4>Illustrator</h4></div>

        </li>

      </ul>

    </div>

  `,


  /* ---------------- Engineering Notes ---------------- */

  "trouble-title": "Engineering Notes",

  "trouble-section": `

    <!-- Issue 1 -->

    <article class="trouble-card">

      <div class="trouble-card-inner">

        <span>01</span>

        <h3 class="trouble-card-title">
          Large-Scale Admin Grid Rendering Performance Optimization
        </h3>

      </div>

      <p class="trouble-card-sub">[Problem]</p>

      <p class="trouble-card-desc">
        Rendering tens of thousands of statistical records directly into the DOM
        on an administrative platform and chart system caused blocking,
        severe memory usage, and UI lag.
      </p>

      <p class="trouble-card-sub">[Solution & Outcome]</p>

      <p class="trouble-card-desc">
        Applied Tabulator's Virtual DOM technology to dynamically render
        only the data within the visible viewport.
        Improved UI rendering performance in large-scale data environments.
      </p>

    </article>


    <!-- Issue 2 -->

    <article class="trouble-card">

      <div class="trouble-card-inner">

        <span>02</span>

        <h3 class="trouble-card-title">
          State Synchronization Issues During Language & Global Setting Changes
        </h3>

      </div>

      <p class="trouble-card-sub">[Problem]</p>

      <p class="trouble-card-desc">
        When switching languages or role-based menus,
        reactive updates between frontend components were sometimes lost,
        leaving stale language or permission-related data.
      </p>

      <p class="trouble-card-sub">[Solution & Outcome]</p>

      <p class="trouble-card-desc">
        Managed language and user settings with Zustand-based global state,
        and synchronized multilingual and session state between the client
        and server using Cookie and Redis Session.
        Applied the Selector pattern so that only the components
        affected by state changes would re-render,
        improving data consistency.
      </p>

    </article>


    <!-- Issue 3 -->

    <article class="trouble-card">

      <div class="trouble-card-inner">

        <span>03</span>

        <h3 class="trouble-card-title">
          Improving API Response Latency Caused by Backend Database Bottlenecks
        </h3>

      </div>

      <p class="trouble-card-sub">[Problem]</p>

      <p class="trouble-card-desc">
        CRM/ERP systems and settlement APIs took more than 3 seconds
        to respond due to complex JOIN queries and missing indexes,
        frequently resulting in timeout errors.
      </p>

      <p class="trouble-card-sub">[Solution & Outcome]</p>

      <p class="trouble-card-desc">
        Analyzed Slow Query logs and execution plans,
        added composite indexes, and refactored subqueries
        and aggregation logic to reduce average response time.
      </p>

    </article>


    <!-- Issue 4 -->

    <article class="trouble-card">

      <div class="trouble-card-inner">

        <span>04</span>

        <h3 class="trouble-card-title">
          Shared UI Package Bundling Issues in a pnpm Monorepo
        </h3>

      </div>

      <p class="trouble-card-sub">[Problem]</p>

      <p class="trouble-card-desc">
        During monorepo setup, the shared component package (@repo/ui)
        experienced missing styles and module resolution failures
        when integrated with the main application.
      </p>

      <p class="trouble-card-sub">[Solution & Outcome]</p>

      <p class="trouble-card-desc">
        Standardized the 'exports' field in 'package.json',
        reconfigured TypeScript 'paths' mappings,
        and organized the build pipeline so that sub-applications
        could reliably reference shared modules
        in an independent and isolated environment.
      </p>

    </article>

  `,


  /* ---------------- PROJECTS ---------------- */

  "project-section": `

    <!-- 00. AI Sales Analyzer -->

    <div class="layer swiper-slide sales-analyzer-slide">

      <div class="item">

        <h3 class="pro_title">

          [AI SALES ANALYZER]<br>

          CSV-Based Sales Data Analysis System

        </h3>

        <div class="swiper-center">

          <div class="desc">

            <div class="desc-card">

              <span class="badge">Overview</span><br>

              <strong>
                A data analysis system that automatically analyzes
                and visualizes sales data using Python and Pandas
                after a CSV file is uploaded.
              </strong>

              <br><br>

              <span class="badge">Role & Contribution</span><br>

              <strong>
                Developer (100% contribution)
              </strong>

              <br><br>

              <span class="badge">Tech Stack</span><br>

              <strong>
                Vue 3, Python, FastAPI, Pandas, Chart.js
              </strong>

            </div>

          </div>


          <!-- Sales Analyzer Live Demo -->

          <div class="desc sales-analyzer-demo">

            <div class="desc-card">

              <span class="badge">💻 LIVE DEMO</span>

              <div id="sales-analyzer-mount"></div>

            </div>

          </div>


          <div class="desc">

            <div class="desc-card">

              <span class="badge">🏗️ Data Processing Flow</span>

              <div class="diagram-wrap">

<pre>

[CSV Upload]

      │

      ▼

[FastAPI]

      │

      ▼

[Python / Pandas]

      │

      ├─ Revenue Calculation

      ├─ Daily Sales Aggregation

      └─ Product Sales Aggregation

      │

      ▼

[JSON REST API]

      │

      ▼

[Vue 3]

      │

      ▼

[Chart.js Visualization]

</pre>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>


    <!-- 01. Clinic Website & Admin System -->

    <div class="layer swiper-slide">

      <div class="item">

        <h3 class="pro_title">
          [apps/snow-gangnam]<br>
          Clinic Website & Administrative System
        </h3>

        <div class="swiper-center">


          <!-- 1. Basic Information -->

          <div class="desc">

            <div class="desc-card">

              <span class="badge">Overview</span><br>

              <strong>
                A comprehensive clinic web and administrative platform
                featuring EMR chart integration, before-and-after treatment comparison,
                multilingual support, and role-based access control.
              </strong>

              <br><br>

              <span class="badge">Role & Contribution</span>
              <strong>Developer (100%)</strong>

              <br><br>

              <span class="badge">Tech Stack</span>
              <strong>
                React, Remix, TypeScript, CodeIgniter 4 (PHP), MySQL
              </strong>

            </div>

          </div>


          <!-- 2. Core Implementation 1: Bundle Isolation & Hydration Guard -->

          <div class="desc">

            <div class="desc-card">

              <span class="badge">
                💻 Remix Bundle Isolation & Hydration Guard (React)
              </span><br>

              <small>
                Isolated server-only modules through dynamic imports
                and safely bound UI after client mount to prevent SSR mismatches.
              </small>

              <div class="code-block-wrap">

                <pre>
                export const loader = async ({ request }: LoaderFunctionArgs) => {
                  const { getEventData } = await import('~/services/event.server'); // Server bundle isolation
                  return json({ events: await getEventData(request) });
                };

                export default function EventComponent() {
                  const [isClient, setIsClient] = useState(false);
                  useEffect(() => setIsClient(true), []); // Detect client mount
                  if (!isClient) return &lt;EventSkeleton /&gt;;
                  return &lt;div className="event-grid"&gt;{/* Render event list */}&lt;/div&gt;;
                }
                </pre>

              </div>

            </div>

          </div>


          <!-- 3. Core Implementation 2: UI/UX Renewal & Layout Optimization -->

          <div class="desc">

            <div class="desc-card">

              <span class="badge">
                🎨 Reservation Flow & My Page UI/UX Redesign (Before & After)
              </span><br>

              <small>
                Reorganized complex calendar pickers, product selection,
                and reservation modification layouts into card-based interfaces
                to improve information visibility and reduce user drop-off.
              </small>

              <div class="img-wrap" style="margin-top: 12px; text-align: center;">

                <img
                  src="/images/wavecode_ui.png"
                  alt="Before and after UI improvements"
                  style="max-width: 90%; height: auto; border-radius: 8px; border: 1px solid #eee;"
                >

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>


    <!-- 02. REDIS RANKING -->

    <div class="layer swiper-slide">

      <div class="item">

        <h3 class="pro_title">
          [REDIS RANKING]<br>
          Real-Time Ranking System with Sorted Sets
        </h3>

        <div class="swiper-center">

          <div class="desc">

            <div class="desc-card">

              <span class="badge">Overview</span><br>

              <strong>
                A real-time ranking system using
                Redis Sorted Set (ZSET) for score updates
                and rank lookups.
              </strong>

              <br><br>

              <span class="badge">Role & Contribution</span><br>

              <strong>
                Developer (100% contribution)
              </strong>

              <br><br>

              <span class="badge">Tech Stack</span><br>

              <strong>
                Java 17, Spring Boot 3.x, Redis (ZSET), Docker, AWS Lightsail
              </strong>

            </div>

          </div>


          <div class="desc">

            <div class="desc-card">

              <span class="badge">🏗️ Data Structure & Pipeline</span>

              <div class="diagram-wrap">

<pre>

[User Action / Score Event]

  │

[Spring Boot Service]

  │──&gt; ZADD leaderboards:daily &lt;score&gt; &lt;user_id&gt;  (Score Update)

  │──&gt; ZREVRANK leaderboards:daily &lt;user_id&gt;     (Real-Time Rank Lookup)

  └─&gt; └─&gt; ZREVRANGE leaderboards:daily 0 N-1 WITHSCORES (Top N Ranking List)

</pre>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>


    <!-- 03. iGaming Platform & Admin System -->

    <div class="layer swiper-slide">

      <div class="item">

        <h3 class="pro_title">

          [DoubleDown Games]<br>

          iGaming Platform & Administrative System

        </h3>

        <div class="swiper-center">


          <!-- 01. Overview -->

          <div class="desc">

            <div class="desc-card">

              <span class="badge">Overview</span><br>

              <strong>
                An iGaming platform and administrative system
                handling large-scale real-time game and betting data.
              </strong>

              <br><br>

              <span class="badge">Role</span><br>

              <strong>Frontend Developer</strong>

              <br><br>

              <span class="badge">Tech Stack</span><br>

              <strong>
                React, TypeScript, Vite, Native WebSocket,
                Zustand, React Query, Tabulator.js
              </strong>

            </div>

          </div>


          <!-- 02. Large-Scale Data -->

          <div class="desc">

            <div class="desc-card">

              <span class="badge">🏗️ Large-Scale Data Rendering</span>

              <div class="diagram-wrap">

<pre>

[10,000+ Rows JSON Data]

        │

        ▼

[Tabulator Virtual DOM]

        │

        ├─ Render only required rows into the DOM

        ├─ Dynamic rendering based on scrolling

        └─ Format / Filter processing

</pre>

              </div>

              <strong>
                Reduced UI rendering overhead and handled
                large-scale administrative data by using
                Tabulator Virtual DOM.
              </strong>

            </div>

          </div>


          <!-- 03. Real-Time Communication -->

          <div class="desc">

            <div class="desc-card">

              <span class="badge">⚡ Real-Time Communication Architecture</span>

              <div class="diagram-wrap">

<pre>

[SockJS / STOMP]

        ↓

  Communication Refactoring

        ↓

[Native WebSocket]

        ↓

[JSON Message]

        ↓

[Event Handler]

        ↓

[Real-Time UI Update]

</pre>

              </div>

              <strong>
                Migrated from SockJS/STOMP-based communication
                to Native WebSocket to directly process game results
                and betting data, simplifying the real-time communication architecture.
              </strong>

            </div>

          </div>


          <!-- 04. State Management -->

          <div class="desc">

            <div class="desc-card">

              <span class="badge">🧩 State Management</span>

              <div class="diagram-wrap">

<pre>

[REST API] ──→ [React Query] ──→ [UI]

                  Server State

[WebSocket] ──→ [Zustand] ──→ [UI]

                 Client State

</pre>

              </div>

              <strong>
                Separated server state and client state
                using React Query and Zustand,
                enabling more efficient data flow management.
              </strong>

            </div>

          </div>


          <!-- 05. Key Responsibilities & Achievements -->

          <div class="desc">

            <div class="desc-card">

              <span class="badge">Key Responsibilities</span>

              <br><br>

              <strong>
                • Designed React-based component and feature-level modular architecture
              </strong>

              <br><br>

              <strong>
                • Implemented and optimized a 10,000+ row large-scale data grid
              </strong>

              <br><br>

              <strong>
                • Migrated SockJS/STOMP → Native WebSocket
                and implemented real-time event handling
              </strong>

              <br><br>

              <strong>
                • Separated client and server state
                using Zustand / React Query
              </strong>

            </div>

          </div>

        </div>

      </div>

    </div>


    <!-- 04. EduCompany Website & SEO -->

    <div class="layer swiper-slide">

      <div class="item">

        <h3 class="pro_title">
          [EduCompany]<br>
          Corporate Website & Search Engine Optimization (SEO)
        </h3>

        <div class="swiper-center">


          <div class="desc">

            <div class="desc-card">

              <span class="badge">Overview</span><br>

              <strong>
                Improved corporate website search visibility
                through semantic markup restructuring
                and integration with search engine webmaster tools.
              </strong>

              <br><br>

              <span class="badge">Role</span><br>

              <strong>Developer (100% contribution)</strong>

              <br><br>

              <span class="badge">Tech Stack</span><br>

              <strong>
                HTML5, CSS3, JavaScript, PHP, Naver Search Advisor
              </strong>

              <br><br>

              <a
                href="http://www.educompany.co.kr/"
                target="_blank"
                rel="noopener noreferrer"
                class="btn-gopage"
              >
                Go Page 🔗
              </a>

            </div>

          </div>


          <div class="desc">

            <div class="desc-card">

              <span class="badge">
                🏗️ Architecture Flow: Semantic Structure & Search Indexing Optimization
              </span>

              <div class="diagram-wrap">

<pre>

[HTML5 Website Layout]

  │

  ├─ 1. Restructured semantic tags:
  │      &lt;header&gt;, &lt;main&gt;, &lt;article&gt;, &lt;nav&gt;

  ├─ 2. Created sitemap.xml & robots.txt
  │

[Naver Search Advisor (Webmaster Tools)]

  ├─ 3. Site ownership verification
  │      (HTML Tag Injection) & Sitemap submission

  └─ 4. Monitored crawling status & requested indexing
         ──&gt; Improved Naver search visibility

</pre>

              </div>

            </div>

          </div>


          <div class="desc">

            <div class="desc-card">

              <span class="badge">📄 Search Crawler Rules (robots.txt)</span><br>

              <small>
                Built access rules and a sitemap so that Naver
                and major search engine crawlers could correctly
                discover and index the latest pages.
              </small>

            </div>

          </div>


          <div class="desc">

            <div class="desc-card">

              <span class="badge">💡 Key Improvements</span><br>

              <strong>
                - Semantic markup conversion: Improved web accessibility
                and made the document structure easier for search crawlers
                to understand by replacing non-standard tags.
              </strong>

              <br>

              <strong>
                - Naver Search Advisor integration: Submitted the sitemap,
                optimized robots.txt, and managed crawl requests
                to improve Naver search indexing.
              </strong>

            </div>

          </div>

        </div>

      </div>

    </div>


    <!-- Security & Asset Protection Policy -->

    <div class="security-notice">

      🔒 <strong>Security & Asset Protection Notice:</strong>
      Due to security policies, actual production screens are not publicly displayed.
      Instead, key architecture diagrams and problem-solving code snippets
      designed and written by me are provided to demonstrate my technical capabilities.

    </div>

  `,


  /* ---------------- LAB ---------------- */

  "lab-section-1": `

    <span class="game-slider__code">React · State Management</span>

    <div class="game-slider__title">HANGMAN GAME</div>

    <div class="game-slider__text">

      <p>
        A thrilling word-guessing game to enjoy with friends!
      </p>

      <p>
        When the host enters a word,
        input fields are dynamically generated
        based on the number of letters.
      </p>

      <p>
        <strong>HOW TO PLAY</strong><br>

        Guess the letters one by one
        and complete the word within the limited number of attempts.
      </p>

    </div>

  `,


  "lab-section-2": `

    <span class="game-slider__code">JavaScript · Canvas API</span>

    <div class="game-slider__title">OCTOPUS GAME</div>

    <div class="game-slider__text">

      <p>
        Defeat the sharks underwater in this arcade shooting game!
      </p>

      <p>
        Dodge approaching sharks and fire ink
        for an immersive arcade-style experience.
      </p>

      <p>
        <strong>HOW TO PLAY</strong><br>

        <code>Arrow Keys</code> Move /
        <code>Spacebar</code> Fire Ink<br>

      </p>

    </div>

  `,


  "lab-section-3": `

    <span class="game-slider__code">Vanilla JS · Game Logic</span>

    <div class="game-slider__title">TETRIS GAME</div>

    <div class="game-slider__text">

      <p>
        A classic reimagined with pure JavaScript.
      </p>

      <p>
        Implemented grid-based optimization
        and block collision detection logic
        to recreate the core mechanics of the original game.
      </p>

      <p>
        <strong>HOW TO PLAY</strong><br>

        <code>Arrow Keys</code> Rotate & Move /
        <code>Spacebar</code> Hard Drop<br>

      </p>

    </div>

  `,

};