document.querySelector('button').addEventListener('click', async () => {
    const isbnInput = document.querySelector('#isbn');
    const isbn = isbnInput.value.trim(); // Eliminar espacios en blanco

    if (!isbn.match(/^\d{10}(\d{3})?$/)) {
        alert('Por favor, ingrese un ISBN válido de 10 o 13 dígitos.');
        return;
    }

    try {
        const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }

        const data = await response.json();

        if (data.totalItems > 0) {
            const book = data.items[0].volumeInfo;
            const titulo = book.title + (book.subtitle ? `: ${book.subtitle}` : '');
            document.querySelector('#titulo').value = book.title + (book.subtitle ? `: ${book.subtitle}` : '');
            document.querySelector('#autor').value = book.authors ? book.authors.join(', ') : 'Desconocido';
            document.querySelector('#genero').value = book.categories ? book.categories.join(', ') : 'No disponible';
            document.querySelector('#editorial').value = book.publisher || 'No disponible';
            document.querySelector('#fecha_publicacion').value = book.publishedDate || 'No disponible';
            document.querySelector('#portada').value = book.imageLinks?.thumbnail || ''; 
            document.querySelector('#descripcion').value = book.description || 'Sin descripción disponible';
        } else {
            alert('No se encontró información para el ISBN proporcionado.');
        }
    } catch (error) {
        console.error('Error al buscar datos en la API de Google Books:', error);
        alert('Hubo un error al buscar los datos. Por favor, inténtelo de nuevo más tarde.');
    }
});
