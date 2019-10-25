
// Navigation Button

const buttonOpenLevel2 = document.querySelector('.button__open--level-2');
const buttonOpenLevel3 = document.querySelector('.button__open--level-3');

const header = document.querySelector('header');
const mainNav = document.querySelector('.main__nav');
const sekNav = document.querySelector('.sekundaer__nav');

const mainNavButton = document.querySelector('.main__nav--icon');
const mainNavWrapper = document.querySelector('.main__nav--wrapper');

const formSuche = document.querySelector('.form__suche');
const formSucheInput = document.querySelector('.form__suche--input');
const formSucheClose = document.querySelector('.header__icon--search');


// Get the click events for the document
document.addEventListener('click', function (event) {

  // Mobile Navigation
  if ( event.target.closest( '.main__nav--icon' ) ) {


   mainNavButton.classList.toggle('open');
          header.classList.toggle('open');
         mainNav.classList.toggle('open');
          sekNav.classList.toggle('open');
  }

  // Search form 
  if ( event.target.matches( '.header__icon--search span' ) ) {

    event.preventDefault();

    formSuche.classList.toggle('show');
    formSucheClose.classList.toggle('open');

    setTimeout(() => { 
      formSucheInput.classList.toggle('show');
    }, 100)
  }

}, false);

//
// Fixed menubar when scrolled
//
$(window).bind('scroll', function () {
  if ($(window).scrollTop() > 150) {
    setTimeout(function() {
      $('header').addClass('scrolled');
      //$('main').css('margin-top', '85px');
    }, 400);
  } else {
    setTimeout(function() {
      $('header').removeClass('scrolled');
      //$('main').css('margin-top', '180px');
    }, 400);
  };

  // Animation of Scroll-to-Top Icon
  var bottomOffset;
  bottomOffset = $('footer').height();
  ($(window).scrollTop() + $(window).height() > $(document).height() - bottomOffset) ? $("#button__scroll--top").addClass('is-at-bottom') : $("#button__scroll--top").removeClass('is-at-bottom');
  (document.body.scrollTop > 450 || document.documentElement.scrollTop > 450) ? $("#button__scroll--top").addClass('is-visible') : $("#button__scroll--top").removeClass('is-visible');

});

// Open Level 2 Mainnavigation
$('.button__open--level-2').click(function(){

  if(!$(this).hasClass('active')) {
    $('.main__nav--link--level1').removeClass('active');
    $(this).addClass('active');
    $('.main__nav--items--level2').removeClass('open');
    $('header').addClass('nav-level2-open');
    $(this).siblings('.main__nav--items--level2').addClass('open');
    $(this).siblings('.main__nav--link--level1').addClass('active');

  } else {
    $(this).siblings('.main__nav--link--level1').removeClass('active');
    $(this).removeClass('active');
    $(this).siblings('.main__nav--items--level2').removeClass('open');
    $('header').removeClass('nav-level2-open');

  }

  $(this).toggleClass('active');
  $(this).siblings('.main__nav--items--level2').toggleClass('open');
  
  $(this).siblings('.main__nav--link--level1').toggleClass('active');
  $('header').toggleClass('nav-level2-open');
  
});



//Open Level 3 Mainnavigation
$('.button__open--level-3').click(function(){
  $(this).toggleClass('active');
  $(this).siblings('.main__nav--items--level3').toggleClass('open');
  $(this).siblings('.main__nav--link--level2').toggleClass('active');
});