/**
 * JQUERY SECTION
 */
jQuery(document).ready(function () {
  jQuery('#example').DataTable({
    pagingType: 'simple_numbers',
        language: {
            paginate: {
              next: 'Previous', // or '→'
              previous: 'Next' // or '←' 
            }
          },
        searching: false,
        "aaSorting": [],
        stripeClasses: [],
    });
    
    // Search in table
    jQuery('.list_view input[type="checkbox"]').on('change', function(e) {
    // Get the column API object
    var col = jQuery('#example').DataTable().column(jQuery(this).attr('data-target'));
    // Toggle the visibility
    col.visible(!col.visible());
    });
}); 

function filter() {
  document.getElementById("filterForm").submit();
}

  /**
   * PLAIN JSVASCRIPT
   */
  function CopyToClipboard(id)
	{ 
		var value = document.getElementById("div_id"+id).value;
		navigator.clipboard.writeText(value).then(() => {
			jQuery('.alert').slideDown(function() {
				setTimeout(function() {
					jQuery('.alert').slideUp();
				}, 5000);
			});
		},() => {
			console.error('Failed to copy');
			/* Rejected - text failed to copy to the clipboard */
		});
	}
  function clear_search() {
    document.getElementById("search").value = "";
    document.getElementById("clear-search").style.display = "none";
    document.getElementById("filterForm").submit();
  }
  function clear_status() {
      document.getElementById("status").value = "";
      document.getElementById("clear-status").style.display = "none";
      document.getElementById("filterForm").submit();
  }
  function clear_type() {
      document.getElementById("type").value = "";
      document.getElementById("clear-type").style.display = "none";
      document.getElementById("filterForm").submit();
  }