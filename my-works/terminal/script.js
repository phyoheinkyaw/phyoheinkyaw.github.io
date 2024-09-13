document.querySelector('.cmd-input').focus();

const inputField = document.querySelector('.cmd-input');
const outputDiv = document.querySelector('.output');
const terminalBody = document.querySelector('.terminal-body');
const subtitleText = document.querySelector('.subtitle');
const titleText = document.querySelector('.title-text');
const promptSpan = document.querySelector('.prompt');  // Update prompt element

let commandHistory = [];
let historyIndex = -1;
let currentSelection = '';  // Track which selection mode is active

// Command mappings
const linkMapping = {
    "LinkedIn": "https://linkedin.com/in/phyo-hein-kyaw",
    "GitHub": "https://github.com/phyoheinkyaw",
    "Facebook": "https://www.facebook.com/phyoheinkyaw.bm",
    "Messenger": "https://m.me/phyoheinkyaw.bm",
    "Line": "https://line.me/ti/p/ceFlz_8EBz",
    "Phyo Hein Kyaw Resume": "Phyo_Hein_Kyaw_Resume.pdf",
    "Blog": "https://myblog.com"
};

// Skills descriptions
const skillDescriptions = {
    1: "JavaScript: A versatile, high-level programming language primarily used for adding interactive elements to websites and web applications. It allows developers to create dynamic content, such as animations, form validations, real-time updates, and more. As a core technology of the web, alongside HTML and CSS, JavaScript plays a crucial role in front-end development, enabling websites to respond to user interactions without needing to refresh the page. It is also widely used in back-end development through environments like Node.js. JavaScript is known for its flexibility, ease of integration, and extensive support across browsers and platforms.",
    2: "CSS: A stylesheet language used for describing the presentation and design of web pages. It controls the layout, colors, fonts, spacing, and overall visual appearance of HTML elements, allowing developers to create aesthetically appealing and responsive websites. By separating content from design, CSS enables more efficient web development and maintenance. It also supports responsive design techniques, ensuring websites look good on various devices and screen sizes. CSS is a cornerstone technology alongside HTML and JavaScript in front-end web development, providing a consistent visual framework for web applications and pages.",
    3: "HTML: The standard language used to create and structure content on the web. It defines the layout and structure of web pages using a series of elements and tags, such as headings, paragraphs, images, links, and lists. HTML serves as the foundation for all web pages, allowing developers to organize content in a readable and accessible way. It works in conjunction with CSS for styling and JavaScript for interactivity, making it one of the core technologies of the web. HTML is essential for building both static and dynamic websites, ensuring proper formatting and display across browsers.",
    4: "PHP (Laravel): A popular server-side scripting language used primarily for web development. Laravel, built on PHP, is a powerful and elegant web application framework designed to simplify and accelerate the development process. It follows the Model-View-Controller (MVC) architecture, promoting clean code organization and separation of concerns. Laravel offers a wide range of built-in tools, such as routing, authentication, and database management through an ORM (Eloquent), making it easier to build robust, scalable web applications. With features like Blade templating, task scheduling, and seamless integration with front-end technologies, Laravel enhances developer productivity while maintaining flexibility and security.",
    5: "MySQL: A widely-used open-source relational database management system (RDBMS) that allows users to store, manage, and retrieve data efficiently. It uses Structured Query Language (SQL) for database operations and is known for its reliability, performance, and ease of use. MySQL is commonly used in web development for managing large datasets and is the backbone of many web applications, providing structured storage for data in tables and supporting complex queries, indexing, and transactions.",
    6: "WordPress: A powerful, open-source content management system (CMS) built using PHP and MySQL. It allows users to create and manage websites and blogs easily without needing deep technical expertise. WordPress offers extensive customization through themes, plugins, and widgets, enabling users to build everything from personal blogs to large e-commerce platforms. Its intuitive interface, combined with a vast community of developers and designers, makes it one of the most popular CMS platforms in the world."
};

