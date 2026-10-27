// =============================
//          ELEMENTS
// =============================
const authSlider = document.querySelector(".auth-slider");
const loginTab = document.querySelector(".login-choice");
const registerTab = document.querySelector(".register-choice");



// =============================
//          FUNCTIONS
// =============================
function choiceLogIn(){
    loginTab.classList.add("active");
    registerTab.classList.remove("active");

    authSlider.classList.remove("register-active");
}

function choiceSignUp(){
    registerTab.classList.add("active");
    loginTab.classList.remove("active");

    authSlider.classList.add("register-active");
}


// =============================
//          ELEMENTS
// =============================
loginTab.addEventListener("click", choiceLogIn);

registerTab.addEventListener("click", choiceSignUp);