async function changeLang(lang) {
  try {
    const module = await import(`/js/lang/${lang}.js`);
    const data = module.default;

    document.querySelectorAll("[data-key]").forEach(el => {
      const key = el.getAttribute("data-key");
      if (data[key]) el.innerHTML = data[key];
    });

    localStorage.setItem("lang", lang);
  } catch (err) {
    console.error("언어 파일 로드 실패:", err);
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const savedLang = localStorage.getItem("lang") || "ko";
  changeLang(savedLang);
});
