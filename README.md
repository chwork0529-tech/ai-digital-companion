# AI Digital Companion System

A web-based AI digital companion system that integrates conversational AI, voice interaction, and animation features.

This project demonstrates full-stack integration across frontend (PHP), backend (PHP + MySQL), and API services (Flask + AI models).

---

## Features

- AI conversation using OpenAI GPT API  
- Prompt-based virtual AI assistant (structured dialogue flow)  
- Voice interaction (speech-to-text & text-to-speech)  
- Audio generation using gTTS  
- Lip-sync animation (via external deep learning model)  
- User authentication system (login / register / session)  
- Chat history storage with database  

---

## System Architecture

Frontend (PHP + JS)  
→ Backend (PHP)  
→ API Layer (Flask)  
→ External Services (OpenAI, TTS, Animation Model)

---

## Project Structure

```bash
.
├── api/
│   ├── gpt_api.py
│   ├── gpt_core.py
│   ├── audio_api.py
│   └── video_api.py
│
├── backend/
│   ├── db.php
│   ├── function.php
│   ├── login.php
│   ├── logout.php
│   ├── register.php
│   ├── save_audio.php
│   ├── upload_text.php
│   ├── get_gpt_text.php
│   ├── get_audio.php
│   └── get_video.php
│
├── frontend/
│   ├── ai_chat.php
│   ├── login_page.php
│   ├── register_page.php
│   ├── css/
│   └── js/
│
├── prompts/
│   └── prompt_sample.json
│
├── requirements.txt
└── README.md

---

## API Services

| Service | Port | Description |
|--------|------|------------|
| GPT API | 5001 | Handles AI conversation |
| Audio API | 5002 | Converts text to speech |
| Video API | 5003 | Generates animation video |

---

## Installation

### 1. Install dependencies

```bash
pip install -r requirements.txt

### Set API Key
set OPENAI_API_KEY=your_api_key

### Run API
python api/gpt_api.py
python api/audio_api.py
python api/video_api.py

### Setup Database
# Create database:
digital_twin

# Edit:
backend/db.php

### Run System
http://localhost/frontend/login_page.php

# Important Notes
# External animation model is not included
# Model weights are omitted to keep repository lightweight
# Author

# This project was developed as a team project.

# My role involved backend development and system integration.

# My contributions include:

# Assisting in backend API development (text, audio, video)
# Integrating GPT API, TTS, and animation APIs
# Handling data flow between frontend, backend, and APIs
# Supporting authentication (login, register, session)
# Participating in testing and debugging
# Technologies Used
# PHP, MySQL
# JavaScript
# Flask (Python)
# OpenAI API
# gTTS

### Demo
https://github.com/your-repo/assets/xxxx