<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Simple Task Manager</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="page-bg"></div>
    <main class="app-shell">
        <section id="authCard" class="card auth-card">
            <h1>Task Manager</h1>
            <p class="subtitle">Simple local app with PHP sessions</p>

            <form id="authForm" class="auth-form">
                <label>
                    Username
                    <input id="usernameInput" type="text" required />
                </label>

                <label>
                    Password
                    <input id="passwordInput" type="password" required />
                </label>

                <div class="btn-row">
                    <button type="submit" data-mode="login">Login</button>
                    <button type="button" id="registerBtn" class="secondary">Register</button>
                </div>
            </form>
        </section>

        <section id="taskCard" class="card task-card hidden">
            <header class="task-header">
                <div>
                    <h2>My Tasks</h2>
                    <p id="welcomeText" class="subtitle"></p>
                </div>
                <button id="logoutBtn" class="secondary">Logout</button>
            </header>

            <form id="taskForm" class="task-form">
                <input id="taskTitleInput" type="text" placeholder="Write a task..." required />
                <button type="submit">Add</button>
            </form>

            <div class="filter-row">
                <button type="button" class="filter-btn active" data-status="all">All</button>
                <button type="button" class="filter-btn" data-status="pending">Pending</button>
                <button type="button" class="filter-btn" data-status="completed">Completed</button>
            </div>

            <ul id="taskList" class="task-list"></ul>
        </section>
    </main>

    <div id="toast" class="toast hidden"></div>

    <script src="script.js"></script>
</body>
</html>
