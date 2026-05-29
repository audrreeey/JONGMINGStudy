(function () {
    const savedTheme = localStorage.getItem('studysync-theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark');
    }

    function refreshIcons() {
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    function toggleTheme() {
        document.body.classList.toggle('dark');
        localStorage.setItem('studysync-theme', document.body.classList.contains('dark') ? 'dark' : 'light');
    }

    document.querySelectorAll('#themeToggle, #settingsThemeToggle').forEach((button) => {
        button.addEventListener('click', toggleTheme);
    });

    const quotes = [
        'Small progress every day creates serious momentum.',
        'Focus on the next clear step, then the next one.',
        'A calm plan beats a crowded mind.',
        'Study with intention, rest without guilt.',
        'Deadlines get easier when they become visible.'
    ];

    const quoteWidget = document.getElementById('quoteWidget');
    if (quoteWidget) {
        quoteWidget.textContent = quotes[Math.floor(Math.random() * quotes.length)];
    }

    let timerSeconds = 25 * 60;
    let timerInterval = null;
    let focusMode = true;
    const timerDisplay = document.getElementById('timerDisplay');
    const timerMode = document.getElementById('timerMode');

    function updateTimer() {
        if (!timerDisplay) return;
        const minutes = Math.floor(timerSeconds / 60).toString().padStart(2, '0');
        const seconds = (timerSeconds % 60).toString().padStart(2, '0');
        timerDisplay.textContent = `${minutes}:${seconds}`;
        timerMode.textContent = focusMode ? 'Focus session' : 'Break time';
    }

    function startTimer() {
        if (timerInterval) return;
        timerInterval = setInterval(() => {
            timerSeconds -= 1;
            if (timerSeconds <= 0) {
                focusMode = !focusMode;
                timerSeconds = focusMode ? 25 * 60 : 5 * 60;
            }
            updateTimer();
        }, 1000);
    }

    function pauseTimer() {
        clearInterval(timerInterval);
        timerInterval = null;
    }

    function resetTimer() {
        pauseTimer();
        focusMode = true;
        timerSeconds = 25 * 60;
        updateTimer();
    }

    document.getElementById('timerStart')?.addEventListener('click', startTimer);
    document.getElementById('timerPause')?.addEventListener('click', pauseTimer);
    document.getElementById('timerReset')?.addEventListener('click', resetTimer);
    updateTimer();

    if (window.calendarTasks) {
        const selectedTitle = document.getElementById('selectedDateTitle');
        const selectedTasks = document.getElementById('selectedDateTasks');

        document.querySelectorAll('.calendar-day[data-date]').forEach((day) => {
            day.addEventListener('click', () => {
                const date = day.dataset.date;
                const tasks = window.calendarTasks[date] || [];
                selectedTitle.textContent = new Date(`${date}T00:00:00`).toLocaleDateString(undefined, {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                if (!tasks.length) {
                    selectedTasks.innerHTML = '<div class="empty-state">No tasks on this date.</div>';
                    return;
                }

                selectedTasks.innerHTML = tasks.map((task) => `
                    <article class="task-item">
                        <span class="priority-dot ${task.priority.toLowerCase()}"></span>
                        <div>
                            <strong>${escapeHtml(task.title)}</strong>
                            <small>${escapeHtml(task.category_name)} &middot; ${escapeHtml(task.priority)} priority</small>
                        </div>
                        <span class="status-pill">${escapeHtml(task.status)}</span>
                    </article>
                `).join('');
            });
        });
    }

    if (window.analyticsData && window.Chart) {
        const textColor = getComputedStyle(document.body).getPropertyValue('--text').trim();
        const gridColor = getComputedStyle(document.body).getPropertyValue('--border').trim();

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending'],
                datasets: [{
                    data: [window.analyticsData.completed, window.analyticsData.pending],
                    backgroundColor: ['#4CAF50', '#5B6CFF'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: { color: textColor, font: { family: 'Inter' } }
                    }
                }
            }
        });

        new Chart(document.getElementById('courseChart'), {
            type: 'bar',
            data: {
                labels: window.analyticsData.courseLabels,
                datasets: [{
                    label: 'Tasks',
                    data: window.analyticsData.courseValues,
                    backgroundColor: '#7C4DFF',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { ticks: { color: textColor }, grid: { color: gridColor } },
                    y: { ticks: { color: textColor, precision: 0 }, grid: { color: gridColor } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    refreshIcons();
})();
