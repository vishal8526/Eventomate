document.getElementById('eventForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const mediaInput = document.getElementById('media');
    const gallery = document.getElementById('gallery');

    // Clear previous gallery items
    gallery.innerHTML = '';

    // Handle media uploads
    for (const file of mediaInput.files) {
        const mediaItem = document.createElement('div');
        mediaItem.classList.add('media-item');

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.width = 100; mediaItem.appendChild(img);
        } else if (file.type.startsWith('video/')) {
            const video = document.createElement('video');
            video.src = URL.createObjectURL(file);
            video.width = 100;
            video.controls = true;
            mediaItem.appendChild(video);
        }

        gallery.appendChild(mediaItem);
    }

    // Handle 3D model upload (placeholder functionality)
    const modelInput = document.getElementById('model3D');
    const modelPlaceholder = document.getElementById('modelPlaceholder');

    if (modelInput.files.length > 0) {
        modelPlaceholder.textContent = '3D Model uploaded: ' + modelInput.files[0].name;
    } else {
        modelPlaceholder.textContent = '3D Model will be displayed here.';
    }

    // Reset the form
    this.reset();
});