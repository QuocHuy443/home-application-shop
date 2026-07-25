// JS for admin
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa mục này không?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
