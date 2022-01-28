// sticky header js
$(window).scroll(function () {
  if ($(window).scrollTop() >= 150) {
    $(".menu").addClass("sticky-header");
  } else {
    $(".menu").removeClass("sticky-header");
  }
});

// on page change add active class to link
$(function () {
  var current_page_URL = location.href;
  $(".navbar-nav a").each(function () {
    if ($(this).attr("href") !== "#") {
      var target_URL = $(this).prop("href");
      if (target_URL == current_page_URL) {
        $(".navbar-nav a").parents("li").removeClass("active");
        $(this).parent("li").addClass("active");
        return false;
      }
    }
  });
});

// on page change add active class to footer quick links
$(function () {
  var current_page_URL = location.href;
  $(".quick-links a").each(function () {
    if ($(this).attr("href") !== "#") {
      var target_URL = $(this).prop("href");
      if (target_URL == current_page_URL) {
        $(".quick-links a").parents("li").removeClass("active");
        $(this).parent("li").addClass("active");
        return false;
      }
    }
  });
});

// on click change product image
function changeimg(url, e) {
  document.getElementById("img").src = url;
  let nodes = document.getElementById("thumb_img");
  let img_child = nodes.children;
  for (i = 0; i < img_child.length; i++) {
    img_child[i].classList.remove("active");
  }
  e.classList.add("active");
}

// ordered-product-slider js
$(".ordered-product-slider").slick({
  slidesToScroll: 1,
  arrows: false,
  dots: true,
  vertical: true,
  verticalSwiping: true,
  responsive: [
    {
      breakpoint: 576,
      settings: {
        vertical: false,
        verticalSwiping: false,
      },
    },
  ],
});

// slick silder in modal width issue js
$(".modal").on("shown.bs.modal", function (e) {
  $(".ordered-product-slider").slick("setPosition");
});

// product-listing page products slider
$(".pl-products-slider").slick({
  slidesToShow: 7,
  slidesToScroll: 1,
  infinite: true,
  arrows: true,
  dots: false,
  centerMode: false,
  centerPadding: "0px",
  responsive: [
    {
      breakpoint: 991,
      settings: {
        slidesToShow: 5,
      },
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 3,
      },
    },
    {
      breakpoint: 575,
      settings: {
        slidesToShow: 2,
      },
    },
  ],
});

// product-listing page products slider
$(".store-image-slider").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  infinite: true,
  arrows: false,
  dots: true,
  centerMode: false,
  centerPadding: "0px",
});

// account page toggle button js
jQuery(document).ready(function () {
  jQuery(".notification-toggle").on("click", function () {
    jQuery(this).toggleClass("active");
  });
});

// product-quantity-ml select js
jQuery(document).ready(function () {
  $(".product-quantity-ml li button").click(function () {
    $(".product-quantity-ml li button").removeClass("active");
    $(this).addClass("active");
  });
});

// onscroll  search box close js
$(window).on("scroll", function (e) {
  $(".search-field1").removeClass("form-active");
});

// about-us page custom video js
jQuery(document).ready(function () {
  jQuery(".play").click(function (event) {
    event.preventDefault();
    $(".js-video").append(
      '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/CNQJKqkVf8s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
    );
    $(this).hide();
  });
});

// on click add class active
jQuery(document).ready(function () {
  jQuery("ul.sign-up-as li").click(function () {
    jQuery(this).addClass("active").siblings().removeClass("active");
  });
});

// on click add class active for Age Confirmatin modal
jQuery(document).ready(function () {
  jQuery("ul.age-confirm li").click(function () {
    jQuery(this).addClass("active").siblings().removeClass("active");
  });
});

