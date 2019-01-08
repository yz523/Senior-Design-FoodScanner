# -*- coding: utf-8 -*-
"""
Created on Mon Apr 30 13:56:34 2018

@author: lemur
"""
from keras_squeezenet import SqueezeNet
from keras.models import Model
from keras.layers import Conv2D, GlobalAveragePooling2D,Dropout,Activation
from keras.preprocessing.image import ImageDataGenerator
from keras.optimizers import SGD, RMSprop
from keras.callbacks import EarlyStopping                 
from keras.applications.imagenet_utils import preprocess_input
CLASS_INDEX = None

img_width,img_height = 227,227                                      
train_data_dir = r'dataset\food-101\train'
validation_data_dir = r'dataset\food-101\test'
epochs = 50
my_batch_size = 32

train_datagen = ImageDataGenerator(
    rescale = 1/127.5,
    samplewise_center= True,                                #image augmentation
    shear_range=0.2,    
    width_shift_range=0.2,  
    height_shift_range=0.2,
    horizontal_flip=True,
    #vertical_flip=True,
    zoom_range=[.8, 1],
    channel_shift_range=30,
    fill_mode='reflect')
test_datagen = ImageDataGenerator(rescale = 1/127.5,samplewise_center= True)                   



train_generator = train_datagen.flow_from_directory(                 
    train_data_dir,
    target_size=(img_width, img_height),
    batch_size=my_batch_size,)

validation_generator = test_datagen.flow_from_directory(
    validation_data_dir,
    target_size=(img_width, img_height),
    batch_size=my_batch_size,
)
        

nb_train_samples = len(train_generator.filenames)
nb_validation_samples = len(validation_generator.filenames)

def decode_predictions(preds, top=5):
   
    global CLASS_INDEX
    if CLASS_INDEX is None:
        CLASS_INDEX = validation_generator.class_indices
        CLASS_INDEX = {v: k for k, v in CLASS_INDEX.items()}             
    results = []
    for pred in preds:
        top_indices = pred.argsort()[-top:][::-1]
        result = [(i,CLASS_INDEX[i], pred[i],) for i in top_indices]
        result.sort(key=lambda x: x[2], reverse=True)
        results.append(result)
    return results




base_model = SqueezeNet(weights='imagenet',include_top=False,input_shape = (img_width,img_height,3))
base_model = Model(inputs=base_model.input,outputs = base_model.get_layer('fire9/concat').output)    
#feel free to experiement where to cut the layers, eg change fire9/concat to fire3.concat     
x = base_model.output                                              
x = Dropout(0.5)(x)
x = Conv2D(101,1)(x)
x = Activation('relu', name='relu_conv10')(x)
x = GlobalAveragePooling2D()(x)
x = Activation('softmax', name='loss')(x)

model = Model(inputs=base_model.input,outputs = x)
for layer in base_model.layers:                             #use pretrained wright
    layer.trainable = False
#opt = SGD(lr = 0.001,momentum = 0.5,decay = 0.9)
lr=1e-4
opt = RMSprop(lr=lr) #feel free to experiment with learning rate or try another optimizer
model.compile(optimizer=opt,loss='categorical_crossentropy',metrics=['accuracy'],
)
#model.summary()       #overview of model
history = model.fit_generator(                                                
    train_generator,
    steps_per_epoch=nb_train_samples // my_batch_size,
    epochs=epochs,
    validation_data=validation_generator,
    validation_steps=nb_validation_samples // my_batch_size,
    callbacks = [EarlyStopping(patience = 2)])    #stop early if validation loss increase after a epoch
print(history.history)
model.save_weights('squeezenet_updated_'+str(lr)+ history.history['val_acc'][-1]+'.h5')