// Education descriptions
const educationDescriptions = {
    1: "B.Sc., Computing - University of Greenwich (2023): An undergraduate degree that provides a strong foundation in computer science and information technology. The program focuses on key areas such as software development, data structures, algorithms, networking, databases, and web technologies. Students gain practical experience in programming languages, system design, and project management, preparing them for diverse careers in the tech industry. Graduates are equipped with problem-solving skills, technical knowledge, and the ability to adapt to rapidly changing technological environments, making them valuable assets in roles such as software engineers, data analysts, IT consultants, and more.",
    2: "Bachelor of Law - Yadanabon University (2021): A Bachelor of Law degree from Yadanabon University based in Myanmar, provides students with a thorough grounding in Myanmar's legal system, laws, and practices. The curriculum focuses on key areas such as Myanmar constitutional law, civil and criminal law, administrative law, and legal procedures relevant to the country's legal framework. Students also gain skills in legal research, critical analysis, and interpretation of legal texts. Graduates are equipped to pursue careers in the legal field within Myanmar, including roles as legal advisors, advocates, or positions in governmental and judicial sectors, with the potential to further specialize in law at the national level.",
    3: "Level 5 Diploma in Computing (2019): An advanced qualification designed to provide students with a deeper understanding of core computing concepts. It builds on the foundational knowledge from Level 4 and covers areas such as database development, network security, software engineering, and web technologies. This qualification prepares students for more complex roles in the IT and computing industry, equipping them with practical and theoretical skills to solve advanced computing problems.",
    4: "Level 4 Diploma in Computing (2017): An entry-level qualification that introduces students to essential computing concepts. It covers topics such as programming, computer systems, data structures, and networking, providing a strong foundation for further study or entry into the IT workforce. Both diplomas are recognized stepping stones toward more advanced qualifications or specialized roles in the computing and IT sectors."
};

// Experience descriptions
const experienceDescriptions = {
    1: "As a Web Developer at AM Digital Marketing (2023 - Present), I focus on creating, developing, and maintaining responsive, user-friendly websites that meet client requirements. I utilize technologies such as HTML, CSS, JavaScript, PHP, and MySQL to build high-quality web solutions. My role involves close collaboration with the marketing team to ensure websites are optimized for SEO and performance. I also handle troubleshooting and technical issues to maintain the smooth functioning of client websites across various platforms.",
    2: "In my role as a Web Development Trainer at Ethan Tech Academy (2024 - Present), I conduct online classes that teach essential web development skills to students. My training covers a broad range of topics including PHP, MySQL, HTML, CSS, JavaScript, and WordPress. I focus on guiding students through both theoretical concepts and practical exercises to help them develop the technical skills needed to succeed in the web development field. My teaching style emphasizes clear communication, timely feedback, and hands-on learning.",
    3: "Previously, I worked as a Web Development Trainer at Winner Tech Professional Training Center (2021 - 2022), where I instructed students on the fundamentals of web development. I specialized in teaching PHP for dynamic web application development and MySQL database design. My approach combined virtual lectures with interactive demonstrations, giving students a solid understanding of web technologies. I prioritized student success through personalized support, practical projects, and structured learning modules to help them build real-world skills."
};

// Project descriptions
const projectDescriptions = {
    1: "Portfolio Website: A personal portfolio website showcasing my skills, experience, and projects.",
    2: "ToDo App: A task management app that allows users to add, edit, and delete tasks.",
    3: "Weather App: A web application that fetches and displays weather information using an API."
};

// Awards descriptions
const awardsDescriptions = {
    1: "At the 55th Myanma Gems Emporium (2018), I was awarded by the Myanmar Computer Company (MCC) for my role as a Data Analyst. In this position, I was responsible for interpreting and analyzing large datasets to provide actionable insights that informed decision-making during the event. I developed and implemented accurate data collection systems, managed databases, and ensured the reliability of the data used for operational and strategic purposes. This award recognized my expertise in data interpretation, database management, and my contributions to the success of the emporium through efficient data-driven analysis.",
    2: "B.Sc., Computing - University of Greenwich (2023): An undergraduate degree that provides a strong foundation in computer science and information technology. The program focuses on key areas such as software development, data structures, algorithms, networking, databases, and web technologies. Students gain practical experience in programming languages, system design, and project management, preparing them for diverse careers in the tech industry. Graduates are equipped with problem-solving skills, technical knowledge, and the ability to adapt to rapidly changing technological environments, making them valuable assets in roles such as software engineers, data analysts, IT consultants, and more."
};