// product counter
function increaseCount(e, el) {
  var input = el.previousElementSibling;
  var value = parseInt(input.value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  input.value = value;
}
function decreaseCount(e, el) {
  var input = el.nextElementSibling;
  var value = parseInt(input.value, 10);
  if (value > 1) {
    value = isNaN(value) ? 0 : value;
    value--;
    input.value = value;
  }
}

// search box js
const search = document.getElementById("click");
if (search) {
  search.addEventListener("click", function () {
    document.getElementById("search-field").classList.toggle("form-active");
  });
}

// price range js
// var slider = new Slider("#range", {
//   min: 0,
//   max: 200,
//   value: [0, 100],
//   range: true,
//   tooltip: 'always'
// });

function add_remove_fav_product(store_id, product_id, add_remove = 0) {
  // const fav_id = $('#your_wishlist_'+store_id+'_'+product_id);
  var add_remove = $(".your_wishlist_" + store_id + "_" + product_id).attr(
    "data-add_remove"
  );

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $("#loader").show();
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/add_remove_fav_product",
    data: {
      store_id: store_id,
      product_id: product_id,
      add_remove: add_remove,
    },
    success: function (returnData) {
      $("#loader").hide();

      if (returnData.status == false && returnData.redirect == "login") {
        window.location.href = "/login";
      } else if (
        returnData.status == false &&
        returnData.redirect == "account"
      ) {
        window.location.href = "/account?verify=mobile";
      }

      if (returnData.status == true) {
        var line_heart_img = "/assets/images/website/bd-heart.png";
        var filled_heart_img = "/assets/images/website/saved.png";
        var url = '{{url("/")}}';

        if (add_remove == 0) {
          $("#your_wishlist_" + store_id + "_" + product_id).attr(
            "src",
            line_heart_img
          );
          $(".your_wishlist_" + store_id + "_" + product_id).attr(
            "src",
            line_heart_img
          );
          $(".remove_fav_prodcut_" + store_id + "_" + product_id).remove();
          $("#your_wishlist_" + store_id + "_" + product_id).attr(
            "data-add_remove",
            "1"
          );
          $(".your_wishlist_" + store_id + "_" + product_id).attr(
            "data-add_remove",
            "1"
          );

          var fav_product_count = $(".remove_fav_store_" + store_id).find(
            ".bd-product-item"
          ).length;
          if (fav_product_count == 0) {
            $(".remove_fav_store_" + store_id).remove();
          }

          var total_product_count = $(".bd-product-listing").length;
          console.log(`total_product_count: `, total_product_count);
          if (total_product_count == 0) {
            $("#no_product").removeClass("d-none");
          }
        } else {
          $("#your_wishlist_" + store_id + "_" + product_id).attr(
            "src",
            filled_heart_img
          );
          $(".your_wishlist_" + store_id + "_" + product_id).attr(
            "src",
            filled_heart_img
          );
          $("#your_wishlist_" + store_id + "_" + product_id).attr(
            "data-add_remove",
            "0"
          );
          $(".your_wishlist_" + store_id + "_" + product_id).attr(
            "data-add_remove",
            "0"
          );
        }
        Notiflix.Notify.Success(returnData.message);
      } else {
        Notiflix.Notify.Failure(returnData.message);
      }
    },
    error: function (xhr, status, error) {
      var err = eval("(" + xhr.responseText + ")");
      window.location.href = "/login";
    },
  });
}
var notification_button = $("#notification_id").attr("data-notification");
console.log(notification_button);
if (notification_button == 1) {
  $("#notification_id:checked").attr("checked", true);
} else {
  $("#notification_id:checked").attr("checked", false);
}

function notification(notification) {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/notification_toggle",
    data: {
      status: notification,
    },
    success: function (data) {
      if (data.status == true) {
        Notiflix.Notify.Success(data.message);
      } else {
        Notiflix.Notify.Failure(data.message);
      }
    },
    error: function (xhr, status, error) {
      var err = eval("(" + xhr.responseText + ")");
      window.location.href = "/login";
    },
  });
}

// $(window).load(function () {
let myInterval;
let isSearch = true;
$("#search_text").keyup(function () {
  clearTimeout(myInterval);
  // myInterval = setTimeout(() => {
  if (!isSearch) return;
  isSearch = true;
  var store_id = $("#store_id").val();
  var category_id = $("#category_id").val();
  var text = $("#search_text").val();

  console.log(`text length: `, text.length);
  if (text.length < 3) {
    $("#search_product").empty();
    //Notiflix.Notify.Failure('Please enter at least 3 characters');
    return false;
  }
  console.log(`search value: `, [store_id, category_id, text]);
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  // $('#loader').show();
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/search_product",
    data: {
      store_id: store_id,
      category_id: category_id,
      text: text,
    },
    success: function (response) {
      $("#loader").hide();
      var search_result = response.data;
      console.log("search result: ", search_result);

      if (search_result && search_result.length > 0) {
        var search_result_list = "";
        $(search_result).each(function (mainkey, mainval) {
          $(mainval).each(function (key, val) {
            var url = '{{url("/")}}';
            let name = val.name.slice(0, 23);
            if (val.name.length > 23) {
              name = name + "...";
            }
            if (val.type == "category") {
              var search_type = "category";
              search_result_list += ` <option value="${name} in Category " class="store-details" data-id="${val.id}" data-type="${val.type}"> `;
            } else {
              var search_type = "product";
              search_result_list += ` <option value="${name} (${val.item_code}) in ${val.category_name}" class="store-details" data-id="${val.id}" data-type="${val.type}"> `;
            }
          });
        });

        $("#search_product").empty();
        $("#search_product").append(search_result_list);
      } else {
        var search_result_list = ``;
        search_result_list += ` <option value="No result found" class="store-details" data-id=""> `;
        $("#search_product").empty();
        $("#search_product").append(search_result_list);
      }
      // Notiflix.Notify.Success(response.message);
    },
  });
  // }, 0);
});

