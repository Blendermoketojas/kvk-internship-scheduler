function logout() {
    var csrfToken = "{{ csrf_token() }}"; // Obtain the CSRF token

    $.ajax({
        type: 'POST',
        url: '/Login', 
        data: {
            _token: csrfToken 
        },
        success: function(response) {
            window.location.href = '/Login'; 
        },
        error: function(xhr, status, error) {
   
            console.error(error);
        }
    });
}