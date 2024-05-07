import os
import pathlib
import tempfile
from flask import Flask, flash, request, redirect, url_for, render_template
from flask import send_from_directory
from werkzeug.utils import secure_filename

app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = '/tmp/uploads'

path = pathlib.Path(app.config['UPLOAD_FOLDER'])
path.mkdir(parents=True, exist_ok=True)

def get_extension(filename):
    return filename[filename.find("."):]

def allowed_file(filename):
    return '.' in filename and \
        filename[-3:] == "pdf"

@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)

@app.route('/', methods=['GET', 'POST'])
def upload_file():
    if request.method == 'POST':

        if 'file' not in request.files:
            flash('No file part')
            return redirect(request.url)

        f = request.files['file']
        if f.filename == '':
            flash('No selected file')
            return redirect(request.url)

        if not allowed_file(f.filename):
            return render_template('exterror.html')

        extension = get_extension(f.filename)
        filename = next(tempfile._get_candidate_names()) + extension
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], filename)
        f.save(filepath)

        os.system(f'gpg --homedir /home/uwsgi --pinentry-mode loopback --symmetric --passphrase 1234 {filepath}')
        return redirect(url_for('uploaded_file', filename=f"{filename}.gpg"))

    return render_template('index.html')

if __name__ == '__main__':
    app.config.from_object(__name__)
    app.config['SECRET_KEY'] = 'c05ee470c7a5bc13b7d426032d5b6aadd27c76121e687469f3fd260ea00718cc'
    app.run(debug=False, host='0.0.0.0', port=7400)
