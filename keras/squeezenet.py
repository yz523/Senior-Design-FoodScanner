from keras_squeezenet import SqueezeNet
from keras.preprocessing.image import ImageDataGenerator
from keras.models import Model
from keras.layers import Dense, GlobalAveragePooling2D

base_model = SqueezeNet(weights='imagenet', include_top=False)      # use pretrained model, exclude last layer(classifier)

x = base_model.output                                               # define new classifier
x = GlobalAveragePooling2D()(x)
x = Dense(1024, activation='relu')(x)
predictions = Dense(101, activation='softmax')(x)
model = Model(inputs=base_model.input, outputs=predictions)

for layer in base_model.layers:                                     #do not train pretrained model
    layer.trainable = False
model.compile(optimizer='rmsprop', loss='categorical_crossentropy') #loss and optimizer function


train_datagen = ImageDataGenerator(                                 #image augmentation
    rescale=1./255,
    shear_range=0.2,    
    width_shift_range=0.2,  
    height_shift_range=0.2,
    horizontal_flip=True,
    vertical_flip=True,
    zoom_range=[.8, 1],
    channel_shift_range=30,
    fill_mode='reflect')
test_datagen = ImageDataGenerator(rescale=1./255)                   #rescale for verification data


img_width,img_height = 299,299                                      #resize image
train_data_dir = r'dataset\food-101\train'
validation_data_dir = r'dataset\food-101\test'
nb_train_samples = 75750
nb_validation_samples = 25250
epochs = 50
my_batch_size = 32

train_generator = train_datagen.flow_from_directory(                #prepare batch, each subfolder represent one class 
    train_data_dir,
    target_size=(img_width, img_height),
    batch_size=my_batch_size,)

validation_generator = test_datagen.flow_from_directory(
    validation_data_dir,
    target_size=(img_width, img_height),
    batch_size=my_batch_size,
)

model.fit_generator(                                                #train the model(just the classifier)
    train_generator,
    steps_per_epoch=nb_train_samples // my_batch_size,
    epochs=epochs,
    validation_data=validation_generator,
    validation_steps=nb_validation_samples // my_batch_size)

model.save_weights('first_try.h5')                                  #save the trained model