// Language descriptions
const languageDescriptions = {
    1: "As a native Burmese speaker, I possess full proficiency in the Burmese language, including reading, writing, speaking, and comprehension. This fluency allows me to effectively communicate and collaborate in professional and social settings, particularly within Myanmar-based projects or organizations. My native language skills also enable me to provide accurate translations, engage in clear communication with Burmese-speaking clients and colleagues, and navigate local cultural and linguistic nuances in a professional environment.",
    2: "I have an intermediate working level of English (B2), which allows me to effectively communicate in both professional and everyday contexts. I can engage in detailed discussions on familiar topics, understand complex texts, write clear reports, and participate in meetings or conversations in English with relative ease. This level of proficiency enables me to work well in international environments and handle most workplace communication confidently."
};

// Ensure the input is always focused
document.addEventListener('click', function () {
    inputField.focus();
});

// Handle commands and command history
inputField.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        const command = this.value.trim().toLowerCase();
        if (command) {
            commandHistory.push(command);
            historyIndex = -1;
            this.value = '';

            if (currentSelection) {
                // Only allow 'exit', selection numbers, and text in selection mode
                if (command === 'exit') {
                    handleExit();  // Handle exit from selection mode
                } else if (isNumberOrText(command)) {
                    processSelection(command);  // Process selection numbers or names
                } else {
                    displayInvalidCommand();  // Invalid command in selection mode
                }
            } else {
                // Process general commands
                processCommand(command);
            }
        }
    } else if (e.key === 'ArrowUp' && commandHistory.length > 0) {
        if (historyIndex < commandHistory.length - 1) {
            historyIndex++;
            this.value = commandHistory[commandHistory.length - 1 - historyIndex];
        }
    } else if (e.key === 'ArrowDown' && commandHistory.length > 0) {
        if (historyIndex > 0) {
            historyIndex--;
            this.value = commandHistory[commandHistory.length - 1 - historyIndex];
        } else {
            historyIndex = -1;
            this.value = '';
        }
    }
});

// Typing effect function with link replacement
function typeEffect(element, text, speed = 20, callback) {
    let index = 0;

    const interval = setInterval(() => {
        element.textContent += text[index];
        index++;
        scrollToBottom();

        if (index === text.length) {
            clearInterval(interval);
            element.classList.remove('typing-effect');
            element.innerHTML = replaceWithLinks(element.textContent);
            scrollToBottom();
            if (callback) callback();
        }
    }, speed);
}

// Function to replace specific keywords with links
function replaceWithLinks(text) {
    for (const keyword in linkMapping) {
        const url = linkMapping[keyword];
        const regex = new RegExp(keyword, 'g');
        text = text.replace(regex, `<a href="${url}" target="_blank">${keyword}</a>`);
    }
    return text;
}

// Scroll terminal body to bottom
function scrollToBottom() {
    setTimeout(() => {
        terminalBody.scrollTop = terminalBody.scrollHeight;
    }, 100);
}

