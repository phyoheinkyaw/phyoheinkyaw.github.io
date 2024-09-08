$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('collapsed');
        $('#content').toggleClass('collapsed');
        $('#sidebarCollapse i').toggleClass('fa-circle-arrow-right');
    });
});
