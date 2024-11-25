$(document).ready(function () {
    $('#mahasiswaTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "get_data.php",
            "type": "POST"
        },
        "columns": [
            { "data": "nim" },
            { "data": "nama" },
            { "data": "alamat" },
            { "data": "prodi" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        }
    });

    // Toggle Sidebar
    $('#sidebar-toggle').click(function (e) {
        e.preventDefault();
        $('#sidebar').toggleClass('active');
        $('#main-content').toggleClass('shifted');
    });

    // Menutup sidebar ketika mengklik di luar sidebar
    $(document).click(function (e) {
        var sidebar = $('#sidebar');
        var sidebarToggle = $('#sidebar-toggle');

        // Jika klik bukan pada sidebar atau tombol toggle dan sidebar sedang aktif
        if (!sidebar.is(e.target) &&
            sidebar.has(e.target).length === 0 &&
            !sidebarToggle.is(e.target) &&
            sidebarToggle.has(e.target).length === 0 &&
            sidebar.hasClass('active')) {

            sidebar.removeClass('active');
            $('#main-content').removeClass('shifted');
        }
    });

    // Menyesuaikan tampilan saat resize window
    $(window).resize(function () {
        if ($(window).width() <= 768) {
            $('#main-content').removeClass('shifted');
        }
    });

});