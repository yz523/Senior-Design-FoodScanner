food-101 is a 5g dataset containing 101 classes of food, since it is very large
and available at https://www.vision.ee.ethz.ch/datasets_extra/food-101/ I am not uploading it to drive.

split.py split the food-101 dataset into train and test data based on the meta file test.txt and train.txt.

squeezenet.py train and save a model based on the food-101 dataset.  Note the food-101 dataset has to be splited first for squeezenet.py to work.

Following packages are required(does not cover everything):keras and keras_squeezenet
Additionaly, to run tensorflow(back end of keras) on gpu, you will need a NVIDIA gpu along with CUDA and cudnn.  Check the tensorflow documentation for details. 

