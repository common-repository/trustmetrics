var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
jQuery(document).ready(function (jQuery) {
  jQuery('[data-toggle=offcanvas]').click(function () {
    jQuery('.row-offcanvas').toggleClass('active');
  });
  jQuery('button#btn-add-user').click(function () {
    jQuery('div#user-list').addClass('d-none');
    jQuery('div#add-user-form').removeClass('d-none');
  });
  jQuery('button#btn-back-to-user-list').click(function () {
    jQuery('div#add-user-form').addClass('d-none');
    jQuery('div#user-list').removeClass('d-none');
  });
});
function clickCom(id){
  jQuery('form#tm-Form').find('input#company-select').val(jQuery(id).attr('data-val'));
  jQuery('form#tm-Form').submit();
}

