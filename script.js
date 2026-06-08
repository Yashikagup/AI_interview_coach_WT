let questions = [];
let index = 0;
let answers = [];

// ==============================
// INIT ON PAGE LOAD
// ==============================
window.onload = function () {

    const role = localStorage.getItem("role");

    if (!role) {
        alert("Role not found. Please go back and enter role first.");
        return;
    }

    fetch("process_resume.php", {
        method: "POST",
        body: new URLSearchParams({
            action: "generate_questions",
            role: role
        })
    })
    .then(res => res.json())
    .then(data => {

        console.log("API Response:", data);

        if (!data || !data.questions) {
            alert("No questions received from server");
            return;
        }

        // Clean and prepare questions
        questions = data.questions
            .split("\n")
            .map(q => q.replace(/^\d+[\.\)]\s*/, "").trim())
            .filter(q => q.length > 5);

        if (questions.length === 0) {
            alert("No valid questions generated");
            return;
        }

        // Limit to 5 questions
        questions = questions.slice(0, 5);

        index = 0;
        answers = [];

        showQuestion();
    })
    .catch(err => {
        console.error("ERROR loading questions:", err);
        alert("Failed to load questions. Check backend.");
    });
};

// ==============================
// SHOW QUESTION
// ==============================
function showQuestion() {

    if (!questions[index]) {
        generateReport();
        return;
    }

    const progressEl = document.getElementById("progress");
    const questionEl = document.getElementById("question");
    const answerEl = document.getElementById("answer");
    const feedbackEl = document.getElementById("feedback");

    if (progressEl)
        progressEl.innerText = `Question ${index + 1} of ${questions.length}`;

    if (questionEl)
        questionEl.innerText = questions[index];

    if (answerEl)
        answerEl.value = "";

    if (feedbackEl)
        feedbackEl.innerHTML = "";
}

// ==============================
// SUBMIT ANSWER
// ==============================
function submitAnswer() {

    const answerEl = document.getElementById("answer");

    if (!answerEl) return;

    const ans = answerEl.value.trim();

    if (!ans) {
        alert("Please write your answer!");
        return;
    }

    answers[index] = ans;

    fetch("process_resume.php", {
        method: "POST",
        body: new URLSearchParams({
            action: "get_feedback",
            question: questions[index],
            answer: ans
        })
    })
    .then(res => res.text())
    .then(data => {

        const feedbackEl = document.getElementById("feedback");

        if (feedbackEl) {
            feedbackEl.innerHTML =
                "<b>Feedback:</b><br>" + data.replace(/\n/g, "<br>");
        }
    })
    .catch(err => {
        console.error("Feedback error:", err);
        alert("Failed to get feedback");
    });
}

// ==============================
// NEXT QUESTION
// ==============================
function nextQuestion() {

    console.log("Next clicked. Current index:", index);

    if (!answers[index]) {
        alert("Please submit your answer first!");
        return;
    }

    index++;

    if (index >= questions.length) {
        generateReport();
        return;
    }

    showQuestion();
}

// ==============================
// GENERATE FINAL REPORT
// ==============================
function generateReport() {

    console.log("Generating final report...");

    fetch("process_resume.php", {
        method: "POST",
        body: new URLSearchParams({
            action: "generate_report",
            answers: JSON.stringify(answers)
        })
    })
    .then(res => res.text())
    .then(report => {

        const reportEl = document.getElementById("report");

        if (reportEl) {
            reportEl.innerHTML =
                "<h4>Final Report</h4><pre>" + report + "</pre>";
        }

        // Download report
        const blob = new Blob([report], { type: "text/plain" });
        const url = URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "Interview_Report.txt";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        URL.revokeObjectURL(url);
    })
    .catch(err => {
        console.error("Report error:", err);
        alert("Failed to generate report");
    });
}