$("#search-field").submit(function (e) {
  e.preventDefault();
  return false;
});

$("#search-field").on("input", "input", function () {
  var store_id = $("#store_id").val();
  const value = $(this).val();
  const options = $(this).next().find("option");
  console.log(`options: `, options);
  const option = options.filter((index, option) => $(option).val() == value);
  console.log(`Id: `, $(option).attr("data-id"));
  console.log(`Type: `, $(option).attr("data-type"));
  const redirect_id = $(option).attr("data-id");
  const type = $(option).attr("data-type");
  if (!redirect_id || !type) return;
  isSearch = false;
  $("#loader").show();
  var url = '{{url("/")}}';
  if (type == "category") {
    var search_type = "category";
    window.location.href = `/products/${store_id}/${redirect_id}`;
  } else {
    var search_type = "product";
    window.location.href = `/product/${store_id}/${redirect_id}`;
  }
});

$(".open-notification").click(function () {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $("#loader").show();
  $.ajax({
    type: "post",
    dataType: "json",
    url: "/notification",
    data: {},
    success: function (response) {
      $("#loader").hide();
      var notification_list = response.data;
      console.log("Notification result: ", notification_list);

      if (notification_list && notification_list.length > 0) {
        var notification_list_list = "";
        $(notification_list).each(function (mainkey, mainval) {
          $(mainval).each(function (key, val) {
            notification_list_list += `
                                       <div class="notification-wrap d-flex align-items-center mb-3">
                                          <div class="notification-image mr-3">
                                              <img src="${val.image}" alt="" class="border-50" width="45" height="45" style="object-fit: contain; ">
                                          </div>                                          
                                          <div class="border-bottom pb-2 w-100 d-flex align-items-start">
                                            <a href="/order/detail/${val.order_id}">
                                                <div class="notification-detail">
                                                    <p class="t-blue mb-0">${val.title}</p>
                                                    <p class="t-grey2 font-14 mb-0">${val.message}</p>
                                                </div>
                                            </a>
                                            <div class="notification-date ml-auto">
                                                <p class="t-grey2 font-14 mb-0">${val.time_ago}</p>
                                            </div>
                                          </div>
                                      </div>
                                      `;
          });
        });

        $("#show-notification").empty();
        $("#show-notification").append(notification_list_list);
        // $('#open-notification').addClass('show');
        $(".dropdown-menu").removeClass("d-none");
      } else {
        var notification_list_list = ``;
        notification_list_list += `<div class="notification-wrap align-items-center mb-3">
                                                <p class="t-blue mb-0 ">No Notifications found</p>                                    
                                            </div>`;
        $("#show-notification").empty();
        $("#show-notification").append(notification_list_list);
      }
    },
    error: function (xhr, status, error) {
      var err = eval("(" + xhr.responseText + ")");
      window.location.href = "/login";
    },
  });
});

// });

function addTocart(is_case) {
  var product_id = $("#product-id").val();
  var qty = $("#product-quantity").val();
  var stock = $("#stock").val();
  var store_id = $("#store_id").val();
  var cart_store_id = $("#cart_store_id").val();
  var data_name = $("#data-name").val();

  if (is_case == "1") {
    qty = 12;
  }
  if (parseInt(qty) > parseInt(stock)) {
    Notiflix.Notify.Failure(
      "" + data_name + " available for " + stock + " quantity only"
    );
  } else {
    if (cart_store_id == store_id || cart_store_id == "0") {
      cart(
        product_id,
        qty,
        stock,
        store_id,
        is_case,
        cart_store_id,
        "not_clear"
      );
    } else {
      Notiflix.Confirm.Show(
        "Confirm",
        "Your cart contains items from a different store. Would you like to reset your cart before adding items from this store?",
        "Yes",
        "No",
        function () {
          cart(
            product_id,
            qty,
            stock,
            store_id,
            is_case,
            cart_store_id,
            "clear"
          );
        }
      );
    }
  }
}

