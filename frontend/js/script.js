const input = document.getElementById("taskInput");
const list = document.getElementById("taskList");
const button = document.getElementById("addTaskBtn");
window.onload = loadTasks;
function createTaskElement(tasktext) {
  let li = document.createElement("li");
  li.className = "task";

  li.innerHTML = `
    <span>${tasktext}</span>
    <div>
    <button class="complete-btn">✅</button>
    <button class="delete-btn">❌</button>
    </div>
    `;
  return li;
}

button.addEventListener("click", async function () {
  let tasktext = input.ariaValueMax.trim();
  if (tasktext === "") return;
  let response = await fetch(
    "http://localhost/TASK_MANAGER/backend/add_task.php",
    {
      method: "POST",
      headers: {
        "Content-Type": "appicatin/x-www-form-urlencoded",
      },
      body: "task = " + encodeURIComponent(tasktext),
    },
  );
  let data = await response.json();
  if (data.success) {
    loadTasks();
    input.value = "";
  }
});

list.addEventListener("click",async function (e) {
  if (e.target.classList.contains("delete-btn")) {

    let id = e.target.dataset.id;

    await fetch("http://localhost/TASK_MANAGER/backend/delete_task.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id
    });

    loadTasks();
}

 if (e.target.classList.contains("complete-btn")) {

    let id = e.target.dataset.id;

    await fetch("http://localhost/task-manager/backend/update_task.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id + "&status=completed"
    });

    loadTasks();
}
});
input.addEventListener("keypress", function (e) {
  if (e.key === "Enter") {
    button.click();
  }
});

async function loadTasks() {
  let response = await fetch(
    "http://localhost/TASK_MANAGER/backend/get_tasks.php",
  );

  let tasks = await response.json();

  list.innerHTML = "";
  tasks.forEach((task) => {
    let li = document.createElement("li");
    li.className = "task";
    if (task.status === "completed") {
      li.classList.add("completed");
    }
    li.innerhtml = `          <span>${task.task_text}</span>
          <div>
            <button class="complete-btn" data-id="${task.id}">✅</button>
            <button class="delete-btn" data-id="${task.id}">❌</button>
          </div>`;
    list.appendChild(li);
  });
}
