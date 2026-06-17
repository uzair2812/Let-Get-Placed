<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Resume Builder</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Modern Scrollbars for screen viewing */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        
        /* A4 Preview Screen Styling */
        .preview-page {
            width: 210mm;
            min-height: 297mm;
            background-color: #ffffff;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        /* Strict CSS Rules targeting print layout mechanics exclusively */
        @media print {
            /* Hide the whole workspace page layout entirely */
            body, html, header, aside, main, button {
                background: white !important;
                overflow: visible !important;
                height: auto !important;
            }
            header, aside, .no-print {
                display: none !important;
            }
            /* Clean canvas setup for isolated printing target rendering */
            main {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                display: block !important;
                overflow: visible !important;
            }
            .preview-page {
                border: none !important;
                box-shadow: none !important;
                padding: 0mm !important;
                margin: 0 !important;
                width: 100% !important;
                min-height: auto !important;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center z-10 shrink-0">
        <h1 class="text-xl font-bold text-slate-800">Dynamic Resume Builder</h1>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg shadow-sm transition flex items-center gap-2 cursor-pointer no-print">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 1.258a1.5 1.5 0 01-1.473 1.772H7.584a1.5 1.5 0 01-1.472-1.772L6.34 18m11.318 0a1.125 1.125 0 001.125-1.125V11.25c0-.621-.504-1.125-1.125-1.125H5.062c-.621 0-1.125.504-1.125 1.125v5.625c0 .621.504 1.125 1.125 1.125m13.875-5.625A2.25 2.25 0 0016.5 4.5h-9A2.25 2.25 0 005.25 6.75V11.25" />
            </svg>
            Print / Save as PDF
        </button>
    </header>

    <div class="flex flex-1 overflow-hidden">
        
        <aside class="w-1/3 bg-white border-r border-slate-200 p-6 overflow-y-auto no-print">
            <form id="resumeForm" oninput="updateTemplate()" class="space-y-6">
                
                <div class="space-y-4">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Personal Details</label>
                    <div class="space-y-3">
                        <input type="text" id="in-name" value="Harshibar" placeholder="Full Name" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:border-blue-500">
                        <input type="text" id="in-phone" value="555.555.5555" placeholder="Phone" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:border-blue-500">
                        <input type="email" id="in-email" value="hello@email.com" placeholder="Email" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:border-blue-500">
                        <input type="text" id="in-link" value="harshibar" placeholder="Profile Link" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:border-blue-500">
                        <input type="text" id="in-citizenship" value="U.S. Citizen" placeholder="Location/Status" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <hr class="border-slate-200">

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Professional Summary</label>
                        <div class="flex items-center gap-1.5">
                            <input type="checkbox" id="chk-summary" checked onchange="updateTemplate()" class="w-3.5 h-3.5 text-blue-600 rounded border-slate-300">
                            <span class="text-xs text-slate-500 font-medium">Show in Preview</span>
                        </div>
                    </div>
                    <textarea id="in-summary" rows="4" placeholder="Brief summary of your professional background, goals, or studies..." class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:border-blue-500">Information Science and Engineering student at UVCE with practical full-stack development experience. Passionate about applying machine learning techniques to real-world domain ecosystems, specifically designing NLP-focused agricultural pipeline applications. Proven record building modular, scalable management software dashboards using modern web architectures.</textarea>
                </div>

                <hr class="border-slate-200">

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Experience</label>
                        <button type="button" onclick="addExperience()" class="text-xs bg-slate-800 hover:bg-slate-700 text-white font-medium px-2.5 py-1 rounded shadow-sm cursor-pointer">+ Add Experience</button>
                    </div>
                    <div id="experience-input-container" class="space-y-4"></div>
                </div>

                <hr class="border-slate-200">

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Projects</label>
                        <button type="button" onclick="addProject()" class="text-xs bg-slate-800 hover:bg-slate-700 text-white font-medium px-2.5 py-1 rounded shadow-sm cursor-pointer">+ Add Project</button>
                    </div>
                    <div id="projects-input-container" class="space-y-4"></div>
                </div>

                <hr class="border-slate-200">

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Education</label>
                        <button type="button" onclick="addEducation()" class="text-xs bg-slate-800 hover:bg-slate-700 text-white font-medium px-2.5 py-1 rounded shadow-sm cursor-pointer">+ Add Education</button>
                    </div>
                    <div id="education-input-container" class="space-y-4"></div>
                </div>

                <hr class="border-slate-200">

                <div class="space-y-4">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Skills</label>
                    <div class="space-y-3">
                        <input type="text" id="in-skills-lang" value="Python, JavaScript (React.js), HTML/CSS, SQL (PostgreSQL, MySQL), PHP" placeholder="Languages" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:border-blue-500">
                        <input type="text" id="in-skills-tools" value="Figma, Notion, Jira, Trello, Miro, Google Analytics, GitHub" placeholder="Tools" class="w-full px-3 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:border-blue-500">
                    </div>
                </div>

            </form>
        </aside>

        <main class="w-2/3 bg-slate-200 p-8 overflow-y-auto flex justify-center">
            
            <div id="resume-template" class="preview-page p-12 text-xs flex flex-col justify-between">
                <div>
                    <div class="text-center mb-5">
                        <h1 id="out-name" class="text-3xl tracking-wide text-neutral-900 mb-1.5 font-bold" style="font-family: 'Inter', sans-serif;">Harshibar</h1>
                        <div class="flex justify-center items-center gap-2 text-neutral-600 text-[11px]">
                            <span id="out-phone"></span> &bull;
                            <span id="out-email"></span> &bull;
                            <span id="out-link"></span> &bull;
                            <span id="out-citizenship"></span>
                        </div>
                    </div>

                    <div id="section-out-summary" class="mb-5 hidden">
                        <h2 class="text-xs font-bold text-neutral-900 border-b border-neutral-400 uppercase tracking-wider pb-0.5 mb-2" style="font-family: 'Inter', sans-serif;">Professional Profile</h2>
                        <p id="out-summary" class="text-neutral-700 leading-relaxed text-[11px] text-justify"></p>
                    </div>

                    <div id="section-out-experience" class="mb-5 hidden">
                        <h2 class="text-xs font-bold text-neutral-900 border-b border-neutral-400 uppercase tracking-wider pb-0.5 mb-2" style="font-family: 'Inter', sans-serif;">Experience</h2>
                        <div id="template-experience-container" class="space-y-3.5"></div>
                    </div>

                    <div id="section-out-projects" class="mb-5 hidden">
                        <h2 class="text-xs font-bold text-neutral-900 border-b border-neutral-400 uppercase tracking-wider pb-0.5 mb-2" style="font-family: 'Inter', sans-serif;">Projects</h2>
                        <div id="template-projects-container" class="space-y-3.5"></div>
                    </div>

                    <div id="section-out-education" class="mb-5 hidden">
                        <h2 class="text-xs font-bold text-neutral-900 border-b border-neutral-400 uppercase tracking-wider pb-0.5 mb-2" style="font-family: 'Inter', sans-serif;">Education</h2>
                        <div id="template-education-container" class="space-y-3.5"></div>
                    </div>

                    <div id="section-out-skills" class="mb-5">
                        <h2 class="text-xs font-bold text-neutral-900 border-b border-neutral-400 uppercase tracking-wider pb-0.5 mb-2" style="font-family: 'Inter', sans-serif;">Skills</h2>
                        <div class="space-y-1 text-[11px]">
                            <p><strong class="font-bold">Languages :</strong> <span id="out-skills-lang"></span></p>
                            <p><strong class="font-bold">Tools :</strong> <span id="out-skills-tools"></span></p>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        let expCount = 0, projCount = 0, eduCount = 0;

        function createBlockElement(htmlString) {
            const div = document.createElement('div');
            div.innerHTML = htmlString.trim();
            return div.firstChild;
        }

        function addExperience() {
            expCount++;
            const id = expCount;
            const html = `
                <div id="exp-group-${id}" class="bg-slate-50 p-3 rounded-lg border border-slate-200 space-y-2 relative">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="chk-exp-${id}" checked onchange="updateTemplate()" class="w-3.5 h-3.5 text-blue-600 rounded border-slate-300">
                            <span class="text-xs font-semibold text-slate-500">Show in Preview</span>
                        </div>
                        <button type="button" onclick="removeBlock('exp-group-${id}')" class="text-xs text-red-500 hover:text-red-700 cursor-pointer">Delete</button>
                    </div>
                    <input type="text" id="in-exp-comp-${id}" placeholder="Company" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                    <input type="text" id="in-exp-role-${id}" placeholder="Role" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" id="in-exp-date-${id}" placeholder="Dates" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                        <input type="text" id="in-exp-loc-${id}" placeholder="Location" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                    </div>
                    <textarea id="in-exp-desc-${id}" rows="3" placeholder="Bullet points (One per line)" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500"></textarea>
                </div>
            `;
            document.getElementById('experience-input-container').appendChild(createBlockElement(html));
            updateTemplate();
        }

        function addProject() {
            projCount++;
            const id = projCount;
            const html = `
                <div id="proj-group-${id}" class="bg-slate-50 p-3 rounded-lg border border-slate-200 space-y-2 relative">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="chk-proj-${id}" checked onchange="updateTemplate()" class="w-3.5 h-3.5 text-blue-600 rounded border-slate-300">
                            <span class="text-xs font-semibold text-slate-500">Show in Preview</span>
                        </div>
                        <button type="button" onclick="removeBlock('proj-group-${id}')" class="text-xs text-red-500 hover:text-red-700 cursor-pointer">Delete</button>
                    </div>
                    <input type="text" id="in-proj-title-${id}" placeholder="Project Name" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                    <textarea id="in-proj-desc-${id}" rows="2" placeholder="Project details (One per line)" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500"></textarea>
                </div>
            `;
            document.getElementById('projects-input-container').appendChild(createBlockElement(html));
            updateTemplate();
        }

        function addEducation() {
            eduCount++;
            const id = eduCount;
            const html = `
                <div id="edu-group-${id}" class="bg-slate-50 p-3 rounded-lg border border-slate-200 space-y-2 relative">
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="chk-edu-${id}" checked onchange="updateTemplate()" class="w-3.5 h-3.5 text-blue-600 rounded border-slate-300">
                            <span class="text-xs font-semibold text-slate-500">Show in Preview</span>
                        </div>
                        <button type="button" onclick="removeBlock('edu-group-${id}')" class="text-xs text-red-500 hover:text-red-700 cursor-pointer">Delete</button>
                    </div>
                    <input type="text" id="in-edu-school-${id}" placeholder="Institution" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                    <input type="text" id="in-edu-degree-${id}" placeholder="Degree" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" id="in-edu-date-${id}" placeholder="Dates" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                        <input type="text" id="in-edu-loc-${id}" placeholder="Location" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                    </div>
                    <input type="text" id="in-edu-course-${id}" placeholder="Coursework summary info" class="w-full px-2 py-1 border border-slate-300 rounded text-xs focus:outline-none focus:border-blue-500">
                </div>
            `;
            document.getElementById('education-input-container').appendChild(createBlockElement(html));
            updateTemplate();
        }

        function removeBlock(groupId) {
            document.getElementById(groupId).remove();
            updateTemplate();
        }

        function makeBulletList(text) {
            if (!text.trim()) return '';
            return text.split('\n')
                       .filter(line => line.trim() !== '')
                       .map(line => `<li style="list-style-type: disc; margin-left: 16px; margin-top: 2px; color: #334155;">${line.replace(/^\s*•\s*/, '')}</li>`)
                       .join('');
        }

        // Live Recompilation Script Logic Setup 
        function updateTemplate() {
            document.getElementById('out-name').innerText = document.getElementById('in-name').value || 'Your Name';
            document.getElementById('out-phone').innerText = document.getElementById('in-phone').value;
            document.getElementById('out-email').innerText = document.getElementById('in-email').value;
            document.getElementById('out-link').innerText = document.getElementById('in-link').value;
            document.getElementById('out-citizenship').innerText = document.getElementById('in-citizenship').value;
            
            document.getElementById('out-skills-lang').innerText = document.getElementById('in-skills-lang').value;
            document.getElementById('out-skills-tools').innerText = document.getElementById('in-skills-tools').value;

            // Summary Control Parsing
            const showSummary = document.getElementById('chk-summary').checked;
            const summaryText = document.getElementById('in-summary').value;
            if (showSummary && summaryText.trim() !== '') {
                document.getElementById('out-summary').innerText = summaryText;
                document.getElementById('section-out-summary').style.display = 'block';
            } else {
                document.getElementById('section-out-summary').style.display = 'none';
            }

            // Experience Rendering Loop
            const expContainer = document.getElementById('template-experience-container');
            expContainer.innerHTML = '';
            let activeExp = 0;
            for (let i = 1; i <= expCount; i++) {
                if (document.getElementById(`exp-group-${i}`) && document.getElementById(`chk-exp-${i}`).checked) {
                    activeExp++;
                    const comp = document.getElementById(`in-exp-comp-${i}`).value;
                    const role = document.getElementById(`in-exp-role-${i}`).value;
                    const date = document.getElementById(`in-exp-date-${i}`).value;
                    const loc  = document.getElementById(`in-exp-loc-${i}`).value;
                    const desc = document.getElementById(`in-exp-desc-${i}`).value;

                    expContainer.innerHTML += `
                        <div style="font-size: 11px; line-height: 1.4;">
                            <div style="display: flex; justify-content: space-between; font-weight: bold; color: #000000;">
                                <div>${comp}${role ? ' <span style="font-weight: normal; font-style: italic; color: #334155;">&mdash; ' + role + '</span>' : ''}</div>
                                <div style="font-weight: normal; color: #64748b;">${date}</div>
                            </div>
                            ${loc ? `<div style="color: #64748b; font-style: italic; font-size: 10px; margin-top: -1px;">${loc}</div>` : ''}
                            <ul style="margin-top: 3px; padding-left: 0;">${makeBulletList(desc)}</ul>
                        </div>
                    `;
                }
            }
            document.getElementById('section-out-experience').style.display = activeExp > 0 ? 'block' : 'none';

            // Projects Rendering Loop
            const projContainer = document.getElementById('template-projects-container');
            projContainer.innerHTML = '';
            let activeProj = 0;
            for (let i = 1; i <= projCount; i++) {
                if (document.getElementById(`proj-group-${i}`) && document.getElementById(`chk-proj-${i}`).checked) {
                    activeProj++;
                    const title = document.getElementById(`in-proj-title-${i}`).value;
                    const desc = document.getElementById(`in-proj-desc-${i}`).value;

                    projContainer.innerHTML += `
                        <div style="font-size: 11px; line-height: 1.4;">
                            <div style="font-weight: bold; color: #000000;">${title}</div>
                            <ul style="margin-top: 2px; padding-left: 0;">${makeBulletList(desc)}</ul>
                        </div>
                    `;
                }
            }
            document.getElementById('section-out-projects').style.display = activeProj > 0 ? 'block' : 'none';

            // Education Rendering Loop
            const eduContainer = document.getElementById('template-education-container');
            eduContainer.innerHTML = '';
            let activeEdu = 0;
            for (let i = 1; i <= eduCount; i++) {
                if (document.getElementById(`edu-group-${i}`) && document.getElementById(`chk-edu-${i}`).checked) {
                    activeEdu++;
                    const school = document.getElementById(`in-edu-school-${i}`).value;
                    const degree = document.getElementById(`in-edu-degree-${i}`).value;
                    const date = document.getElementById(`in-edu-date-${i}`).value;
                    const loc = document.getElementById(`in-edu-loc-${i}`).value;
                    const course = document.getElementById(`in-edu-course-${i}`).value;

                    eduContainer.innerHTML += `
                        <div style="font-size: 11px; line-height: 1.4;">
                            <div style="display: flex; justify-content: space-between; font-weight: bold; color: #000000;">
                                <div>${school}</div>
                                <div style="font-weight: normal; color: #64748b;">${date}</div>
                            </div>
                            <div style="display: flex; justify-content: space-between; color: #475569; font-style: italic; font-size: 10.5px; margin-top: 1px;">
                                <div>${degree}</div>
                                <div>${loc}</div>
                            </div>
                            ${course ? `<p style="color: #334155; margin-top: 3px; padding-left: 4px;"><strong style="font-weight: 600;">Coursework:</strong> ${course}</p>` : ''}
                        </div>
                    `;
                }
            }
            document.getElementById('section-out-education').style.display = activeEdu > 0 ? 'block' : 'none';
        }

        // Initialize Seeding Core Dataset Elements on Load Loops
        window.onload = function() {
            addExperience();
            document.getElementById('in-exp-comp-1').value = 'YouTube';
            document.getElementById('in-exp-role-1').value = 'Creator (@harshibar)';
            document.getElementById('in-exp-date-1').value = 'Aug. 2019 – Present';
            document.getElementById('in-exp-loc-1').value = 'San Francisco, CA';
            document.getElementById('in-exp-desc-1').value = "Grew channel to 60k subscribers in 1.5 years; created 80+ videos on tech and productivity\nConducted A/B testing on titles and thumbnails; increased video impressions by 2.5M in 3 months\nDesigned a Notion workflow to streamline video production; boosted productivity by 20%";
            
            addExperience();
            document.getElementById('in-exp-comp-2').value = 'Google Verily';
            document.getElementById('in-exp-role-2').value = 'Software Engineer';
            document.getElementById('in-exp-date-2').value = 'Aug. 2018 – Sept. 2019';
            document.getElementById('in-exp-loc-2').value = 'San Francisco, CA';
            document.getElementById('in-exp-desc-2').value = "Led front-end development of a dashboard to process 50k blood samples and detect early-stage cancer\nRebuilt a Quality Control product with input from 20 cross-functional stakeholders, saving $1M annually";

            addProject();
            document.getElementById('in-proj-title-1').value = 'Minimal Icon Pack';
            document.getElementById('in-proj-desc-1').value = "Designed and released 100+ minimal iOS and Android icons from scratch using Procreate and Figma\nMarketed the product and design process on YouTube; accumulated over $250 in sales on Gumroad";

            addEducation();
            document.getElementById('in-edu-school-1').value = 'Wellesley College';
            document.getElementById('in-edu-degree-1').value = 'Bachelor of Arts in Computer Science and Pre-Med';
            document.getElementById('in-edu-date-1').value = 'Aug. 2014 – May 2018';
            document.getElementById('in-edu-loc-1').value = 'Wellesley, MA';
            document.getElementById('in-edu-course-1').value = 'Data Structures, Algorithms, Databases, Computer Systems, Machine Learning';

            updateTemplate();
        };
    </script>
</body>
</html>