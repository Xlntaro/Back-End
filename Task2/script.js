// Базовий URL для API
const API_BASE_URL = 'api';

// Елементи DOM
const createForm = document.getElementById('createForm');
const editForm = document.getElementById('editForm');
const editPanel = document.getElementById('editPanel');
const cancelEditBtn = document.getElementById('cancelEdit');
const notesListDiv = document.getElementById('notesList');
const createMessage = document.getElementById('createMessage');
const createError = document.getElementById('createError');
const editMessage = document.getElementById('editMessage');
const editError = document.getElementById('editError');

// Завантаження заміток при завантаженні сторінки
document.addEventListener('DOMContentLoaded', loadNotes);

// Обробник відправлення форми створення замітки
createForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Очистка попередніх повідомлень
    createMessage.textContent = '';
    createError.textContent = '';

    // Отримання даних із форми
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();

    // Валідація на клієнті
    if (!title || !content) {
        createError.textContent = 'Заголовок і текст обов’язкові!';
        return;
    }

    // Створення даних для запиту
    const noteData = { title, content };

    try {
        const response = await fetch(`${API_BASE_URL}/create.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(noteData)
        });

        const data = await response.json();

        if (data.success) {
            createMessage.textContent = 'Замітку успішно додано!';
            createForm.reset();
            loadNotes(); // Оновлення списку заміток
        } else {
            createError.textContent = data.message || 'Не вдалося додати замітку!';
        }
    } catch (error) {
        createError.textContent = 'Сталася помилка. Спробуйте ще раз.';
        console.error('Помилка створення замітки:', error);
    }
});

// Функція завантаження заміток
async function loadNotes() {
    try {
        const response = await fetch(`${API_BASE_URL}/read.php`);
        const data = await response.json();

        if (data.success) {
            displayNotes(data.notes);
        } else {
            notesListDiv.innerHTML = `<p class="error-message">${data.message || 'Не вдалося завантажити замітки!'}</p>`;
        }
    } catch (error) {
        notesListDiv.innerHTML = '<p class="error-message">Сталася помилка під час завантаження заміток.</p>';
        console.error('Помилка завантаження заміток:', error);
    }
}

// Функція відображення заміток
function displayNotes(notes) {
    if (!notes || notes.length === 0) {
        notesListDiv.innerHTML = '<p>Заміток немає.</p>';
        return;
    }

    let html = '';
    notes.forEach(note => {
        html += `
        <div class="note">
            <h3>${note.title}</h3>
            <p>${note.content}</p>
            <p><small>Створено: ${new Date(note.created_at).toLocaleString('uk-UA')}</small></p>
            <div class="actions">
                <button onclick="editNote(${note.id}, '${note.title}', '${note.content}')">Редагувати</button>
                <button onclick="deleteNote(${note.id})">Видалити</button>
            </div>
        </div>
        `;
    });

    notesListDiv.innerHTML = html;
}

// Функція редагування замітки
window.editNote = function(id, title, content) {
    editPanel.style.display = 'block';
    document.getElementById('editId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editContent').value = content;
    editPanel.scrollIntoView({ behavior: 'smooth' });
};

// Обробник скасування редагування
cancelEditBtn.addEventListener('click', () => {
    editPanel.style.display = 'none';
    editForm.reset();
});

// Обробник відправлення форми редагування
editForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Очистка попередніх повідомлень
    editMessage.textContent = '';
    editError.textContent = '';

    // Отримання даних із форми
    const id = document.getElementById('editId').value;
    const title = document.getElementById('editTitle').value.trim();
    const content = document.getElementById('editContent').value.trim();

    // Валідація на клієнті
    if (!title || !content) {
        editError.textContent = 'Заголовок і текст обов’язкові!';
        return;
    }

    // Створення даних для запиту
    const noteData = { id, title, content };

    try {
        const response = await fetch(`${API_BASE_URL}/update.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(noteData)
        });

        const data = await response.json();

        if (data.success) {
            editMessage.textContent = 'Замітку успішно оновлено!';
            loadNotes();
            setTimeout(() => {
                editPanel.style.display = 'none';
                editForm.reset();
            }, 2000);
        } else {
            editError.textContent = data.message || 'Не вдалося оновити замітку!';
        }
    } catch (error) {
        editError.textContent = 'Сталася помилка. Спробуйте ще раз.';
        console.error('Помилка оновлення замітки:', error);
    }
});

// Функція видалення замітки
window.deleteNote = async function(id) {
    if (!confirm('Ви впевнені, що хочете видалити цю замітку?')) {
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/delete.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });

        const data = await response.json();

        if (data.success) {
            loadNotes();
        } else {
            alert(data.message || 'Не вдалося видалити замітку!');
        }
    } catch (error) {
        alert('Сталася помилка під час видалення замітки.');
        console.error('Помилка видалення замітки:', error);
    }
};