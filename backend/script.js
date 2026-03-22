const API_BASE = `http://${window.location.hostname}/backend`;

const authCard = document.getElementById('authCard');
const taskCard = document.getElementById('taskCard');
const authForm = document.getElementById('authForm');
const registerBtn = document.getElementById('registerBtn');
const logoutBtn = document.getElementById('logoutBtn');
const usernameInput = document.getElementById('usernameInput');
const passwordInput = document.getElementById('passwordInput');
const taskForm = document.getElementById('taskForm');
const taskTitleInput = document.getElementById('taskTitleInput');
const taskList = document.getElementById('taskList');
const welcomeText = document.getElementById('welcomeText');
const toast = document.getElementById('toast');

let currentStatus = 'all';

function showToast(message) {
    toast.textContent = message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 1800);
}

function encodeBody(data) {
    return new URLSearchParams(data).toString();
}

async function getJson(url) {
    const response = await fetch(url, {
        method: 'GET',
        credentials: 'include'
    });
    return response.json();
}

async function postJson(url, data) {
    const response = await fetch(url, {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: encodeBody(data)
    });
    return response.json();
}

function setLoggedInState(user) {
    authCard.classList.add('hidden');
    taskCard.classList.remove('hidden');
    welcomeText.textContent = `Hello, ${user.username}`;
}

function setLoggedOutState() {
    taskCard.classList.add('hidden');
    authCard.classList.remove('hidden');
    taskList.innerHTML = '';
}

function escapeHtml(text) {
    return text
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function renderTasks(tasks) {
    if (!tasks.length) {
        taskList.innerHTML = '<li class="task-item">No tasks yet.</li>';
        return;
    }

    taskList.innerHTML = tasks.map((task) => {
        const doneClass = task.status === 'completed' ? 'done' : '';
        const toggleLabel = task.status === 'completed' ? 'Pending' : 'Done';
        return `
            <li class="task-item ${doneClass}">
                <div>
                    <div class="task-title">${escapeHtml(task.title)}</div>
                    <small>${task.status}</small>
                </div>
                <div class="task-actions">
                    <button data-action="toggle" data-id="${task.id}">${toggleLabel}</button>
                    <button data-action="delete" data-id="${task.id}" class="danger">Delete</button>
                </div>
            </li>
        `;
    }).join('');
}

async function loadTasks() {
    const url = currentStatus === 'all'
        ? `${API_BASE}/get_tasks.php`
        : `${API_BASE}/get_tasks.php?status=${encodeURIComponent(currentStatus)}`;

    const result = await getJson(url);
    if (!result.ok) {
        showToast(result.message || 'Could not load tasks');
        return;
    }
    renderTasks(result.tasks);
}

async function login() {
    const result = await postJson(`${API_BASE}/login.php`, {
        username: usernameInput.value.trim(),
        password: passwordInput.value
    });

    if (!result.ok) {
        showToast(result.message || 'Login failed');
        return;
    }

    setLoggedInState(result.user);
    showToast('Logged in');
    loadTasks();
}

async function register() {
    const result = await postJson(`${API_BASE}/register.php`, {
        username: usernameInput.value.trim(),
        password: passwordInput.value
    });

    if (!result.ok) {
        showToast(result.message || 'Register failed');
        return;
    }

    setLoggedInState(result.user);
    showToast('Registered');
    loadTasks();
}

authForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    await login();
});

registerBtn.addEventListener('click', async () => {
    await register();
});

logoutBtn.addEventListener('click', async () => {
    const result = await postJson(`${API_BASE}/logout.php`, {});
    if (result.ok) {
        setLoggedOutState();
        showToast('Logged out');
    }
});

taskForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    const title = taskTitleInput.value.trim();
    if (!title) {
        return;
    }

    const result = await postJson(`${API_BASE}/create_task.php`, { title: title });
    if (!result.ok) {
        showToast(result.message || 'Create failed');
        return;
    }

    taskTitleInput.value = '';
    loadTasks();
});

taskList.addEventListener('click', async (event) => {
    if (!(event.target instanceof HTMLButtonElement)) {
        return;
    }

    const action = event.target.dataset.action;
    const taskId = event.target.dataset.id;
    if (!action || !taskId) {
        return;
    }

    const endpoint = action === 'toggle' ? 'toggle_task.php' : 'delete_task.php';
    const result = await postJson(`${API_BASE}/${endpoint}`, { task_id: taskId });

    if (!result.ok) {
        showToast(result.message || 'Task action failed');
        return;
    }

    loadTasks();
});

document.querySelectorAll('.filter-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');
        currentStatus = btn.dataset.status || 'all';
        loadTasks();
    });
});

async function boot() {
    const result = await getJson(`${API_BASE}/session.php`);
    if (result.loggedIn) {
        setLoggedInState(result.user);
        loadTasks();
        return;
    }
    setLoggedOutState();
}

boot();
