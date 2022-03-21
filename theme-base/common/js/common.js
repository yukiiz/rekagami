var ua = {};
ua.name = window.navigator.userAgent.toLowerCase();
ua.isiPhone = ua.name.indexOf("iphone") >= 0;
ua.isAndroid = ua.name.indexOf("android") >= 0;
ua.isTouch = "ontouchstart" in window;
jQuery(function () {
	var allH = jQuery(".content-wrap").height();
	var footerH = jQuery(".footer").height();
	jQuery(window).on("load resize", function () {
		w = window.innerWidth ? window.innerWidth : jQuery(window).width();
		h = window.innerHeight ? window.innerHeight : jQuery(window).height();
	});
	if (ua.isTouch) {
		w = screen.width;
		h = screen.height;
	} else {
		w = window.innerWidth ? window.innerWidth : jQuery(window).width();
		h = window.innerHeight ? window.innerHeight : jQuery(window).height();
	}

	// 追従ナビ・スマホでハンバーガーメニューになる
	(function (jQuery) {
		jQuery(function () {
			var jQueryheader = jQuery("#head_wrap");
			var jQuerypagetop = jQuery(".js-pagetop");
			var headerH = jQueryheader.outerHeight();
			// Nav Fixed
			jQuery(window).scroll(function () {
				if (jQuery(window).scrollTop() > 350) {
					jQueryheader.addClass("fixed");
					$('body').css('padding-top', headerH);
				} else {
					jQueryheader.removeClass("fixed");
					$('body').css('padding-top', '');
				}
			});
			jQuery(window).on("load scroll resize", function () {
				if (jQuery(window).scrollTop() < 100) {
					jQuery(".js-pagetop").removeClass("view").addClass("btn-fixed");
				} else if (jQuery(window).scrollTop() < allH - footerH - h - 20) {
					jQuery(".js-pagetop").addClass("view").addClass("btn-fixed");
				} else {
					jQuery(".js-pagetop").addClass("view").removeClass("btn-fixed");
				}
			});
			// Nav Toggle Button
			jQuery("#nav-toggle, #global-nav ul li a").click(function () {
				jQueryheader.toggleClass("open");
			});
		});
	})(jQuery);

	// ◇ボタンをクリックしたら、スクロールして上に戻る
	jQuery(".js-pagetop").click(function () {
		jQuery("body,html").animate(
			{
				scrollTop: 0,
			},
			500
		);
		return false;
	});

	//ACF formに画像欄追加
	$('.acf-field[data-name="stylist"]').before('\
		<div class="acf-field">\
			<div class="acf-label">\
				<label for="attachment">画像 <span class="acf-required">*</span></label>\
			</div>\
			<div class="acf-input">\
				<div class="acf-input-wrap"><input type="file" name="attachment" id="attachment" required="required"></div>\
			</div>\
		</div>\
	');
	$('#acf-form').attr( 'enctype', "multipart/form-data");
});

$(window).on('load', function(){
	//ACF form author_idがあるときにユーザーをセット
	if(getParam('author_id')){
		var $select = $('#acf-field_6200d21f83cb3');
		$.ajax({
		    type: 'GET',
		    // url: '/api/students/s/' + studentId
		}).then(function (data) {
		    // create the option and append to Select2
		    var displayName = $('input[name="user_displayname"]').val();
		    var option = '<option value="'+getParam('author_id')+'">'+displayName+'</option>';
		    $select.append(option).trigger('change');

		    // manually trigger the `select2:select` event
		    $select.trigger({
		        type: 'select2:select',
		        params: {
		            data: data
		        }
		    });
		});
	}
});

function getParam(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

/*-----------------------------------------------------------------------------------*/
//slick
/*-----------------------------------------------------------------------------------*/
jQuery(".slider").slick({
	autoplay: true,
	autoplaySpeed: 5000,
	dots: true,
	arrows: false,
	fade: true,
	pauseOnFocus: false,
	pauseOnHover: false,
	//dotsClass: "slide-dots",
});

jQuery(function () {
	function sliderSetting() {
		var width = jQuery(window).width();
		if (width <= 750) {
			jQuery(".slider02").not(".slick-initialized").slick({
				autoplay: true,
				fade: true,
				dots: true,
				arrows: false,
			});
		} else {
			jQuery(".slider02.slick-initialized").slick("unslick");
		}
	}
	sliderSetting();
	jQuery(window).resize(function () {
		sliderSetting();
	});
});

/*-----------------------------------------------------------------------------------*/
//placeholder
/*-----------------------------------------------------------------------------------*/
jQuery(document).ready(function () {
	jQuery("#pass1").attr("placeholder", "パスワード");
	jQuery("#pass2").attr("placeholder", "確認用パスワード");
});

/*-----------------------------------------------------------------------------------*/
//top-page inputフィルター
/*-----------------------------------------------------------------------------------*/
//キーワード入力
window.addEventListener("load", function () {
	// 検索関数
	searchWord = function (searchText) {
		let targetText;
		if (searchText !== "") {
			jQuery(".target-area li").each(function () {
				//targetText = jQuery(this).find(".box-member").text(); お客様の氏名・顧客番号のみでフィルタする場合
				targetText = jQuery(this).text();
				if (targetText.indexOf(searchText) != -1) {
					jQuery(this).removeClass("hidden");
					console.log(this);
				} else {
					jQuery(this).addClass("hidden");
				}
			});
		} else {
			jQuery(".target-area li").each(function () {
				jQuery(this).removeClass("hidden");
			});
		}
	};
	jQuery(document).on("keypress", "#search-text", function (e) {
		if (e.which !== 13) return;
		searchWord(jQuery("#search-text").val());
	});
	jQuery(document).on("click", "#search-submit", function (e) {
		searchWord(jQuery("#search-text").val());
	});
	jQuery(document).on("click", "#search-clear", function (e) {
		jQuery("#search-text").val("");
		searchWord("");
	});
});

//select
jQuery("[name=search]").change(function () {
	var val = jQuery("[name=search]").val();
	console.log(val); // 出力：ABC
	jQuery(".target-area li").each(function () {
		targetText = jQuery(this).text();
		if (targetText.indexOf(val) != -1) {
			jQuery(this).removeClass("hidden");
		} else {
			jQuery(this).addClass("hidden");
		}
	});
});
