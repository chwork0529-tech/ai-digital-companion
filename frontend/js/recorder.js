(function (global) {
    let isRecording = false;
    let mediaRecorder = null;
    let audioChunks = [];
    let recordButton = null;
    let transcript = '';
    let recognition = null;

    function updateButtonState() {
        if (!recordButton) return;
        recordButton.textContent = isRecording ? '結束發話' : '按下發話';
    }

    function saveAudio(audioBlob, filename) {
        const formData = new FormData();
        formData.append('audio', audioBlob, filename);

        return fetch('../backend/save_audio.php', {
            method: 'POST',
            body: formData
        });
    }

    function startRecording() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                audioChunks = [];
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.start();

                mediaRecorder.addEventListener('dataavailable', event => {
                    audioChunks.push(event.data);
                });

                mediaRecorder.addEventListener('stop', () => {
                    const user = document.getElementById('user')?.dataset.value || 'user';
                    const safeTranscript = transcript || 'voice';
                    const fileName = `${user}_${safeTranscript}.wav`;
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });

                    saveAudio(audioBlob, fileName)
                        .then(response => response.text())
                        .then(data => {
                            console.log('Audio saved:', data);
                        })
                        .catch(error => {
                            console.error('Audio save failed:', error);
                        });

                    audioChunks = [];
                });

                isRecording = true;
                updateButtonState();

                if (recognition) {
                    recognition.start();
                }
            })
            .catch(error => {
                console.error('Microphone access failed:', error);
            });
    }

    function stopRecording() {
        if (mediaRecorder && isRecording) {
            mediaRecorder.stop();
            mediaRecorder.stream.getTracks().forEach(track => track.stop());
            isRecording = false;
            updateButtonState();
        }

        if (recognition) {
            recognition.stop();
        }
    }

    function toggleRecording() {
        if (isRecording) {
            stopRecording();
        } else {
            startRecording();
        }
    }

    function initSpeechRecognition() {
        if (!('webkitSpeechRecognition' in window)) {
            alert('您的瀏覽器不支援語音識別，請使用 Chrome。');
            return;
        }

        recognition = new webkitSpeechRecognition();
        recognition.lang = 'zh-TW';

        recognition.onresult = function (event) {
            transcript = event.results[0][0].transcript;

            const familyPicture = document.getElementById('img1-path')?.dataset.value || '';
            const currentUser = document.getElementById('user')?.dataset.value || 'user2';

            if (typeof save_text === 'function') {
                save_text(transcript, familyPicture, currentUser);
            }

            if (typeof get_gpt_text === 'function') {
                get_gpt_text(transcript, familyPicture);
            }

            stopRecording();
        };
    }

    function init(buttonId) {
        recordButton = document.getElementById(buttonId);

        if (!recordButton) {
            console.error('Record button not found');
            return;
        }

        recordButton.addEventListener('click', toggleRecording);
        initSpeechRecognition();
        updateButtonState();
    }

    global.recorder = {
        init,
        start: startRecording,
        stop: stopRecording,
        toggle: toggleRecording
    };
})(window);