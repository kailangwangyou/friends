$(function() {
	$('.img3').on('animationend', function() {
		$('.img2').removeClass('fadeInUp')
		$('.img3').removeClass('fadeInUp')
		$('.img4').removeClass('hide')
		setTimeout(function() {
			$('.img2').addClass('fadeInUp');
			$('.img3').addClass('fadeInUp');
			$('.img4').addClass('hide')
		}, 6000)
	// do something
	});
	$('.start').on('click', function() {
		$(this).addClass('fadeOut')
		$('.box').removeClass('hide')
		$('#main').addClass('scroll')
		setTimeout(function() {
			$('.start').addClass('hide')
		}, 1000)
	})
	console.log($('.heart-box'))
	$('.heart-box').on('click', function() {
		$(this).find('img').toggle()
	})
	$('.img-box span').on('click', function() {
		var index = $(this).index()
		var pIndex = $(this).parent().siblings('img').attr('src').replace('.png','/')
		var url = pIndex + (index+1) + ($(this).attr('t') ? $(this).attr('t') : '.jpg')
		$('.big-img img').attr('src', url)
		$('.big-img').show()
	})
	$('.big-img').on('click', function() {
		$(this).hide()
	})
	$('.video').on('click', function() {
		$('.video video').each(function() {
			this.pause()
			$(this).parent().removeClass('video-show')
		})
		$(this).addClass('video-show')
		$(this).find('video')[0].play()
	})
	$('.video video').on('ended', function() {
		this.pause()
		$(this).parent().removeClass('video-show')
	})
})