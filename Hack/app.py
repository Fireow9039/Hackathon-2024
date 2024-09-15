from flask import Flask, request, jsonify
import os
import genai

app = Flask(__name__)

@app.route('/generate_quiz', methods=['POST'])
def generate_quiz():
    video_id = request.form['video_id']
    
    # Create the model with the API key directly
    model = genai.GenerativeModel(model_id='gemini-1.5-flash', api_key=os.environ.get("GEMINI_API_KEY"))
    
    response = model.generate(
        prompt=f"Generate a quiz with 10 MCQs for video content with ID {video_id}",
        max_tokens=500
    )
    
    questions = response['choices'][0]['text']
    return jsonify(questions)

if __name__ == '__main__':
    app.run(debug=True)
