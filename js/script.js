const chatButton = document.getElementById('chatButton');
const chatBox = document.getElementById('chatBox');
const chatClose = document.getElementById('chatClose');
const chatSend = document.getElementById('chatSend');
const chatInput = document.getElementById('chatInput');
const chatMessages = document.getElementById('chatMessages');

const signinModal = document.getElementById('signinModal');
const signupModal = document.getElementById('signupModal');
const signinBtn = document.getElementById('signinBtn');
const signupBtn = document.getElementById('signupBtn');
const heroSigninBtn = document.getElementById('heroSigninBtn');
const closeModals = document.querySelectorAll('.modal-close');

const signinForm = document.getElementById('signinForm');
const signupForm = document.getElementById('signupForm');
const signinAlert = document.getElementById('signinAlert');
const signupAlert = document.getElementById('signupAlert');

chatButton.addEventListener('click', () => {
    chatBox.classList.add('active');
});

chatClose.addEventListener('click', () => {
    chatBox.classList.remove('active');
});

chatSend.addEventListener('click', sendMessage);
chatInput.addEventListener('keypress', (e) => {
    if(e.key === 'Enter') {
        sendMessage();
    }
});

function sendMessage() {
    const message = chatInput.value.trim();
    if(message) {
        const messageDiv = document.createElement('div');
        messageDiv.style.marginBottom = '10px';
        messageDiv.style.padding = '10px';
        messageDiv.style.backgroundColor = '#f0f0f0';
        messageDiv.style.borderRadius = '5px';
        messageDiv.textContent = message;
        chatMessages.appendChild(messageDiv);
        chatInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        setTimeout(() => {
            const replyDiv = document.createElement('div');
            replyDiv.style.marginBottom = '10px';
            replyDiv.style.padding = '10px';
            replyDiv.style.backgroundColor = '#7cb342';
            replyDiv.style.color = 'white';
            replyDiv.style.borderRadius = '5px';
            replyDiv.textContent = 'Thank you for your message! We will get back to you soon.';
            chatMessages.appendChild(replyDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }, 1000);
    }
}

signinBtn.addEventListener('click', () => {
    signinModal.classList.add('active');
});

signupBtn.addEventListener('click', () => {
    signupModal.classList.add('active');
});

heroSigninBtn.addEventListener('click', () => {
    signinModal.classList.add('active');
});

closeModals.forEach(close => {
    close.addEventListener('click', () => {
        signinModal.classList.remove('active');
        signupModal.classList.remove('active');
    });
});

window.addEventListener('click', (e) => {
    if(e.target === signinModal) {
        signinModal.classList.remove('active');
    }
    if(e.target === signupModal) {
        signupModal.classList.remove('active');
    }
});

signinForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(signinForm);
    
    try {
        const response = await fetch('auth/login.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if(data.success) {
            showAlert(signinAlert, data.message, 'success');
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 1500);
        } else {
            showAlert(signinAlert, data.message, 'error');
        }
    } catch(error) {
        showAlert(signinAlert, 'An error occurred. Please try again.', 'error');
    }
});

signupForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(signupForm);
    
    try {
        const response = await fetch('auth/register.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if(data.success) {
            showAlert(signupAlert, data.message, 'success');
            setTimeout(() => {
                signupModal.classList.remove('active');
                signinModal.classList.add('active');
                signupForm.reset();
            }, 1500);
        } else {
            showAlert(signupAlert, data.message, 'error');
        }
    } catch(error) {
        showAlert(signupAlert, 'An error occurred. Please try again.', 'error');
    }
});

function showAlert(alertElement, message, type) {
    alertElement.textContent = message;
    alertElement.className = 'alert active alert-' + type;
    
    setTimeout(() => {
        alertElement.classList.remove('active');
    }, 5000);
}

function switchToSignup() {
    signinModal.classList.remove('active');
    signupModal.classList.add('active');
}

function switchToSignin() {
    signupModal.classList.remove('active');
    signinModal.classList.add('active');
}