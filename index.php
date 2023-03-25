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
  <script src="./js/jquery.mousewheel.js"></script>
  <script src="./js/jquerymobile-swipeupdown.js"></script>
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
    img.src = img_array[Math.floor((Math.random() * img_array.length))];
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

      bookmarks_content.scrollTop = bookmarks_content.scrollHeight / 3;

      let bookmarks_direction2;
      function bookmarks_scroll() {
        if (bookmarks_content.scrollTop + bookmarks_content.clientHeight + 1 >= bookmarks_content.scrollHeight) {
          bookmarks_content.scrollTop = bookmarks_content.scrollTop - bookmarks_content.scrollHeight / 3;
          bookmarks_direction2 = "down";
        };

        if (bookmarks_content.scrollTop === 0) {
          bookmarks_content.scrollTop = bookmarks_content.scrollHeight / 3;
          bookmarks_direction2 = "up";
        };
      };
      bookmarks_content.addEventListener("scroll", bookmarks_scroll);
      bookmarks_content.addEventListener("touchmove", bookmarks_scroll);

      function scroll(fn) {
        var beforeScrollTop = $(bookmarks_content).scrollTop(),
          fn = fn || function () { };
        bookmarks_content.addEventListener("scroll", function () {
          var afterScrollTop = $(bookmarks_content).scrollTop(),
            delta = afterScrollTop - beforeScrollTop;
          if (delta === 0) return false;
          fn(delta > 0 ? "down" : "up");
          beforeScrollTop = afterScrollTop;
        }, false);
      };

      scroll(function (direction) {
        bookmarks_direction = direction;
        bookmarks_direction2 = "undefined";
      });

      let timeOutEvent = 0;
      function bookmarks_reset() {
        timeOutEvent = 0;

        bookmarks_content.style.setProperty("scroll-behavior", "smooth");

        if (bookmarks_direction == "down" && bookmarks_direction2 != "up" || bookmarks_direction2 == "down") {
          bookmarks_content.scrollTop = bookmarks_content.scrollTop - bookmarks_content.scrollTop % Math.floor(bookmarks_content.scrollHeight / 3);
        };

        if (bookmarks_direction == "up" && bookmarks_direction2 != "down" || bookmarks_direction2 == "up") {
          bookmarks_content.scrollTop = bookmarks_content.scrollTop + bookmarks_content.scrollHeight / 3 - bookmarks_content.scrollTop % Math.floor(bookmarks_content.scrollHeight / 3);
        };

        bookmarks_content.style.removeProperty("scroll-behavior");
      };

      bgimg.addEventListener("touchstart", function (e) {
        e.preventDefault();
        timeOutEvent = setTimeout(bookmarks_reset, 700);
      });

      bgimg.addEventListener("touchend", function (e) {
        clearTimeout(timeOutEvent);
        return false;
      });

      bgimg.addEventListener("mousedown", function (e) {
        e.preventDefault();
        timeOutEvent = setTimeout(bookmarks_reset, 700);
      });

      bgimg.addEventListener("mouseup", function (e) {
        clearTimeout(timeOutEvent);
        return false;
      });
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
      padding: 50px 0 40px;
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

</body>

</html>