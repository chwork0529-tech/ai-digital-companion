const btn = document.querySelector('#btn');
const txt = document.querySelector('#txt');
const loadingAni = document.getElementById('loading');
let stop = 0;

document.addEventListener('DOMContentLoaded', () => {
    setupMediaSwitcher();
});

function showText(user, img, text) {
    const displayUser = user !== 'user1' ? 'user2' : 'user1';

    const chatHtml = `
        <div class="chat-user ${displayUser}">
            <div class="user-img">
                <img src="${img}" alt="user">
            </div>
            <div class="user-msg">
                <p class="typing"></p>
            </div>
        </div>
    `;

    const chats = document.querySelector('.chats');
    const chatPage = document.querySelector('.chat-page');

    if (!chats || !chatPage) return;

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = chatHtml;
    const newMessage = tempDiv.firstElementChild;

    chats.appendChild(newMessage);
    chatPage.scrollTop = chatPage.scrollHeight - chatPage.clientHeight;

    const typingElement = newMessage.querySelector('.typing');
    typeEffect(typingElement, text);
}

function typeEffect(element, text, delay = 50) {
    let i = 0;
    const interval = setInterval(() => {
        element.textContent += text.charAt(i);
        i++;

        if (i >= text.length) {
            clearInterval(interval);
            element.classList.remove('typing');
        }
    }, delay);
}

function save_text(text, familyPicture, user) {
    const formData = new FormData();
    formData.append('msg', text);
    formData.append('family_picture', familyPicture);
    formData.append('user', user);

    $.ajax({
        url: '../backend/upload_text.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            get_audio(text, familyPicture, user);
        },
        error: function () {
            console.log('Upload failed');
            showText(user, user === 'user2' ? './img/user2.jpg' : `./family/${familyPicture}`, '上傳錯誤請檢查內容');
        }
    });
}

function get_gpt_text(text, familyPicture) {
    const formData = new FormData();
    formData.append('msg', text);
    formData.append('family_picture', familyPicture);

    $.ajax({
        url: '../backend/get_gpt_text.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            save_text(response, familyPicture, 'user1');

            if (txt) {
                txt.value = '';
                txt.disabled = false;
                txt.focus();
            }

            showLoading();
        },
        error: function () {
            console.log('GPT request failed');
            showText('user1', `./family/${familyPicture}`, '系統回應失敗');
        }
    });
}

function get_audio(text, familyPicture, user) {
    const formData = new FormData();
    formData.append('msg', text);
    formData.append('family_picture', familyPicture);
    formData.append('user', user);

    $.ajax({
        url: '../backend/get_audio.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: async function (response) {
            if (user === 'user2') {
                showText(user, './img/user2.jpg', text);
                await playAudio(response);
            } else {
                const videoPath = document.getElementById('video-path')?.dataset.value || '';
                const audioPath = response;
                const img = `./family/${familyPicture}`;
                await get_video(videoPath, audioPath, text, user, img);
            }
        },
        error: function () {
            console.log('Audio request failed');
        }
    });
}

function playAudio(filepath) {
    return new Promise((resolve) => {
        const audio = new Audio(filepath);
        audio.play();

        audio.onended = function () {
            stop = 1;
            resolve();
        };
    });
}

function get_video(imagePath, audioPath, text, user, img) {
    const formData = new FormData();
    formData.append('imagepath', imagePath);
    formData.append('audiopath', audioPath);

    return new Promise((resolve, reject) => {
        $.ajax({
            url: '../backend/get_video.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: async function (response) {
                await waitForUser2Audio();
                hideLoading();
                loadAndPlayVideo(response);
                showText(user, img, text);
                resolve();
            },
            error: function (xhr, status, error) {
                console.log('Video request failed');
                reject(error);
            }
        });
    });
}

function waitForUser2Audio() {
    return new Promise((resolve) => {
        const interval = setInterval(() => {
            if (stop === 1) {
                clearInterval(interval);
                stop = 0;
                resolve();
            }
        }, 200);
    });
}

function showLoading() {
    if (loadingAni) {
        loadingAni.style.display = 'block';
    }
}

function hideLoading() {
    if (loadingAni) {
        loadingAni.style.display = 'none';
    }
}

function setupMediaSwitcher() {
    const videoElement = document.getElementById('userVideo');
    const imageElement = document.getElementById('userImage');

    if (!videoElement || !imageElement) return;

    window.loadAndPlayVideo = function (videoSrc) {
        videoElement.src = videoSrc;
        imageElement.style.display = 'none';
        videoElement.style.display = 'block';
        videoElement.load();
        videoElement.play();
    };

    videoElement.addEventListener('ended', () => {
        videoElement.style.display = 'none';
        imageElement.style.display = 'block';
    });
}

function sendMessage() {
    if (!txt) return;

    const value = txt.value.trim();
    if (value === '' || value === '上傳中~') return;

    txt.value = '上傳中~';
    txt.disabled = true;

    const imgPath = document.getElementById('img1-path')?.dataset.value || '';
    save_text(value, imgPath, 'user2');
    get_gpt_text(value, imgPath);
}

if (btn) {
    btn.addEventListener('click', sendMessage);
}

if (txt) {
    txt.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendMessage();
        }
    });
}