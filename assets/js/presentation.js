let $ = require('jquery');
require('jquery.easing');
require('bootstrap');
require('@tehnoskarb/jquery-bxslider');
require('../scss/presentation.scss');
require('./menu');

$('img').each(function (index, element) {
  let image = new Image();
  image.src = $(element).attr('src');
});

$('.loading').remove();

let navBar = $('.navbar');
let navBarFixed = $('.navbar-fixed-top');

if (navBar.length > 0 && navBarFixed.length > 0) {
  $(window).scroll(function () {
    if (navBar.offset().top > 50) {
      navBarFixed.addClass('top-nav-collapse');
    } else {
      navBarFixed.removeClass('top-nav-collapse');
    }
  });
}

$('a.page-scroll').bind('click', function (event) {
  let $anchor = $(this);
  $('html, body').stop().animate({
    scrollTop: $($anchor.attr('href')).offset().top
  }, 1500, 'easeInOutExpo');
  event.preventDefault();
});

$(document).ready(function () {
  $('.slider').bxSlider({
    controls: false,
    auto: true,
    stopAutoOnClick: true,
    autoHover: true
  });
});
