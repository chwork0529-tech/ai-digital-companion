from flask import Flask, request, jsonify
from flask_cors import CORS
import gpt_core

app = Flask(__name__)
CORS(app)

@app.route('/get_gpt_text', methods=['POST'])
def get_gpt_text():
    data = request.get_json()

    msg = data.get('msg')
    page_name = data.get('page_name', '基本資料')

    if not msg:
        return jsonify({
            'status': 'error',
            'message': 'Missing message'
        }), 400

    try:
        response = gpt_core.run(page_name, msg)
        return jsonify({'status': response})
    except Exception as e:
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001)