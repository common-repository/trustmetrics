/**
  * JQUERY SECTION BIGINS HERE------------
  */
jQuery('#Form').on('submit', function (e) {
  e.preventDefault();
  var url = jQuery(this).attr('action');
  var method = jQuery(this).attr('method');
  var data = jQuery(this).serialize();
  jQuery.ajax({
    url: url,
    dataType: "json",
    type: method,
    data: data,
    success: function (result) {
      jQuery('#myModal1').modal('hide');
      jQuery('#myModal0').modal('show');
    }
  });

});
jQuery('#myform').on('submit', function (e) {
  e.preventDefault();
  var url = jQuery(this).attr('action');
  var method = jQuery(this).attr('method');
  var data = jQuery(this).serialize();
  jQuery.ajax({
    url: url,
    dataType: "json",
    type: method,
    data: data,
    success: function (result) {
      console.log(result);
      var myModal1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('myModal1'));
      myModal1.hide();
      // jQuery('#myModal1').modal('hide');
    },
    error: function (xhr, status, error) {
      if (xhr.status === 422) {
        alert(xhr.responseJSON.error);
      } else {
        alert(xhr.responseText);
      }
    }

  });
});
jQuery('#myform11').on('submit', function (e) {
  e.preventDefault();
  var url = jQuery(this).attr('action');
  var method = jQuery(this).attr('method');
  var data = jQuery(this).serialize();
  console.log(data);
  jQuery.ajax({
    url: url,
    dataType: "json",
    type: method,
    data: data,
    success: function (result) {
      var myModal0 = bootstrap.Modal.getOrCreateInstance(document.getElementById('myModal0'));
      myModal0.show();
      // jQuery('#myModal0').modal('show');
      alert('We send verification link on your email pls verify link');
    },
    error : function( jqXHR, textStatus, errorThrown ) {
      alert("Error while logging out, please try again!!");
      return false;
    }
  });
});

jQuery('#signInBtn').on('click',function(e){
  var myModal1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('myModal1'));
  myModal1.hide();
  var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('myModal'));
  myModal.show();
});

jQuery('#signUpBtn').on('click',function(e){
  var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('myModal'));
  myModal.hide();
  var myModal1 = bootstrap.Modal.getOrCreateInstance(document.getElementById('myModal1'));
  myModal1.show();
});

/**
 * PLAIN JAVASCRIPT CODE SECTION ------
 */
if(document.getElementById('company-select') != undefined && document.getElementById('company-select') != null){
  var select = document.getElementById('company-select');
  var p = document.querySelector('.business');
  select.addEventListener('change', function () {
    p.textContent = 'Welcome ' + this.options[this.selectedIndex].text;
  });
}

function change() {
  document.getElementById("filterForm").submit();
}
function changeTimeframe() {
  document.getElementById("filterTimeframe").submit();
}
function changeDates() {
  document.getElementById("filterDates").submit();
}
function submitTimeframeForm() {
  document.getElementById("filterTimeframe").submit();
}
function showSignUp(){
  var url = trustmetrics_globals.signupUrl;
  var newWindow = window.open(url, "", "width=500,height=1000");
  if (!newWindow.closed) {
      jQuery('#login-content').hide();
  }
  var win_timer = setInterval(function() {   
      if(newWindow.closed) {
          window.location.reload();
          clearInterval(win_timer);
      } 
  }, 100); 
}
function showLogin(){
  var url = trustmetrics_globals.loginUrl;
  var newWindow = window.open(url, "", "width=500,height=1000");
  if (!newWindow.closed) {
      jQuery('#login-content').hide();
  }
  var win_timer = setInterval(function() {   
      if(newWindow.closed) {
          window.location.reload();
          clearInterval(win_timer);
      } 
  }, 100); 
}
