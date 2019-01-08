# -*- coding: utf-8 -*-
"""
Created on Sat May  5 18:12:02 2018

@author: lemur
"""
import numpy as np
from keras.applications import Xception
from keras.models import Model
from keras.layers import Dense, GlobalAveragePooling2D
from keras.preprocessing.image import ImageDataGenerator,load_img,img_to_array, array_to_img
from keras.optimizers import SGD, RMSprop
from keras.callbacks import ModelCheckpoint,LearningRateScheduler,CSVLogger                 
from keras.applications.xception import preprocess_input
from scipy.misc import imresize
from PIL import Image
import os

# =============================================================================
# def decode_predictions(preds, top=5):
#    
#     global CLASS_INDEX
#     if CLASS_INDEX is None:
#         CLASS_INDEX = validation_generator.class_indices
#         CLASS_INDEX = {v: k for k, v in CLASS_INDEX.items()}             
#     results = []
#     for pred in preds:
#         top_indices = pred.argsort()[-top:][::-1]
#         result = [(i,CLASS_INDEX[i], pred[i],) for i in top_indices]
#         result.sort(key=lambda x: x[2], reverse=True)
#         results.append(result)
#     return results
# =============================================================================

img_width,img_height = 299,299                                      
train_data_dir = r'dataset\food-101\train'
validation_data_dir = r'dataset\food-101\test'
epochs = 40
my_batch_size = 8
def reverse_preprocess_input(x0):
    x = x0 / 2.0
    x += 0.5
    x *= 255.
    return x


def random_crop(x, random_crop_size = (img_width,img_height), sync_seed=None):
# =============================================================================
#     perform random crop, if the width or height is smaller than given crop
#     size, then the image will be resized
# =============================================================================
    np.random.seed(sync_seed)
    w, h = x.shape[0], x.shape[1]
    if w < img_width:
        wpercent = (img_width/float(w))
        hsize = int((float(h)*float(wpercent)))
        x = imresize(x, (img_width, hsize))
        w, h = x.shape[0], x.shape[1]
    if h < img_height:
        hpercent = (img_height/float(h))
        wsize = int((float(w)*float(hpercent)))
        x = imresize(x, (wsize, img_height))
        w, h = x.shape[0], x.shape[1]
        
    
    rangew = (w - random_crop_size[0]) 
    rangeh = (h - random_crop_size[1]) 
    #print('w: {}, h: {}, rangew: {}, rangeh: {}'.format(w, h, rangew, rangeh))
    offsetw = 0 if rangew == 0 else np.random.randint(rangew)
    offseth = 0 if rangeh == 0 else np.random.randint(rangeh)
    return x[offsetw:offsetw+random_crop_size[0], offseth:offseth+random_crop_size[1], :]
def load_images(root, num_of_img,random_crop_size = (img_width,img_height)):
# =============================================================================
#     loading image myself because flow_from_directory force to resize image
#     and does not have crop functionality
#     
# =============================================================================
    index = 0
    
    x=np.zeros((num_of_img,img_width,img_height,3), dtype = 'uint8' )   
    y=np.zeros((num_of_img,img_width,img_height,3), dtype = 'uint8' ) #1d numpy array of labels,
    d = {} #directory, keys are the labels, values are labels names
    print('loading image from',root)
    for i, subdir in enumerate(sorted(os.listdir(root))):
        print('loading',i,subdir)
        d[i] = subdir
        imgs = os.listdir(os.path.join(root,subdir))
        for img in imgs:
            pil_image = load_img(os.path.join(root,subdir,img))
            crop = random_crop(img_to_array(pil_image),random_crop_size)
            #using unsinged integer since I do not have enough memory
            x[index] = crop.astype('uint8')
            y[index] = i
            index +=1
    return x,y,d,index

# =============================================================================
# train_x is an array holding all of the 75000 training images
# train_y is an array holding the label for these images
# same goes for test_x and test_y for the 25000 testing images
# =============================================================================
print('loading training images')
train_x,train_y,ix_to_label,nb_train_samples = load_images(train_data_dir,75750)

print('\nloading testing images')
test_x,test_y ,ix_to_label,nb_validation_samples = load_images(validation_data_dir,25250)


base_model = Xception(include_top=False, weights='imagenet',input_shape = (img_width,img_height,3))
base_model = Model(inputs=base_model.input,outputs = base_model.get_layer('block14_sepconv2').output)    
x = base_model.output    
x = GlobalAveragePooling2D(name='avg_pool')(x)
x = Dense(101, activation='softmax', name='predictions')(x)

model = Model(inputs=base_model.input,outputs = x)
for layer in base_model.layers:                             #use pretrained wright
    layer.trainable = False
# =============================================================================
lr=1e-2
opt = SGD(lr = lr,momentum = 0.9) #
#opt = RMSprop(lr = lr)
# =============================================================================


model.compile(optimizer=opt,loss='categorical_crossentropy',metrics=['accuracy'])
train_datagen = ImageDataGenerator(                                #image augmentation
    rotation_range = 0,        
    width_shift_range=0.2,
    height_shift_range=0.2, 
    horizontal_flip=True,
    zoom_range=[.8, 1],
    channel_shift_range=30,
    fill_mode='reflect',
    preprocessing_function=preprocess_input)
test_datagen = ImageDataGenerator(preprocessing_function=preprocess_input)

train_generator = train_datagen.flow(train_x,train_y,my_batch_size)
validation_generator = test_datagen.flow(test_x,test_y,my_batch_size)


checkpointer = ModelCheckpoint(filepath='xception1_.{epoch:02d}-{val_loss:.2f}.hdf5', verbose=1, save_best_only=True)
def schedule(epoch,lr):
    if epoch < 10 :
        return .00008
    elif epoch < 20:
        return .000016
    else:
        return .0000032
lr_scheduler = LearningRateScheduler(schedule,verbose= 1)
csv_logger = CSVLogger('xception_1.log')
    
history = model.fit_generator(                                                
    train_generator,
    steps_per_epoch=nb_train_samples // my_batch_size,
    epochs=epochs,
    validation_data=validation_generator,
    validation_steps=nb_validation_samples // my_batch_size,
    callbacks=[lr_scheduler,csv_logger,checkpointer])    
print(history.history)
