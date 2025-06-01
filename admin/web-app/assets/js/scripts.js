// This file contains JavaScript code for client-side functionality, such as form validation and AJAX requests for dynamic content updates.

document.addEventListener('DOMContentLoaded', function() {
    const addItemForm = document.getElementById('add-item-form');
    const editItemForm = document.getElementById('edit-item-form');

    if (addItemForm) {
        addItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(addItemForm);
            fetch('src/controllers/add_item.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item added successfully!');
                    window.location.reload();
                } else {
                    alert('Error adding item: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    if (editItemForm) {
        editItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(editItemForm);
            fetch('src/controllers/edit_item.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Item updated successfully!');
                    window.location.reload();
                } else {
                    alert('Error updating item: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            if (confirm('Are you sure you want to delete this item?')) {
                fetch('src/controllers/delete_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: itemId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Item deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting item: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});