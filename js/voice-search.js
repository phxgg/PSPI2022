var voice = {
  // (A) INIT SPEECH RECOGNITION
  searchForm: null, // HTML SEARCH FORM
  searchBox: null, // HTML SEARCH FIELD
  micButton: null, // HTML VOICE SEARCH BUTTON
  recog: null, // SPEECH RECOGNITION OBJECT

  init: function () {
    // (A1) GET HTML ELEMENTS
    voice.searchForm = document.getElementById('search-form');
    voice.searchBox = document.getElementById('search-bar');
    voice.micButton = document.getElementById('search-voice');

    // (A2) GET MICROPHONE ACCESS
    navigator.mediaDevices.getUserMedia({ audio: true })
      .then((stream) => {
        // (A3) SPEECH RECOGNITION OBJECT + SETTINGS
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        voice.recog = new SpeechRecognition();
        voice.recog.lang = 'el-GR'; // en-US
        voice.recog.continuous = false;
        voice.recog.interimResults = false;

        // (A4) POPUPLATE SEARCH FIELD ON SPEECH RECOGNITION
        voice.recog.onresult = (evt) => {
          let said = evt.results[0][0].transcript.toLowerCase();
          voice.searchBox.value = said;
          // voice.searchForm.submit();
          // OR RUN AN AJAX/FETCH SEARCH
          voice.stop();
        };

        // (A5) ON SPEECH RECOGNITION ERROR
        voice.recog.onerror = (err) => { console.error(err); };

        // (A6) READY!
        voice.micButton.disabled = false;
        voice.stop();
      })
      .catch((err) => {
        console.error(err);
        var micWrapper = document.getElementById('mic-wrapper');

        voice.micButton.classList.add('disabled');
        voice.micButton.classList.add('text-danger');
        micWrapper.setAttribute('data-bs-toggle', 'tooltip');
        micWrapper.setAttribute('data-bs-placement', 'right');
        micWrapper.setAttribute('title', 'Please enable access to microphone.');

        var tooltip = new bootstrap.Tooltip(micWrapper);
      });
  },

  // (B) START SPEECH RECOGNITION
  start: () => {
    voice.recog.start();
    voice.micButton.onclick = voice.stop;

    voice.micButton.classList.add('text-danger');
    voice.micButton.innerHTML = '<i class="bi bi-mic-mute-fill"></i>';

    // console.log('Speak now or click again to cancel.');
  },

  // (C) STOP/CANCEL SPEECH RECOGNITION
  stop: () => {
    voice.recog.stop();
    voice.micButton.onclick = voice.start;

    voice.micButton.classList.remove('text-danger');

    voice.micButton.innerHTML = '<i class="bi bi-mic-fill"></i>';

    // voice.micButton.value = 'Press to speak';
  }
};

window.addEventListener('DOMContentLoaded', voice.init);
