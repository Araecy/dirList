import os
import json

# Path to the assignments directory
assignments_dir = os.path.abspath('./')

# Path to the descriptions JSON file
json_file_path = 'descriptions.json'

# Load existing descriptions
if os.path.exists(json_file_path):
    with open(json_file_path, 'r') as f:
        try:
            descriptions = json.load(f)
        except json.JSONDecodeError:
            print(f"Warning: {json_file_path} is empty or malformed. Initializing empty descriptions.")
            descriptions = {}
else:
    descriptions = {}

# Function to recursively scan directories and files
for root, dirs, files in os.walk(assignments_dir):
    for file in files:
        # Construct the relative path and normalize it
        file_path = os.path.relpath(os.path.join(root, file), start=assignments_dir)
        normalized_path = file_path.replace('/', '\\')  # Convert forward slashes to backslashes

        # Prepend .\ to the path
        if not normalized_path.startswith('.\\'):
            normalized_path = '.\\' + normalized_path

        # Check if the file already has a description
        if normalized_path not in descriptions:
            # If not, add it with a placeholder description
            descriptions[normalized_path] = f"Placeholder description for {normalized_path}."

# Save the updated descriptions back to the JSON file
with open(json_file_path, 'w') as f:
    json.dump(descriptions, f, indent=4)

print("Descriptions have been updated and saved.")