// Process commands
function processCommand(command) {
    const newOutput = document.createElement('div');
    newOutput.classList.add('typing-effect');
    const defaultPrefix = "> ";

    switch (command) {
        case 'help':
            showHelp(newOutput);
            break;
        case 'about':
            typeEffect(newOutput, defaultPrefix + "Hi, I'm Phyo Hein Kyaw, a passionate web developer with extensive experience in front-end and back-end development. I specialize in creating responsive, user-friendly websites and web applications using technologies like HTML, CSS, JavaScript, PHP (Laravel), MySQL, and WordPress. Currently, I work as a Freelance Web Developer at AM Digital Marketing (2023 - Present), where I collaborate with teams to deliver high-quality web solutions tailored to client needs.\n\nIn addition to development, I also share my knowledge as a Web Development Trainer at Ethan Tech Academy (2024 - Present), guiding students in mastering essential web technologies. I’ve previously taught at Winner Tech Professional Training Center (2021 - 2022), helping aspiring developers build real-world skills. I hold a B.Sc. in Business Information Technology from the University of Greenwich and have also received recognition as a Data Analyst at the 55th Myanma Gems Emporium (2018).\n\nI am always eager to take on new challenges, develop innovative solutions, and help others grow in the tech industry.", 20);
            resetPrompt();  // Reset input span
            break;
        case 'projects':
            setSelectionMode('projects', projectDescriptions);
            typeEffect(newOutput, defaultPrefix + "Projects:\n1. Portfolio Website\n2. ToDo App\n3. Weather App\n\nPlease select a project by number or type the project name (or type 'exit' to leave):", 20);
            break;
        case 'contact':
            typeEffect(newOutput, defaultPrefix + "You can contact me via email at: phyoheinkyaw0@outlook.com", 20);
            resetPrompt();  // Reset input span
            break;
        case 'resume':
            typeEffect(newOutput, defaultPrefix + "Check out my resume here: Phyo Hein Kyaw Resume", 20);
            resetPrompt();  // Reset input span
            break;
        case 'education':
            setSelectionMode('education', educationDescriptions);
            typeEffect(newOutput, defaultPrefix + "Education:\n1. B.Sc., BIT - University of Greenwich (2023)\n2. Bachelor of Law - Yadanabon University (2021)\n3. Level 5 Diploma in Computing (2019)\n4. Level 4 Diploma in Computing (2017)\n\nPlease select an education entry by number or type the education name (or type 'exit' to leave):", 20);
            break;
        case 'experience':
            setSelectionMode('experience', experienceDescriptions);
            typeEffect(newOutput, defaultPrefix + "Experience:\n1. Web Developer - AM Digital Marketing (2023 - Present)\n2. Web Development Trainer - Ethan Tech Academy (2024 - Present)\n3. Web Development Trainer - Winner Tech Professional Training Center (2021 - 2022)\n\nPlease select an experience entry by number or type the experience name (or type 'exit' to leave):", 20);
            break;
        case 'skills':
            setSelectionMode('skills', skillDescriptions);
            typeEffect(newOutput, defaultPrefix + "Skills:\n1. JavaScript\n2. CSS\n3. HTML\n4. PHP (Laravel)\n5. MySQL\n6. WordPress\n\nPlease select a skill by number or type the skill name (or type 'exit' to leave):", 20);
            break;
        case 'social':
            typeEffect(newOutput, defaultPrefix + 'Connect with me on: LinkedIn, GitHub, Facebook, Messenger or Line', 20);
            resetPrompt();  // Reset input span
            break;
        case 'awards':
            setSelectionMode('awards', awardsDescriptions);
            typeEffect(newOutput, defaultPrefix + "Awards:\n1. 55th Myanma Gems Emporium (2018)\n2. B.Sc., BIT (University Of Greenwich - 2023)\n\nPlease select an award by number or type the award name (or type 'exit' to leave):", 20);
            break;
        case 'language':
            setSelectionMode('language', languageDescriptions);
            typeEffect(newOutput, defaultPrefix + "Languages:\n1. Burmese (Native)\n2. English\n\nPlease select a language by number or type the language name (or type 'exit' to leave):", 20);
            break;
        case 'theme':
            toggleTheme(newOutput);  // Toggle theme
            break;
        case 'cls':
        case 'clear':
            outputDiv.innerHTML = '';
            return;
        default:
            typeEffect(newOutput, defaultPrefix + `${command}: command not found`, 20);
            resetPrompt();  // Reset input span
    }

    outputDiv.appendChild(newOutput);
    outputDiv.appendChild(document.createElement('br'));
    scrollToBottom();
}