function cart(product_id, qty, stock, store_id, is_case, cart_store_id, clear) {
  $("#loader").show();
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $.ajax({
    async: false,
    url: "/add-to-cart",
    type: "POST",
    data: {
      product_id: product_id,
      qty: qty,
      store_id: store_id,
      is_case: is_case,
      clear: clear,
    },
    success: function (response) {
      var res = JSON.parse(response);
      if (res.status == false && res.redirect == "login") {
        window.location.href = "/login";
      } else if (res.status == false && res.redirect == "account") {
        window.location.href = "/account?verify=mobile";
      }

      $("#cart_product_count").html(res.cart_products_count);

      if (res.status == true) {
        Notiflix.Notify.Success("Added in cart");
        $("#loader").hide();
      } else {
        Notiflix.Notify.Failure(res.message);
        $("#loader").hide();
      }
    },
  });
}

function checkUserLogin() {
  Notiflix.Confirm.Show(
    "Confirm",
    "To proceed further please Sign in.",
    "Ok",
    "Cancel",
    function () {
      window.location.href = "/login";
    }
  );
}

function proceed_to_payment() {
  $("#loader").show();
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  var card_id = $("input[name='card_id']:checked").val();

  $.ajax({
    async: false,
    url: "/proceed_to_payment",
    type: "POST",
    dataType: "json",
    data: { card_id: card_id },
    success: function (response) {
      if (response.status == true) {
        window.location.href = "/order/detail/" + response.data.order_id;
      } else {
        window.location.href = "/cart";
      }
    },
  });
}

// For profile page
function getCodeBoxElement(index) {
  return document.getElementById("codeBox" + index);
}
function onKeyUpEvent(index, event) {
  const eventCode = event.which || event.keyCode;
  console.log("getCodeBoxElement", getCodeBoxElement(index).value.length);
  if (getCodeBoxElement(index).value.length === 1) {
    if (index !== 6) {
      getCodeBoxElement(index + 1).focus();
    } else {
      getCodeBoxElement(index).blur();
      // Submit code
      console.log("submit code ");
      var otp_digits =
        $("#codeBox1").val() +
        $("#codeBox2").val() +
        $("#codeBox3").val() +
        $("#codeBox4").val() +
        $("#codeBox5").val() +
        $("#codeBox6").val();
      console.log(otp_digits);
      if (match_otp == otp_digits) {
        console.log("OTP matched");

        window.location.replace("/phone/add/" + phone_number);
      } else {
        Notiflix.Notify.Failure("Invalid OTP");
        return false;
      }
    }
  } else {
    $("#codeBox" + index).val("");
  }
  if (eventCode === 8 && index !== 1) {
    getCodeBoxElement(index - 1).focus();
  }
}

function onFocusEvent(index) {
  for (item = 1; item < index; item++) {
    const currentElement = getCodeBoxElement(item);
    if (!currentElement.value) {
      currentElement.focus();
      break;
    }
  }
}

var phone_number = "";

var match_otp = "";
$(document).on("click", "#send-otp", function () {
  console.log("submitting");
  var phone = $('[name="phone"]').val();

  if (phone == "") {
    Notiflix.Notify.Failure("Enter your phone number to verify");
    return false;
  }
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $("#loader").show();
  $.ajax({
    url: "send_otp_mobile",
    type: "post",
    dataType: "json",
    data: {
      phone: phone,
    },
    success: function (returnData) {
      console.log(returnData);
      if (returnData.status == true) {
        Notiflix.Notify.Success(returnData.message);

        if (typeof returnData.data !== "undefined") {
          $("#verify-modal").modal();
          phone_number = returnData.data.crypt_phone;
          console.log(phone_number);
          match_otp = returnData.data.otp;
        } else {
          Notiflix.Notify.Success(returnData.message);
        }

        $("#loader").hide();
      } else {
        $("#loader").hide();
        Notiflix.Notify.Failure(returnData.message);
        return false;
      }
    },
  });
});

$(document).on("click", "#resend-link", function () {
  console.log("resend");

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $("input[name='otp_digits[]']").val("");
  $("#loader").show();

  var phone = $('[name="phone"]').val();

  $.ajax({
    url: "send_otp_mobile",
    type: "post",
    dataType: "json",
    data: {
      phone: phone,
    },
    success: function (returnData) {
      $("input[name='otp_digits[]']").val("");
      $("#loader").hide();
      console.log(returnData);
      if (returnData.status == true) {
        Notiflix.Notify.Success(returnData.message);
        match_otp = returnData.data.otp;
      } else {
        Notiflix.Notify.Failure(returnData.message);
        return false;
      }
    },
  });
});

$("#map-nearby-store").click(function () {  
  $.ajaxSetup({ 
    headers: {  
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), 
    },  
  }); 
  $("#loader").show();  
  $.ajax({  
    type: "get",  
    dataType: "json", 
    url: "/set-cookie", 
    data: {}, 
    success: function (returnData) {  
      $("#loader").hide();  
      console.log(`returnData: `, returnData);  
    },  
  }); 
});