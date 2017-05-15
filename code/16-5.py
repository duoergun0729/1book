# -*- coding:utf-8 -*-

import re
import matplotlib.pyplot as plt
import os
from sklearn.feature_extraction.text import CountVectorizer
from sklearn import cross_validation
import os
from sklearn.datasets import load_iris
from sklearn import tree
import pydotplus
import numpy as np
import tflearn
from tflearn.data_utils import to_categorical, pad_sequences
from sklearn import metrics
from sklearn.model_selection import train_test_split

max_sequences_len=300
max_sys_call=0

def load_one_flle(filename):
    global max_sys_call
    x=[]
    with open(filename) as f:
        line=f.readline()
        line=line.strip('\n')
        line=line.split(' ')
        for v in line:
            if len(v) > 0:
                x.append(int(v))
                if int(v) > max_sys_call:
                    max_sys_call=int(v)
    return x

def load_adfa_training_files(rootdir):
    x=[]
    y=[]
    list = os.listdir(rootdir)
    for i in range(0, len(list)):
        path = os.path.join(rootdir, list[i])
        if os.path.isfile(path):
            x.append(load_one_flle(path))
            y.append(0)
    return x,y

def dirlist(path, allfile):
    filelist = os.listdir(path)

    for filename in filelist:
        filepath = os.path.join(path, filename)
        if os.path.isdir(filepath):
            dirlist(filepath, allfile)
        else:
            allfile.append(filepath)
    return allfile

def load_adfa_webshell_files(rootdir):
    x=[]
    y=[]
    allfile=dirlist(rootdir,[])
    for file in allfile:
        if re.match(r"../data/ADFA-LD/Attack_Data_Master/Web_Shell_\d+/UAD-W*",file):
            x.append(load_one_flle(file))
            y.append(1)
    return x,y

def do_rnn(trainX, testX, trainY, testY):
    global max_sequences_len
    global max_sys_call
    # Data preprocessing
    # Sequence padding

    trainX = pad_sequences(trainX, maxlen=max_sequences_len, value=0.)
    testX = pad_sequences(testX, maxlen=max_sequences_len, value=0.)
    # Converting labels to binary vectors
    trainY = to_categorical(trainY, nb_classes=2)
    testY = to_categorical(testY, nb_classes=2)

    # Network building
    print "GET max_sequences_len embedding %d" % max_sequences_len
    print "GET max_sys_call embedding %d" % max_sys_call

    net = tflearn.input_data([None, max_sequences_len])
    net = tflearn.embedding(net, input_dim=max_sys_call+1, output_dim=128)
    net = tflearn.lstm(net, 128, dropout=0.8)
    net = tflearn.fully_connected(net, 2, activation='softmax')
    net = tflearn.regression(net, optimizer='adam', learning_rate=0.1,
                             loss='categorical_crossentropy')

    # Training



    model = tflearn.DNN(net, tensorboard_verbose=3)
    model.fit(trainX, trainY, validation_set=(testX, testY), show_metric=True,
             batch_size=32,run_id="maidou")

if __name__ == '__main__':
    x1,y1=load_adfa_training_files("../data/ADFA-LD/Training_Data_Master/")
    x2,y2=load_adfa_webshell_files("../data/ADFA-LD/Attack_Data_Master/")
    x=x1+x2
    y=y1+y2

    x_train, x_test, y_train, y_test = train_test_split(x, y, test_size=0.4, random_state=0)
    do_rnn(x_train, x_test, y_train, y_test)







