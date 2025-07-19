// /pocketmode/main.js

const grid = document.getElementById("ballGrid");
const popup = document.getElementById("popup");
const resetBtn = document.getElementById("resetBtn");
const registBtn = document.getElementById("registBtn");
const ruleSelect = document.getElementById("ruleSelect");

let score1 = 0;
let score2 = 0;

const ballState = {};

function playSoundOverlap(src) {
  const sound = new Audio(src);
  sound.play().catch((e) => console.warn("音声再生エラー:", e));
}

function restartAnimation(el, className) {
  el.classList.remove("roll-left", "roll-right");
  void el.offsetWidth;
  el.classList.add(className);
  el.style.opacity = "1";
}

function toggleSettings() {
  const settings = document.getElementById("gameSettings");
  settings.style.display = settings.style.display === "none" ? "block" : "none";
}

function updateScoreDisplay() {
  document.getElementById("score1").textContent = score1;
  document.getElementById("score2").textContent = score2;
}

function showPopup(text) {
  popup.textContent = text;
  popup.style.display = "block";
  setTimeout(() => {
    popup.style.display = "none";
  }, 1000);
}

function updateMultiplierLabel(num) {
  const label = document.getElementById(`multi${num}`);
  const mult = ballState[num].multiplier;
  if (mult === 2) {
    label.textContent = "×2";
    label.style.display = "block";
    label.classList.remove("bounce");
    void label.offsetWidth;
    label.classList.add("bounce");
  } else {
    label.style.display = "none";
    label.classList.remove("bounce");
  }
}

function recalculateScores() {
  score1 = 0;
  score2 = 0;
  const rule = ruleSelect.value;

  for (let j = 1; j <= 9; j++) {
    const state = ballState[j];
    if (state.swiped && state.assigned) {
      let point = 0;
      if (rule === "A") {
        if (j === 9) point = 2;
        else if (j % 2 === 1) point = 1;
        point *= state.multiplier;
      } else if (rule === "B") {
        point = j === 9 ? 2 : 1;
      }
      if (state.assigned === 1) score1 += point;
      if (state.assigned === 2) score2 += point;
    }
  }
  updateScoreDisplay();
}

function resetAll() {
  score1 = 0;
  score2 = 0;
  updateScoreDisplay();
  for (let i = 1; i <= 9; i++) {
    const state = ballState[i];
    const wrapperEl = state.wrapper;
    wrapperEl.classList.remove("roll-left", "roll-right");
    wrapperEl.style.opacity = "0.5";
    state.swiped = false;
    state.assigned = null;
    state.multiplier = 1;
    updateMultiplierLabel(i);
  }
}

function updateLabels() {
  document.getElementById("label1").textContent = document.getElementById("player1").value || "Player 1";
  document.getElementById("label2").textContent = document.getElementById("player2").value || "Player 2";
}

function attachPlayerChangeListeners() {
  document.getElementById("player1").addEventListener("change", () => {
    updateLabels();
    localStorage.setItem("player1", document.getElementById("player1").value);
  });
  document.getElementById("player2").addEventListener("change", () => {
    updateLabels();
    localStorage.setItem("player2", document.getElementById("player2").value);
  });
  document.getElementById("shop").addEventListener("change", () => {
    localStorage.setItem("shop", document.getElementById("shop").value);
  });
}

function hideActions() {
  document.getElementById("postRegistActions").style.display = "none";
}

registBtn.addEventListener("click", () => {
  const payload = {
    score1,
    score2,
    balls: Object.fromEntries(
      Object.entries(ballState).map(([k, v]) => [
        k,
        { assigned: v.assigned, multiplier: v.multiplier }
      ])
    ),
    rule: document.getElementById("ruleSelect").value,
    shop: document.getElementById("shop").value,
    player1: document.getElementById("player1").value,
    player2: document.getElementById("player2").value
  };
  console.log("送信内容", payload);

  fetch("submit_v2.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload)
  })
    .then((res) => res.json())
    .then((data) => {
      showPopup(data.message || "登録しました！");
      resetAll();
      document.getElementById("postRegistActions").style.display = "flex";
    })
    .catch((err) => {
      console.error("送信エラー", err);
      showPopup("送信に失敗しました");
    });
});

