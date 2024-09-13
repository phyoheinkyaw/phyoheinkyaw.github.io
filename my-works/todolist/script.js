document.addEventListener('DOMContentLoaded', () => {
    const taskInput = document.getElementById('new-task-input');
    const taskList = document.getElementById('task-list');
    const addTaskBtn = document.getElementById('add-task-btn');
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    
    let isDarkMode = localStorage.getItem('theme') === 'dark'; // Check if dark mode is stored in localStorage
  
    // Apply saved theme
    if (isDarkMode) {
      document.body.classList.add('dark-mode');
      themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i>'; // Show dark mode icon
    }
  
    // Toggle theme button click event
    themeToggleBtn.addEventListener('click', () => {
      isDarkMode = !isDarkMode;
      document.body.classList.toggle('dark-mode');
      
      if (isDarkMode) {
        themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i>'; // Show dark mode icon
        localStorage.setItem('theme', 'dark'); // Save theme preference
      } else {
        themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i>'; // Show light mode icon
        localStorage.setItem('theme', 'light'); // Save theme preference
      }
    });
  
    // Load tasks from localStorage
    loadTasksFromLocalStorage();
  
    // Add task button event
    addTaskBtn.addEventListener('click', addTask);
  
    // Add keyboard event for 'Enter' key to add task
    taskInput.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        addTask();
      }
    });
  
    function addTask() {
      const taskText = taskInput.value.trim();
      if (taskText === '') return; // Ignore empty input
  
      const taskItem = createTaskItem(taskText);
      taskList.appendChild(taskItem);
      saveTasksToLocalStorage();
  
      taskInput.value = ''; // Clear the input
    }
  
    function createTaskItem(taskText) {
      // Create a Bootstrap column element
      const col = document.createElement('div');
      col.className = 'col-lg-4 col-md-6'; // Set the column width for lg and md screens
  
      // Create a Bootstrap card element
      const card = document.createElement('div');
      card.className = 'card';
  
      const cardBody = document.createElement('div');
      cardBody.className = 'card-body';
  
      const taskTextSpan = document.createElement('span');
      taskTextSpan.textContent = taskText;
  
      // Create the delete button with Font Awesome icon
      const deleteBtn = document.createElement('button');
      deleteBtn.className = 'btn btn-danger'; // Bootstrap btn-danger class for styling
      deleteBtn.innerHTML = '<i class="fas fa-trash"></i>'; // Font Awesome trash icon
  
      cardBody.appendChild(taskTextSpan);
      cardBody.appendChild(deleteBtn);
      card.appendChild(cardBody);
      col.appendChild(card); // Append the card to the column
  
      // Mark task as completed or delete
      cardBody.addEventListener('click', (e) => {
        if (e.target.tagName === 'BUTTON' || e.target.tagName === 'I') {
          // Delete task
          col.remove();
          saveTasksToLocalStorage();
        } else {
          // Toggle completed status
          cardBody.classList.toggle('completed');
          saveTasksToLocalStorage();
        }
      });
  
      return col; // Return the column element with the card inside
    }
  
    function saveTasksToLocalStorage() {
      const tasks = [];
      taskList.querySelectorAll('.card-body').forEach(task => {
        tasks.push({
          text: task.querySelector('span').innerText,
          completed: task.classList.contains('completed')
        });
      });
      localStorage.setItem('tasks', JSON.stringify(tasks));
    }
  
    function loadTasksFromLocalStorage() {
      const tasks = JSON.parse(localStorage.getItem('tasks') || '[]');
      tasks.forEach(task => {
        const taskItem = createTaskItem(task.text);
        if (task.completed) {
          taskItem.querySelector('.card-body').classList.add('completed');
        }
        taskList.appendChild(taskItem);
      });
    }
  });
  