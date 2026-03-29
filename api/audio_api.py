from flask import Flask, request, jsonify
import os
from gtts import gTTS

app = Flask(__name__)

@app.route('/get_audio', methods=['POST'])
def get_audio():
    data = request.get_json()

    text = data.get('text')
    filename = data.get('filename')

    if not text or not filename:
        return jsonify({
            'status': 'error',
            'message': 'Missing parameters'
        }), 400

    try:
        tts = gTTS(text=text, lang='zh-tw')
        tts.save(filename)

        return jsonify({'status': 'success'})
    except Exception as e:
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 500


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5002)