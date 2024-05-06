$(document).ready(function () {
  $("#membershipTypeProduct").select2();
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).on('click', '.themeMode', function () {
    let themeMode = $('input[name="flexRadioDefault"]:checked').val();
    let headerColor = null;
    let sidebarColor = null;
    changeSettings(themeMode, headerColor, sidebarColor);

  });

  //////////////////// Change Header Color ############

  $(document).on('click', '#headercolor1,#headercolor2,#headercolor3,#headercolor4,#headercolor5,#headercolor6,#headercolor7,#headercolor8', function () {
    let headerColor = $(this).attr('data-color');
    let themeMode = null;
    let sidebarColor = null;
    changeSettings(themeMode, headerColor, sidebarColor)
  });

  ///////// Change Sidebar Color /////####################

  $(document).on('click', '#sidebarcolor1,#sidebarcolor2,#sidebarcolor3,#sidebarcolor4,#sidebarcolor5,#sidebarcolor6,#sidebarcolor7,#sidebarcolor8', function () {
    let sidebarColor = $(this).attr('data-sidebarcolor');
    let themeMode = null;
    let headerColor = null;
    changeSettings(themeMode, headerColor, sidebarColor)
  });

  function changeSettings(themeMode, headerColor, sidebarColor) {
    $.ajax({
      type: 'POST',
      url: '/admin/change/themesettings',
      data: { themeMode: themeMode, headerColor: headerColor, sidebarColor: sidebarColor },
      success: function (res) {

      }, error: function () {

      }
    })
  }

});