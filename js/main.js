/*  ---------------------------------------------------
    Template Name: Male Fashion
    Description: Male Fashion - ecommerce teplate
    Author: Colorib
    Author URI: https://www.colorib.com/
    Version: 1.0
    Created: Colorib
---------------------------------------------------------  */

'use strict';

(function ($) {

    /*------------------
        Preloader
    --------------------*/
    $(window).on('load', function () {
        $(".loader").fadeOut();
        $("#preloder").delay(200).fadeOut("slow");

        /*------------------
            Gallery filter
        --------------------*/
        $('.filter__controls li').on('click', function () {
            $('.filter__controls li').removeClass('active');
            $(this).addClass('active');
        });
        if ($('.product__filter').length > 0) {
            var containerEl = document.querySelector('.product__filter');
            var mixer = mixitup(containerEl);
        }
    });

    /*------------------
        Background Set
    --------------------*/
    $('.set-bg').each(function () {
        var bg = $(this).data('setbg');
        $(this).css('background-image', 'url(' + bg + ')');
    });

    //Search Switch
    $('.search-switch').on('click', function () {
        $('.search-model').fadeIn(400);
    });

    $('.search-close-switch').on('click', function () {
        $('.search-model').fadeOut(400, function () {
            $('#search-input').val('');
        });
    });

    /*------------------
		Navigation
	--------------------*/
    $(".mobile-menu").slicknav({
        prependTo: '#mobile-menu-wrap',
        allowParentLinks: true
    });

    /*------------------
        Accordin Active
    --------------------*/
    $('.collapse').on('shown.bs.collapse', function () {
        $(this).prev().addClass('active');
    });

    $('.collapse').on('hidden.bs.collapse', function () {
        $(this).prev().removeClass('active');
    });

    //Canvas Menu
    $(".canvas__open").on('click', function () {
        $(".offcanvas-menu-wrapper").addClass("active");
        $(".offcanvas-menu-overlay").addClass("active");
    });

    $(".offcanvas-menu-overlay").on('click', function () {
        $(".offcanvas-menu-wrapper").removeClass("active");
        $(".offcanvas-menu-overlay").removeClass("active");
    });

    /*-----------------------
        Hero Slider
    ------------------------*/
    $(".hero__slider").owlCarousel({
        loop: true,
        margin: 0,
        items: 1,
        dots: false,
        nav: true,
        navText: ["<span class='arrow_left'><span/>", "<span class='arrow_right'><span/>"],
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: false
    });

    /*--------------------------
        Select
    ----------------------------*/
    $("select").niceSelect();

    /*-------------------
		Radio Btn
	--------------------- */
    $(".product__color__select label, .shop__sidebar__size label, .product__details__option__size label").on('click', function () {
        $(".product__color__select label, .shop__sidebar__size label, .product__details__option__size label").removeClass('active');
        $(this).addClass('active');
    });

    /*-------------------
		Scroll
	--------------------- */
    $(".nice-scroll").niceScroll({
        cursorcolor: "#0d0d0d",
        cursorwidth: "5px",
        background: "#e5e5e5",
        cursorborder: "",
        autohidemode: true,
        horizrailenabled: false
    });

    /*------------------
        CountDown
    --------------------*/
    // For demo preview start
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    if(mm == 12) {
        mm = '01';
        yyyy = yyyy + 1;
    } else {
        mm = parseInt(mm) + 1;
        mm = String(mm).padStart(2, '0');
    }
    var timerdate = mm + '/' + dd + '/' + yyyy;
    // For demo preview end


    // Uncomment below and use your date //

    /* var timerdate = "2020/12/30" */

    $("#countdown").countdown(timerdate, function (event) {
        $(this).html(event.strftime("<div class='cd-item'><span>%D</span> <p>Days</p> </div>" + "<div class='cd-item'><span>%H</span> <p>Hours</p> </div>" + "<div class='cd-item'><span>%M</span> <p>Minutes</p> </div>" + "<div class='cd-item'><span>%S</span> <p>Seconds</p> </div>"));
    });

    /*------------------
		Magnific
	--------------------*/
    $('.video-popup').magnificPopup({
        type: 'iframe'
    });

    /*-------------------
		Quantity change
	--------------------- */
  var proQty = $('.pro-qty-2');
  proQty.prepend('<span class="fa fa-angle-left dec qtybtn"></span>');
proQty.append('<span class="fa fa-angle-right inc qtybtn"></span>');

proQty.on('click', '.qtybtn', function () {
    var $button = $(this);
    var input = $button.siblings('input');
    var oldValue = parseInt(input.val());
    var newVal = oldValue;

    if ($button.hasClass('inc')) {
        newVal = oldValue + 1;
    } else {
        if (oldValue > 1) {
            newVal = oldValue - 1;
        }
    }

    input.val(newVal);
    var productId = $button.closest('.pro-qty-2').data('id');
fetch('updateQuantity.php',{
    method:'POST',
     headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    body:'id='+encodeURIComponent(productId) + '&quantity='+encodeURIComponent(newVal)
})
.then(res=>res.text())
.then(data=>{
console.log('update',data);
location.reload();
})
.catch(()=>{
alert('update faild');
})
 });

    /*------------------
        Achieve Counter
    --------------------*/
    $('.cn_num').each(function () {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 4000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

})(jQuery);
function filter(value) {
    fetch('filter_products.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'filter=' + encodeURIComponent(value)
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById('products').innerHTML = data;

    })
    .catch(error => {
        console.error('Error: ' + error);
    });
}

function category(value){
fetch('category.php' ,{
    method:'POST',
    headers:{
         'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'filter=' +encodeURIComponent(value)
})
.then(res=>res.text())
.then(data=>{
     document.getElementById('products').innerHTML = data;
       
})
.catch(error=>{
    console.log('error'+error);
})
console.log('done');
}

 const searchInput = document.getElementById('search');
 const products = Array.from(document.querySelectorAll('.product__item'));
 const cartTable = document.querySelector('.shopping__cart__table');

 searchInput.addEventListener('keyup', function() {
    const query = this.value.toLowerCase();

     products.forEach(product => {
        const nameEl = product.querySelector('h6');
         const name = nameEl.textContent.toLowerCase();

         if(name.includes(query)) {
             product.parentElement.style.display = "block"; 
         } else {
             product.parentElement.style.display = "none";
        }
    });
     if(cartTable) cartTable.style.display = 'block';
 });
 function toggleProfileMenu(event) {
    event.preventDefault(); 
    const menu = document.getElementById('profileMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}


document.addEventListener('click', function(e) {
    const container = document.querySelector('.profile-container');
    if (container && !container.contains(e.target))  {
        document.getElementById('profileMenu').style.display = 'none';
    }
});
function togglePasswordForm(){
    const form=document.getElementById("passwordForm");
    form.style.display=(form.style.display==='block')?'none' :'block';
}


   
