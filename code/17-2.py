from __future__ import division, print_function, absolute_import

import tensorflow as tf
import tflearn
from tflearn.layers.core import input_data, dropout, fully_connected
from tflearn.layers.conv import conv_1d, global_max_pool
from tflearn.layers.merge_ops import merge
from tflearn.layers.estimator import regression
from tflearn.data_utils import to_categorical, pad_sequences
from tflearn.datasets import imdb
import os
from tensorflow.contrib.learn.python import learn
from sklearn import metrics
from sklearn.model_selection import train_test_split
import numpy as np

MAX_DOCUMENT_LENGTH = 200
EMBEDDING_SIZE = 50

n_words=0


def load_one_file(filename):
    x=""
    with open(filename) as f:
        for line in f:
            x+=line
    return x

def load_files(rootdir,label):
    list = os.listdir(rootdir)
    x=[]
    y=[]
    for i in range(0, len(list)):
        path = os.path.join(rootdir, list[i])
        if os.path.isfile(path):
            #print "Load file %s" % path
            y.append(label)
            x.append(load_one_file(path))

    return x,y


def load_data():
    x=[]
    y=[]
    x1,y1=load_files("../data/movie-review-data/review_polarity/txt_sentoken/pos/",0)
    x2,y2=load_files("../data/movie-review-data/review_polarity/txt_sentoken/neg/", 1)
    x=x1+x2
    y=y1+y2
    return x,y
def  do_cnn(trainX, trainY,testX, testY):
    global n_words
    # Data preprocessing
    # Sequence padding
    trainX = pad_sequences(trainX, maxlen=MAX_DOCUMENT_LENGTH, value=0.)
    testX = pad_sequences(testX, maxlen=MAX_DOCUMENT_LENGTH, value=0.)
    # Converting labels to binary vectors
    trainY = to_categorical(trainY, nb_classes=2)
    testY = to_categorical(testY, nb_classes=2)

    # Building convolutional network
    network = input_data(shape=[None, MAX_DOCUMENT_LENGTH], name='input')
    network = tflearn.embedding(network, input_dim=n_words+1, output_dim=128)
    branch1 = conv_1d(network, 128, 3, padding='valid', activation='relu', regularizer="L2")
    branch2 = conv_1d(network, 128, 4, padding='valid', activation='relu', regularizer="L2")
    branch3 = conv_1d(network, 128, 5, padding='valid', activation='relu', regularizer="L2")
    network = merge([branch1, branch2, branch3], mode='concat', axis=1)
    network = tf.expand_dims(network, 2)
    network = global_max_pool(network)
    network = dropout(network, 0.5)
    network = fully_connected(network, 2, activation='softmax')
    network = regression(network, optimizer='adam', learning_rate=0.001,
                         loss='categorical_crossentropy', name='target')
    # Training
    model = tflearn.DNN(network, tensorboard_verbose=0)
    model.fit(trainX, trainY, n_epoch = 20, shuffle=True, validation_set=(testX, testY), show_metric=True, batch_size=32)

if __name__ == '__main__':
    # IMDB Dataset loading
    global n_words

    x,y=load_data()

    x_train, x_test, y_train, y_test = train_test_split(x, y, test_size=0.4, random_state=0)

    vp = learn.preprocessing.VocabularyProcessor(max_document_length=MAX_DOCUMENT_LENGTH, min_frequency=1)
    vp.fit(x)
    x_train = np.array(list(vp.transform(x_train)))
    x_test = np.array(list(vp.transform(x_test)))
    n_words=len(vp.vocabulary_)
    print('Total words: %d' % n_words)

    do_cnn(x_train, y_train,x_test, y_test)