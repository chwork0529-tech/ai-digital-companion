import os
import json
import traceback
from openai import OpenAI

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PROMPT_PATH = os.path.join(BASE_DIR, 'prompts', 'prompt_sample.json')

def load_prompts(file_path):
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    except FileNotFoundError:
        print(f'File not found: {file_path}')
        raise
    except json.JSONDecodeError:
        print(f'Error decoding JSON from file: {file_path}')
        raise

prompts = load_prompts(PROMPT_PATH)
messages = []

client = OpenAI(api_key=os.getenv("OPENAI_API_KEY"))

def get_text(name, role):
    for item in prompts:
        if item["name"] == name:
            return item.get(role)
    return None

def get_completion(messages):
    try:
        chat_completion = client.chat.completions.create(
            model="gpt-4o-mini",
            messages=messages,
            max_tokens=100,
            temperature=0.7
        )
        answer = chat_completion.choices[0].message.content.replace('\n', '')
        return answer
    except Exception as e:
        print("Error occurred:", str(e))
        traceback.print_exc()
        return "Error: Unable to generate a response"

def run(page_name, msg):
    ai_msg = ''

    try:
        if msg:
            if not messages:
                user1 = get_text(page_name, 'user1')
                assistant1 = get_text(page_name, 'assistant1')
                user2 = get_text(page_name, 'user2')

                if user1 and assistant1 and user2:
                    messages.append({"role": "user", "content": user1})
                    messages.append({"role": "assistant", "content": assistant1})
                    messages.append({"role": "user", "content": user2})
                else:
                    raise ValueError(f"Prompt with name '{page_name}' not found.")

            messages.append({"role": "user", "content": msg})
            ai_msg = get_completion(messages)
            messages.append({"role": "assistant", "content": ai_msg})

    except Exception as e:
        print("Error occurred:", str(e))
        traceback.print_exc()

    return ai_msg