import sys
import json
from genai import GenerativeModel, configure
import os

# Ensure the GEMINI_API_KEY is set correctly in your environment
configure(api_key=os.environ.get("GEMINI_API_KEY"))

# Use the Gemini model
model = GenerativeModel(model="gemini-1.5-flash")

def generate_mcq(video_id):
    # Example prompt for generating a quiz based on video content
    prompt = f"Generate 10 multiple-choice questions (MCQ) from the content of video with ID: {video_id}. " \
             "Each question should have 4 options (a, b, c, d) and indicate the correct option."

    # Use Gemini AI to generate the content
    response = model.generate(prompt)

    # Parse the response and extract questions
    questions = []
    for i, question_text in enumerate(response.results):
        question_data = {
            'question': f"Question {i + 1}: " + question_text['text'],  # assuming the model gives text output
            'options': {
                'a': "Option A",
                'b': "Option B",
                'c': "Option C",
                'd': "Option D"
            },
            'correct_option': "a"  # Assuming correct option is marked in response (you'll need to adjust this)
        }
        questions.append(question_data)

    return questions

if __name__ == "__main__":
    # The first argument is the video_id (passed from PHP)
    video_id = sys.argv[1]
    
    # Generate the quiz questions based on the video ID
    quiz_questions = generate_mcq(video_id)
    
    # Output the quiz questions as a JSON object
    print(json.dumps(quiz_questions))
