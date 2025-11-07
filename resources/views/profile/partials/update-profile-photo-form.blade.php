<section>
        
        <h2>Actualizar Foto de Perfil</h2>

        <label for="photo">Foto de Perfil Actual</label>
        {{-- (Ajusta la ruta de la imagen según tu configuración de Storage) --}}
        <img src="{{ asset('storage/' . ($user->profile_photo_path ?? 'profiles/default-profile.png')) }}"
         id="current-photo"
         alt="Foto de Perfil"
         style="width: 100px; height: 100px; border-radius: 70%;">
    
        <br>
        {{-- Formulario oculto que usamos para enviar el archivo --}}
        <form id="photo-upload-form" enctype="multipart/form-data">
            @csrf

            <label for="photo-input" 
                style="
                    cursor: pointer; 
                    display: inline-block; /* Importante para que el padding y el ancho funcionen */
                    margin-top: 10px;
                    padding: 8px 15px; /* Define el tamaño del botón */
                    background-color: #007bff; /* Color de fondo del botón (azul, estándar) */
                    color: white; /* Color del texto */
                    border: 1px solid #007bff; /* Borde */
                    border-radius: 4px; /* Esquinas redondeadas */
                    text-align: center;
                    text-decoration: none;
                    transition: background-color 0.3s; /* Efecto de transición para el hover */
                "
                onmouseover="this.style.backgroundColor='#0056b3';"
                onmouseout="this.style.backgroundColor='#007bff';">
                Cambiar Foto
            </label>
            
            {{-- Input de archivo oculto --}}
            <input type="file" 
                name="photo" 
                id="photo-input" 
                accept="image/*" 
                style="display: none;">
                
            {{-- Opcional: Mostrar un indicador de carga --}}
            <p id="loading-indicator" style="display: none; color: blue;">Subiendo...</p>
            <p id="status-message" style="margin-top: 10px;"></p>
        </form>

        <script>
            document.getElementById('photo-input').addEventListener('change', function(event) {
                // Ejecutamos la función de subida al detectar un cambio de archivo
                uploadProfilePhoto(event.target.files[0]);
            });

            function uploadProfilePhoto(file) {
                if (!file) return;

                const form = document.getElementById('photo-upload-form');
                const formData = new FormData(form);
                formData.append('photo', file);
                
                // El método POST es necesario para enviar archivos,
                // pero Laravel requiere @method('PUT') o @method('PATCH')
                // para las acciones de actualización (lo gestionamos en la URL/Ruta)
                
                const loadingIndicator = document.getElementById('loading-indicator');
                const statusMessage = document.getElementById('status-message');
                
                loadingIndicator.style.display = 'block';
                statusMessage.textContent = ''; // Limpiar mensajes anteriores

                // Usamos la API Fetch para la solicitud AJAX
                fetch('/profile/update/photo', { // ⬅️ Asegúrate que esta es tu URL de actualización
                    method: 'POST', // Usamos POST porque estamos enviando un formulario con archivos
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, // Incluir el token CSRF
                        // NO DEBEMOS establecer 'Content-Type': 'multipart/form-data', Fetch lo hace automáticamente
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    loadingIndicator.style.display = 'none';

                    if (data.success) {
                        // Actualizar la imagen en la vista (DOM) sin recargar la página
                        const currentPhoto = document.getElementById('current-photo');
                        // Añadimos un timestamp para forzar al navegador a cargar la nueva imagen y no usar la caché
                        currentPhoto.src = data.path + '?' + new Date().getTime(); 
                        
                        statusMessage.style.color = 'green';
                        statusMessage.textContent = 'Foto actualizada con éxito.';
                    } else {
                        statusMessage.style.color = 'red';
                        statusMessage.textContent = data.message || 'Error al subir la foto.';
                    }
                })
                .catch(error => {
                    loadingIndicator.style.display = 'none';
                    statusMessage.style.color = 'red';
                    statusMessage.textContent = 'Error de conexión o del servidor.';
                    console.error('Error:', error);
                });
            }
        </script>
</section>
