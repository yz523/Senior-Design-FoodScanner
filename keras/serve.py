# -*- coding: utf-8 -*-
"""
Created on Mon May 21 15:45:19 2018

@author: lemur
"""
from keras.preprocessing.image_gen_extended import preprocess_input
from keras.models import load_model
from keras.preprocessing.image import img_to_array
from PIL import Image
import numpy as np
import flask
import io

# initialize our Flask application and the Keras model
app = flask.Flask(__name__)
model = None
CLASS_INDEX = {}
def decode_predictions(preds, top=5):                
    results = []
    for pred in preds:
        top_indices = pred.argsort()[-top:][::-1]
        result = [(i,CLASS_INDEX[i], pred[i],) for i in top_indices]
        result.sort(key=lambda x: x[2], reverse=True)
        results.append(result)
    return results
def load():
	# load the pre-trained Keras model (here we are using a model
	# pre-trained on ImageNet and provided by Keras, but you can
	# substitute in your own networks just as easily)
    global model
    model_path = r'xceptionEXT_resize_l2regulation0.0005_SGD_0.002_step_10+.01-0.5554.hdf5'
    model = load_model(model_path)
    class_path = r'classes.txt'
    f = open(class_path,"r")
    global CLASS_INDEX 
    for i, line in enumerate(f.readlines()):
        CLASS_INDEX[i] = line
    f.close()
    print('testing model:', model.predict(np.zeros((1, 299, 299, 3))))

def prepare_image(image, target):
	# if the image mode is not RGB, convert it
    if image.mode != "RGB":
        image = image.convert("RGB")

	# resize the input image and preprocess it
    image = image.resize(target)
    image = img_to_array(image)
	
    image = preprocess_input(image)
    image = np.expand_dims(image, axis=0)
	# return the processed image
    return image

@app.route("/predict", methods=["POST"])
def predict():
	# initialize the data dictionary that will be returned from the
	# view
	data = {"success": False}

	# ensure an image was properly uploaded to our endpoint
	if flask.request.method == "POST":
		if flask.request.files.get("image"):
			# read the image in PIL format
			image = flask.request.files["image"].read()
			image = Image.open(io.BytesIO(image))

			# preprocess the image and prepare it for classification
			image = prepare_image(image, target=(299, 299))

			# classify the input image and then initialize the list
			# of predictions to return to the client
			preds = model.predict(image)
			results = decode_predictions(preds)
			data["predictions"] = []

			# loop over the results and add them to the list of
			# returned predictions
			for (imagenetID, label, prob) in results[0]:
				r = {"label": label, "probability": float(prob)}
				data["predictions"].append(r)

			# indicate that the request was a success
			data["success"] = True

	# return the data dictionary as a JSON response
	return flask.jsonify(data)

# if this is the main thread of execution first load the model and
# then start the server
if __name__ == "__main__":
    
    print(("* Loading Keras model and Flask starting server..."
	"please wait until server has fully started"))
    load()
    app.run('0.0.0.0')