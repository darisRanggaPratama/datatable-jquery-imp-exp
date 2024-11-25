$(document).ready(function() {
    let table = $('#mahasiswaTable').DataTable({
        "processing": true,
        "serverSide": false, // Set true jika ingin implementasi server-side processing
        "ajax": {
            "url": "process.php?action=read",
            "type": "GET",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "id_mhs" },
            {
                "data": null,
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    return `
                        <button onclick="editData('${row.id_mhs}')" class="btn btn-primary btn-sm"><i class="far fa-edit"></i></button>                        
                    `;
                }
            },
            { "data": "nim" },
            { "data": "nama" },
            { "data": "alamat" },
            { "data": "prodi" },
            {
                "data": null,
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    return `                        
                        <button onclick="deleteData('${row.id_mhs}')" class="btn btn-danger btn-sm"><i class="fas fa-backspace"></i></button>
                    `;
                }
            }
        ]
    });

    // Create Data
    $('#addForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: $(this).serialize() + '&action=create',
            dataType: 'json',
            success: function(response) {
                if(response.status == 'success') {
                    $('#addModal').modal('hide');
                    $('#addForm')[0].reset();
                    table.ajax.reload();
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });
});

// Edit Data Function
function editData(id) {
    $.ajax({
        url: 'process.php?action=read',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let data = response.data.find(item => item.id_mhs == id);
            if(data) {
                $('#editId').val(data.id_mhs);
                $('#editNim').val(data.nim);
                $('#editNama').val(data.nama);
                $('#editAlamat').val(data.alamat);
                $('#editProdi').val(data.prodi);
                $('#editModal').modal('show');
            }
        }
    });
}

// Update Data Form Submit
$('#editForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: 'process.php',
        type: 'POST',
        data: $(this).serialize() + '&action=update',
        dataType: 'json',
        success: function(response) {
            if(response.status == 'success') {
                $('#editModal').modal('hide');
                $('#mahasiswaTable').DataTable().ajax.reload();
                alert(response.message);
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error);
        }
    });
});

// Delete Data Function
function deleteData(id) {
    if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: {
                action: 'delete',
                id_mhs: id
            },
            dataType: 'json',
            success: function(response) {
                if(response.status == 'success') {
                    $('#mahasiswaTable').DataTable().ajax.reload();
                    alert(response.message);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }
}

// Sidebar functions
function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
}