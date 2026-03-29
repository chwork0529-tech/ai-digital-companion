from flask import Flask, request, jsonify
import os
import demo

app = Flask(__name__)

# 專案根目錄（api/ 的上一層）
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# frontend 資料夾
FRONTEND_DIR = os.path.join(BASE_DIR, "frontend")

def run(video_file, output_file):
    config = os.path.join(BASE_DIR, "api", "config", "vox-256.yaml")
    checkpoint = os.path.join(BASE_DIR, "api", "checkpoints", "vox.pth.tar")

    driving_video = os.path.join(FRONTEND_DIR, "source", "driving.mp4")

    demo.main(
        config,
        checkpoint,
        video_file,
        driving_video,
        output_file,
        "256,256",
        "relative",
        False,
        False,
        False
    )

@app.route('/get_video', methods=['POST'])
def get_video():
    data = request.get_json()

    video_file = data.get('video_file')
    output_file = data.get('output_file')

    if not video_file or not output_file:
        return jsonify({
            'status': 'error',
            'message': 'Missing parameters'
        }), 400

    try:
        run(video_file, output_file)
        return jsonify({'status': 'success'})
    except Exception as e:
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 500


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5003)