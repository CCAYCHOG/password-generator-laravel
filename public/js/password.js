    function copyPassword() {
        const input = document.getElementById('generatedPassword');
        if (!input) return;
        if (input.value.trim() === '') return;
        input.select();
        input.setSelectionRange(0, 99999);
        document.execCommand('copy');
        //alert('Contraseña copiada al portapapeles');
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Contraseña copiada al portapapeles',
            showConfirmButton: false,
            timer: 1500
        });
    }