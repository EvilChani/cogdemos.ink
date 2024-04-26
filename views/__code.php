  <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="/js/popper.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/js/mdb.min.js"></script>
	<script type="text/javascript" src="/js/jquery.nyroModal.custom.min.js"></script>
	<script>
		$(document).ready(function() {
				$( ".nyroModal" ).nyroModal();
				$(".megamenu").on("click", function(e) {
					e.stopPropagation();
				});
				$(function () {
					$('[data-toggle="tooltip"]').tooltip()
				})
			});
	</script>
