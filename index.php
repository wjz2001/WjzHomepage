<!DOCTYPE html>
<html>

<head>

  <title>主页</title>

  <link href="img/logo.ico" rel="SHORTCUT ICON" />

  <meta charset="UTF-8">
  <meta content="zh-cn" http-equiv="Content-Language" />
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="theme-color" content="#F72405">

  <script src="./js/jquery.min.js"></script>
  <script src="./js/jquery.mousewheel.min.js"></script>
  <script src="./js/EventUtil.js"></script>
  <script src="./js/color-thief.umd.js"></script>
  <script src="./js/rgbtohex.js"></script>

  <?php
  $img_array = glob("img/background/*.{webp,jpg,png}", GLOB_BRACE);
  $img_array_json = json_encode($img_array);
  ?>

  <script>
    function search() {
      const search_bar = document.querySelector(".search_bar input");
      if (search_bar.value != "") {
        location.href = "https://www.baidu.com/s?from=1011440l&word=" + search_bar.value;
        search_bar.value = "";
      };
      return false;
    }

    const img_array = JSON.parse('<?php echo $img_array_json ?>');
    const img = new Image();
    img.crossOrigin = "Anonymous";
    img_number = Math.floor((Math.random() * img_array.length));
    img.src = img_array[img_number];
    const colorThief = new ColorThief();

    img.addEventListener("load", function () {
      const imgcolor = colorThief.getPalette(img, 5);
      const sRgb = "RGB(" + imgcolor[Math.floor(Math.random() * imgcolor.length)] + ")";
      document.querySelector("meta[name='theme-color']").setAttribute("content", sRgb.colorHex());
      document.documentElement.style.setProperty("--background", "url(" + img.src + ")");
      document.documentElement.style.setProperty("--background_color", sRgb.colorHex());
      document.querySelector(".bgimg img").src = img.src;
    });

    var bgimg, bookmarks_content, box_all;

    window.onload = function () {
      document.querySelector(".page").style.setProperty("animation-play-state", "running");
      $(".loading").fadeOut();

      bgimg = document.querySelector(".bgimg");
      bookmarks_content = document.querySelector(".bookmarks_content");
      box_all = document.querySelectorAll(".box");

      for (let i1 = 0; i1 < 3 - (box_all.length % 3); i1++) {
        const box_create = document.createElement("div");
        box_create.className = "box";
        bookmarks_content.appendChild(box_create);
      };

      box_all = document.querySelectorAll(".box");
      let i2 = 0;
      while (i2 < 2) {
        for (let i3 = 0; i3 < box_all.length; i3++) {
          bookmarks_content.appendChild(box_all[0 + i3].cloneNode(true));
        };
        i2++;
      };

      box_first = document.querySelectorAll(".box")[document.querySelectorAll(".box").length / 3];

      bookmarks_content.scrollTop = bookmarks_content.scrollHeight / 3;

      function bookmarks_scroll() {
        if (bookmarks_content.scrollTop >= bookmarks_content.scrollHeight / 3 * 2) {
          bookmarks_content.scrollTop = bookmarks_content.scrollHeight / 3;
        } else
          if (bookmarks_content.scrollTop === 0) {
            bookmarks_content.scrollTop = bookmarks_content.scrollHeight / 3;
          };
      };
      bookmarks_content.addEventListener("scroll", bookmarks_scroll);
      bookmarks_content.addEventListener("touchmove", bookmarks_scroll);

      var bookmarks_move, startY, endY;
      $(bookmarks_content).bind("touchstart", function (e) {
        startY = e.originalEvent.changedTouches[0].screenY;
      });

      $(bookmarks_content).bind("touchmove", function (e) {
        endY = e.originalEvent.changedTouches[0].screenY;

        if (startY != endY) {
          bookmarks_move = "true";
        };
      });

      $(bookmarks_content).mousewheel(function (event) {
        if (event.deltaY != 0) {
          bookmarks_move = "true";
        };
      });

      let timeOutEvent = 0;
      function bookmarks_reset() {
        timeOutEvent = 0;

        if (bookmarks_move == "true") {
          box_first.scrollIntoView({
            behavior: "smooth"
          });
        };

      };

      function bgimg_event_start(e) {
        e.preventDefault();
        timeOutEvent = setTimeout(bookmarks_reset, 700);
      };

      function bgimg_event_end(e) {
        clearTimeout(timeOutEvent);
        return false;
      };

      bgimg.addEventListener("touchstart", bgimg_event_start);
      bgimg.addEventListener("touchend", bgimg_event_end);
      bgimg.addEventListener("mousedown", bgimg_event_start);
      bgimg.addEventListener("mouseup", bgimg_event_end);

      var last_time = 0;
      function img_number_increase() {
        let now_time = new Date().valueOf();
        if (now_time - last_time > 2000) {
          img_number++;
          img.src = img_array[img_number];
          last_time = now_time;
        };
      };

      function img_number_decrease() {
        let now_time = new Date().valueOf();
        if (now_time - last_time > 2000) {
          img_number--;
          img.src = img_array[img_number];
          last_time = now_time;
        };
      };

      document.querySelectorAll(".body_blanks")[1].addEventListener("click", img_number_increase);
      EventUtil.bindEvent(document.querySelectorAll(".body_blanks")[1], "click", img_number_increase);

      document.querySelectorAll(".body_blanks")[0].addEventListener("click", img_number_decrease);
      EventUtil.bindEvent(document.querySelectorAll(".body_blanks")[0], "click", img_number_decrease);

      EventUtil.bindEvent(bgimg, "swipeleft", img_number_increase);
      EventUtil.bindEvent(bgimg, "swiperight", img_number_decrease);
    };
  </script>

  <style>
    html,
    body {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden;
      transition: background 1s ease-in-out;
    }

    @media screen and (orientation: landscape) {

      body {
        position: relative;
        background: var(--background_color);
        display: flex;
      }

      body::before {
        content: "";
        inset: 0;
        position: absolute;
        z-index: -1;
        background: var(--background);
        background-size: cover;
        filter: blur(10px);
      }

      body:has(.search_bar:focus-within)::before {
        filter: none;
        transition: filter 0.3s linear;
      }

      body:has(.search_bar:focus-within) .bgimg {
        filter: blur(5px);
        transition: filter 0.3s linear;
      }

      .body_blanks {
        display: block;
        flex: 1;
      }
    }

    @layer {
      .body_blanks {
        display: none;
      }
    }

    img {
      pointer-events: none;
    }

    .loading {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: white;
    }

    .container {
      width: 150px;
      height: 150px;
      padding: 30px;
      box-sizing: border-box;
      border-radius: 15px;
      display: flex;
      flex-wrap: wrap;
      box-shadow: 0 12px 20px 6px #68707666;
      transform-style: preserve-3d;
      transform: rotateX(45deg) rotatez(45deg);
    }

    .square {
      flex-basis: 30px;
      background: #F72405;
      position: relative;
      transform-style: preserve-3d;
      transform: translateZ(30px);
      box-shadow: 90px 90px 15px #00000066;
      animation: beating 1s infinite;
      animation-delay: calc(0.05s * var(--d));
    }

    @keyframes beating {
      50% {
        transform: translateZ(-5px);
        background: orange;
      }
    }

    .square::before {
      content: "";
      position: absolute;
      width: 30px;
      height: 30px;
      left: 0;
      top: 0;
      background: #663731;
      transform: rotateY(-90deg);
      transform-origin: right center;
    }

    .square::after {
      content: "";
      position: absolute;
      width: 30px;
      height: 30px;
      left: 0;
      top: 0;
      background: #823327;
      transform: rotateX(90deg);
      transform-origin: right bottom;
    }

    .page {
      width: 100%;
      height: 100%;
      overflow: hidden;
      background: var(--background_color);
      display: flex;
      flex-direction: column;
      animation: move 0.5s ease-in-out 1 paused;
    }

    @media screen and (orientation: landscape) {
      .page {
        aspect-ratio: 9 / 18;
        height: 100%;
        width: auto;
        margin: 0 auto;
        box-shadow: 0 0 15px black;
        border-radius: 15px;
      }
    }

    @keyframes move {
      0% {
        transform: translate3d(0, 100%, 0);
      }
    }

    .search_bar {
      padding: 30px 0 40px;
      text-align: center;
      order: 1;
    }

    @media (pointer: coarse) {
      .search_bar:focus-within {
        position: relative;
        z-index: 1;
        padding: 0;
        order: 0;
      }

      .search_bar:focus-within~div {
        filter: blur(5px);
        transition: filter 0.3s ease-in-out;
        pointer-events: none;
      }
    }

    .search_bar input {
      line-height: 1.8em;
      width: 84%;
      border: none;
      font-size: 0.85em;
      text-align: center;
      background: #eeeeee;
      border-radius: 15px;
      outline: 1.0em;
      color: #666666;
    }

    @media (pointer: coarse) {
      .search_bar input:focus {
        text-align: left;
        border-radius: 0;
        width: 100%;
        padding: 10px 10px;
      }
    }

    .bgimg {
      position: relative;
    }

    .bgimg::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(transparent 50%, var(--background_color) 95%);
    }

    .bgimg img {
      vertical-align: top;
      width: 100%;
    }

    .bookmarks {
      position: relative;
      flex: 1;
      overflow: hidden;
    }

    .bookmarks:active::before {
      background: transparent;
    }

    .bookmarks::before {
      content: "";
      position: absolute;
      inset: 0;
      z-index: 1;
      pointer-events: none;
      background: linear-gradient(var(--background_color), transparent 10%, transparent 90%, var(--background_color));
    }

    .bookmarks_content {
      width: 100%;
      height: 100%;
      overflow-y: scroll;
      scroll-snap-type: y;
      display: grid;
      grid-template-columns: repeat(3, 33.33%);
      grid-template-rows: repeat(3, 33.33%);
      grid-auto-rows: 33.33%;
      overflow: -moz-scrollbars-none;
    }

    .bookmarks_content::-webkit-scrollbar {
      display: none;
    }

    .box {
      scroll-snap-align: start;
    }

    .box a {
      height: 100%;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .box a:active {
      backdrop-filter: brightness(80%);
    }

    .box img {
      width: 55px;
      filter: drop-shadow(2px 2px 2px #727272);
    }

    .box p {
      color: #ffffff;
      text-shadow: 2px 2px 2px #727272;
      line-height: 1.5em;
      font-size: 0.75em;
      white-space: nowrap;
      margin: 0;
      padding: 3px;
    }
  </style>
</head>

<body>

  <div class="loading">
    <div class="container">
      <div class="square" style="--d: 1"></div>
      <div class="square" style="--d: 2"></div>
      <div class="square" style="--d: 3"></div>
      <div class="square" style="--d: 4"></div>
      <div class="square" style="--d: 5"></div>
      <div class="square" style="--d: 6"></div>
      <div class="square" style="--d: 7"></div>
      <div class="square" style="--d: 8"></div>
      <div class="square" style="--d: 9"></div>
    </div>
  </div>

  <div class="body_blanks"></div>

  <div class="page">

    <form class="search_bar" onsubmit="return search()">
      <input type="text" placeholder="百度一下，你就上当" autocomplete="off" />
    </form>

    <div class="bgimg">
      <img src="" crossorigin="anonymous" />
    </div>

    <div class="bookmarks">

      <div class="bookmarks_content">

        <div class="box">
          <a href="https://www.twitter.com">
            <img src="img/twitter.png" />
            <p>Twitter</p>
          </a>
        </div>

        <div class="box">
          <a href="http://bbs.nga.cn/thread.php?fid=540">
            <img src="img/nga.png" />
            <p>NGAFGO版</p>
          </a>
        </div>

        <div class="box">
          <a href="https://fgo.wiki/w/首页">
            <img src="img/fgowiki.png" />
            <p>MoonCell</p>
          </a>
        </div>

        <div class="box">
          <a href="https://www.facebook.com">
            <img src="img/facebook.png" />
            <p>Facebook</p>
          </a>
        </div>

        <div class="box">
          <a href="https://w.linovelib.com">
            <img src="img/bililight.png" />
            <p>哔哩轻小说</p>
          </a>
        </div>

        <div class="box">
          <a href="https://m.manhuagui.com/user/book/shelf">
            <img src="img/manhuagui.png" />
            <p>漫画柜</p>
          </a>
        </div>

        <div class="box">
          <a href="https://syosetu.com/favnovelmain18/list/?nowcategory=1">
            <img src="img/成为小说家吧R18.png" />
            <p>成为小说家吧X</p>
          </a>
        </div>

        <div class="box">
          <a href="https://syosetu.com/favnovelmain/list/?nowcategory=1">
            <img src="img/成为小说家吧.png" />
            <p>成为小说家吧</p>
          </a>
        </div>

        <div class="box">
          <a href="https://kakuyomu.jp/users/wswjzColby/following_works">
            <img src="img/カクヨム.png" />
            <p>カクヨム</p>
          </a>
        </div>

        <div class="box">
          <a href="https://www.guokr.com">
            <img src="img/guokr.png" />
            <p>果壳网</p>
          </a>
        </div>

      </div>

    </div>

  </div>

  <div class="body_blanks"></div>

</body>

</html>