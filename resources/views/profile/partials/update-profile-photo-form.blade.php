<section>
    <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
        @csrf
        
        <h2>Actualizar Foto de Perfil</h2>

        <label for="photo">Foto de Perfil Actual</label>
        {{-- (Ajusta la ruta de la imagen según tu configuración de Storage) --}}
        <img src="{{ asset('storage/' . ($user->profile_photo_path ?? 'profiles/default-profile.png')) }}" 
            alt="Foto de perfil" style="width: 100px; border-radius: 50%;">

        <br>
        <label for="photo" class="mt-3">Seleccionar Nueva Foto</label>
        <input type="file" id="photo" name="photo" accept="image/*">
        @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
        <br>
        <button type="submit" class="mt-3">Subir Foto</button>
    </form>
</section>