// Toggle light/dark mode after type effect
function toggleTheme(newOutput) {
    const themeText = document.body.classList.contains('light-mode') ? "Activating Dark Mode..." : "Activating Light Mode...";

    // First, display the theme activation message
    typeEffect(newOutput, `> ${themeText}`, 20, function() {
        // Toggle the theme after the type effect is finished
        document.body.classList.toggle('light-mode');
        resetPrompt(); // Reset the input prompt after toggling the theme
    });
}


// Process selection from lists (skills, education, experience, etc.)
function processSelection(input) {
    const newOutput = document.createElement('div');
    newOutput.classList.add('typing-effect');
    const defaultPrefix = "> ";

    const selectedNumber = parseInt(input);
    const descriptions = currentSelection === 'skills' ? skillDescriptions :
                         currentSelection === 'education' ? educationDescriptions :
                         currentSelection === 'experience' ? experienceDescriptions :
                         currentSelection === 'projects' ? projectDescriptions :
                         currentSelection === 'awards' ? awardsDescriptions :
                         currentSelection === 'language' ? languageDescriptions : null;

    if (descriptions && !isNaN(selectedNumber) && descriptions[selectedNumber]) {
        typeEffect(newOutput, defaultPrefix + descriptions[selectedNumber], 20);
    } else if (descriptions && Object.values(descriptions).some(desc => desc.toLowerCase().includes(input.toLowerCase()))) {
        const selectedKey = Object.keys(descriptions).find(key => descriptions[key].toLowerCase().includes(input.toLowerCase()));
        typeEffect(newOutput, defaultPrefix + descriptions[selectedKey], 20);
    } else {
        typeEffect(newOutput, defaultPrefix + "Invalid selection. Please try again.", 20);
    }

    outputDiv.appendChild(newOutput);
    outputDiv.appendChild(document.createElement('br'));
    scrollToBottom();
}

// Check if input is a number or text from the selection list
function isNumberOrText(input) {
    const selectedNumber = parseInt(input);
    const descriptions = currentSelection === 'skills' ? skillDescriptions :
                         currentSelection === 'education' ? educationDescriptions :
                         currentSelection === 'experience' ? experienceDescriptions :
                         currentSelection === 'projects' ? projectDescriptions :
                         currentSelection === 'awards' ? awardsDescriptions :
                         currentSelection === 'language' ? languageDescriptions : null;

    return !isNaN(selectedNumber) || (descriptions && Object.values(descriptions).some(desc => desc.toLowerCase().includes(input.toLowerCase())));
}

// Display an invalid command message in selection mode
function displayInvalidCommand() {
    const newOutput = document.createElement('div');
    newOutput.classList.add('typing-effect');
    typeEffect(newOutput, "> Invalid input in selection mode. Please choose a number, a name, or type 'exit' to leave.", 20);
    outputDiv.appendChild(newOutput);
    outputDiv.appendChild(document.createElement('br'));
    scrollToBottom();
}

// Handle exit from selection mode
function handleExit() {
    const newOutput = document.createElement('div');
    newOutput.classList.add('typing-effect');
    typeEffect(newOutput, "> Exiting selection mode...\n", 20, function () {
        resetPrompt();  // Reset input span
        showHelp(newOutput);  // Automatically display help after exiting
    });
    outputDiv.appendChild(newOutput);
    outputDiv.appendChild(document.createElement('br'));
    scrollToBottom();
}

// Display help content
function showHelp(newOutput) {
    typeEffect(newOutput, "> Available commands: help, theme, about, projects, contact, skills, social, resume, education, experience, awards, language, cls", 20);
}

// Set the selection mode (skills, education, etc.)
function setSelectionMode(mode, descriptions) {
    currentSelection = mode;
    updatePrompt();  // Update prompt based on mode
}

// Reset prompt to root ($~/)
function resetPrompt() {
    currentSelection = '';  // Clear current selection mode
    updatePrompt();  // Reset to default root
}

// Update prompt text based on current selection mode
function updatePrompt() {
    if (currentSelection) {
        promptSpan.textContent = `$~/${currentSelection}/`;
    } else {
        promptSpan.textContent = `$~/`;
    }
}
