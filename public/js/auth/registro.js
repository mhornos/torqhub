// validar en tiempo real que las contraseñas coincidan en el formulario de registro

document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('formulario-registro');
    const password = document.getElementById('password');
    const passwordRepetida = document.getElementById('password_repetida');
    const errorPasswordRepetida = document.getElementById('error-password-repetida');

    if (!formulario || !password || !passwordRepetida || !errorPasswordRepetida) {
        return;
    }

    function validarCoincidenciaPassword() {
        const coinciden = password.value === passwordRepetida.value;

        if (passwordRepetida.value.length === 0) {
            errorPasswordRepetida.style.display = 'none';
            passwordRepetida.classList.remove('campo-error');
            return true;
        }

        if (!coinciden) {
            errorPasswordRepetida.style.display = 'block';
            passwordRepetida.classList.add('campo-error');
            return false;
        }

        errorPasswordRepetida.style.display = 'none';
        passwordRepetida.classList.remove('campo-error');
        return true;
    }

    password.addEventListener('input', validarCoincidenciaPassword);
    passwordRepetida.addEventListener('input', validarCoincidenciaPassword);

    formulario.addEventListener('submit', function (evento) {
        if (!validarCoincidenciaPassword()) {
            evento.preventDefault();
        }
    });
});