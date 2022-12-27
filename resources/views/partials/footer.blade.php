<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="{{asset('//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js')}}"></script>
<script src="{{asset('/theme/bootstrap5/js/bootstrap.js')}}"></script>
<script src="{{asset('//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js')}}"></script>

<script src="https://kit.fontawesome.com/ec19ec29f3.js" crossorigin="anonymous"></script>
<script src="https://unpkg.com/feather-icons"></script>

<script type="text/javascript">
	var popover = new bootstrap.Popover(document.querySelector('.cart-price'), {
	container: 'body',
	html:  true
	});
	feather.replace();
</script>
<script type="text/javascript">
	let dropdowns = document.querySelectorAll('.dropdown-toggle')
		dropdowns.forEach((dd)=>{
		dd.addEventListener('click', function (e) {
	var el = this.nextElementSibling
	el.style.display = el.style.display==='block'?'none':'block'
	})
	});
			
	$(document).ready(function(){
	$('.login-info-box').fadeOut();
	$('.login-show').addClass('show-log-panel');
	});
	$('.login-reg-panel input[type="radio"]').on('change', function() {
	if($('#log-login-show').is(':checked')) {
	$('.register-info-box').fadeOut();
	$('.login-info-box').fadeIn();
	$('.white-panel').addClass('right-log');
	$('.register-show').addClass('show-log-panel');
	$('.login-show').removeClass('show-log-panel');
	}
	else if($('#log-reg-show').is(':checked')) {
	$('.register-info-box').fadeIn();
	$('.login-info-box').fadeOut();
	$('.white-panel').removeClass('right-log');
	$('.login-show').addClass('show-log-panel');
	$('.register-show').removeClass('show-log-panel');
	}
	});
</script>

</html>