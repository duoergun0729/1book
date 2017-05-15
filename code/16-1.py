# -*- coding: utf-8 -*-

from __future__ import division, print_function, absolute_import

import numpy as np
import tflearn
from sklearn import metrics
import tflearn.datasets.mnist as mnist


X, Y, testX, testY = mnist.load_data(one_hot=True)

def do_DNN(X, Y, testX, testY):
    # Building deep neural network
    input_layer = tflearn.input_data(shape=[None, 784])
    dense1 = tflearn.fully_connected(input_layer, 64, activation='tanh',
                                     regularizer='L2', weight_decay=0.001)
    dropout1 = tflearn.dropout(dense1, 0.8)
    dense2 = tflearn.fully_connected(dropout1, 64, activation='tanh',
                                     regularizer='L2', weight_decay=0.001)
    dropout2 = tflearn.dropout(dense2, 0.8)
    softmax = tflearn.fully_connected(dropout2, 10, activation='softmax')

    # Regression using SGD with learning rate decay and Top-3 accuracy
    sgd = tflearn.SGD(learning_rate=0.1, lr_decay=0.96, decay_step=1000)
    top_k = tflearn.metrics.Top_k(3)
    net = tflearn.regression(softmax, optimizer=sgd, metric=top_k,
                             loss='categorical_crossentropy')

    # Training
    model = tflearn.DNN(net, tensorboard_verbose=0)
    model.fit(X, Y, n_epoch=20, validation_set=(testX, testY),
              show_metric=True, run_id="dense_model")

def do_rnn(X, Y, testX, testY):
    X = np.reshape(X, (-1, 28, 28))
    testX = np.reshape(testX, (-1, 28, 28))

    net = tflearn.input_data(shape=[None, 28, 28])
    net = tflearn.lstm(net, 128, return_seq=True)
    net = tflearn.lstm(net, 128)
    net = tflearn.fully_connected(net, 10, activation='softmax')
    net = tflearn.regression(net, optimizer='adam',
                         loss='categorical_crossentropy', name="output1")
    model = tflearn.DNN(net, tensorboard_verbose=2)
    model.fit(X, Y, n_epoch=1, validation_set=(testX,testY), show_metric=True,
          snapshot_step=100)


#do_DNN(X, Y, testX, testY)
do_rnn(X, Y, testX, testY)