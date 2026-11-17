// ***** FOR MORE PROJECTS VISIT :- https://updategadh.com/ *****
// Developed By UPDATEGADH TEAM
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".primary-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            button.classList.add("pressed");
            setTimeout(function(){ button.classList.remove("pressed"); }, 180);
        });
    });
});