(() => {
  let $navToggle = $("#nav-aside-toggle");
  let $navClose = $("#nav-aside-close");
  let $nav = $("#nav-aside");

  if ($navToggle.length === 0 || $navClose.length === 0 || $nav.length === 0) {
    return;
  }

  $navToggle.click(() => {
    if ($nav.css("left") === "0px") {
      $nav.animate({"left": "-" + $(window).width(), "opacity": 0});
    } else {
      $nav.animate({"left": 0, "opacity": 1});
    }
  });
})();