// 初期データ読み込み
fetch("../api/fetch_master.php")
  .then(res => res.json())
  .then(data => {
    const shopSel = document.getElementById("shop");
    const p1Sel = document.getElementById("player1");
    const p2Sel = document.getElementById("player2");

    data.shops.forEach((shop) => {
      const option = document.createElement("option");
      option.value = shop;
      option.textContent = shop;
      shopSel.appendChild(option);
    });

    data.users.forEach((user, index) => {
      const opt1 = document.createElement("option");
      opt1.value = user.name;
      opt1.textContent = user.name;
      if (index === 0) opt1.selected = true;
      p1Sel.appendChild(opt1);

      const opt2 = document.createElement("option");
      opt2.value = user.name;
      opt2.textContent = user.name;
      if (index === 1) opt2.selected = true;
      p2Sel.appendChild(opt2);
    });

    // localStorage から履歴復元
    if (localStorage.getItem("player1")) {
      p1Sel.value = localStorage.getItem("player1");
    }
    if (localStorage.getItem("player2")) {
      p2Sel.value = localStorage.getItem("player2");
    }
    if (localStorage.getItem("shop")) {
      shopSel.value = localStorage.getItem("shop");
    }

    attachPlayerChangeListeners();
    updateLabels();
  });

// ボールUI生成
for (let i = 1; i <= 9; i++) {
  const wrapper = document.createElement("div");
  wrapper.classList.add("ball-wrapper");
  wrapper.style.opacity = "0.5";

  const img = document.createElement("img");
  img.src = `/images/ball${i}.png`; 
  img.classList.add("ball");
  img.dataset.number = i;

  const label = document.createElement("div");
  label.classList.add("ball-multiplier");
  label.id = `multi${i}`;
  label.textContent = "";
  label.style.display = "none";

  wrapper.appendChild(img);
  wrapper.appendChild(label);
  grid.appendChild(wrapper);

  ballState[i] = {
    swiped: false,
    assigned: null,
    multiplier: 1,
    wrapper: wrapper
  };

  let startX = null;

  const onStart = (clientX) => {
    startX = clientX;
  };

  const onEnd = (clientX) => {
    if (startX === null) return;
    const deltaX = clientX - startX;
    if (Math.abs(deltaX) < 30) return;

    const prevAssigned = ballState[i].assigned;
    const isSwiped = ballState[i].swiped;
    const wrapperEl = ballState[i].wrapper;

    if (!isSwiped) {
      if (deltaX < -30) {
        ballState[i].assigned = 1;
        restartAnimation(wrapperEl, "roll-left");
      } else if (deltaX > 30) {
        ballState[i].assigned = 2;
        restartAnimation(wrapperEl, "roll-right");
      }
      ballState[i].swiped = true;
      playSoundOverlap("sounds/swipe.mp3");
    } else {
      if (
        (prevAssigned === 1 && deltaX > 30) ||
        (prevAssigned === 2 && deltaX < -30)
      ) {
        ballState[i].assigned = null;
        ballState[i].swiped = false;
        wrapperEl.classList.remove("roll-left", "roll-right");
        wrapperEl.style.opacity = "0.5";
        playSoundOverlap("sounds/cancel.mp3");
      }
    }

    recalculateScores();
  };

  wrapper.addEventListener("touchstart", (e) => onStart(e.touches[0].clientX));
  wrapper.addEventListener("touchend", (e) => onEnd(e.changedTouches[0].clientX));
  wrapper.addEventListener("mousedown", (e) => onStart(e.clientX));
  wrapper.addEventListener("mouseup", (e) => onEnd(e.clientX));
  wrapper.addEventListener("click", () => {
    if (!ballState[i].swiped) return;
    ballState[i].multiplier = ballState[i].multiplier === 1 ? 2 : 1;
    updateMultiplierLabel(i);
    showPopup(ballState[i].multiplier === 2 ? "サイド（得点×2）" : "コーナー（得点×1）");
    playSoundOverlap("sounds/side.mp3");
    recalculateScores();
  });
}

resetBtn.addEventListener("click", () => {
  resetAll();
});

if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('service-worker.js')
    .then(() => console.log("Service Worker Registered"))
    .catch(err => console.error("SW registration failed:", err));
}
