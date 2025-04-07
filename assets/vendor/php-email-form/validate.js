document.addEventListener('DOMContentLoaded', function () {
  const contactForm = document.getElementById('contact-form');

  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(contactForm);
      const loading = contactForm.querySelector('.loading');
      const errorMessage = contactForm.querySelector('.error-message');
      const sentMessage = contactForm.querySelector('.sent-message');

      // Mostra loading e esconde mensagens
      loading.style.display = 'block';
      errorMessage.style.display = 'none';
      sentMessage.style.display = 'none';

      fetch('forms/enviar.php', {
        method: 'POST',
        body: formData,
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
          }
          return response.json();
        })
        .then((data) => {
          loading.style.display = 'none';
          if (data.success) {
            sentMessage.textContent = data.success;
            sentMessage.style.display = 'block';
            contactForm.reset();
          } else if (data.error) {
            errorMessage.textContent = data.error;
            errorMessage.style.display = 'block';
          }
        })
        .catch((error) => {
          loading.style.display = 'none';
          errorMessage.textContent = 'Erro na conexão com o servidor: ' + error.message;
          errorMessage.style.display = 'block';
        });
    });
  }
});