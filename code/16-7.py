# -*- coding:utf-8 -*-

import sys

import re
import numpy as np


import nltk
import csv
import matplotlib.pyplot as plt
from nltk.probability import FreqDist
from sklearn.feature_extraction.text import CountVectorizer

from sklearn import cross_validation
from tflearn.data_utils import to_categorical, pad_sequences
from tflearn.datasets import imdb
import tflearn

#测试样本数
N=80

def load_user_cmd_new(filename):
    cmd_list=[]
    dist=[]
    with open(filename) as f:
        i=0
        x=[]
        for line in f:
            line=line.strip('\n')
            x.append(line)
            dist.append(line)
            i+=1
            if i == 100:
                cmd_list.append(x)
                x=[]
                i=0

    fdist = FreqDist(dist).keys()
    return cmd_list,fdist

def load_user_cmd(filename):
    cmd_list=[]
    dist_max=[]
    dist_min=[]
    dist=[]
    with open(filename) as f:
        i=0
        x=[]
        for line in f:
            line=line.strip('\n')
            x.append(line)
            dist.append(line)
            i+=1
            if i == 100:
                cmd_list.append(x)
                x=[]
                i=0

    fdist = FreqDist(dist).keys()
    dist_max=set(fdist[0:50])
    dist_min = set(fdist[-50:])
    return cmd_list,dist_max,dist_min

def get_user_cmd_feature(user_cmd_list,dist_max,dist_min):
    user_cmd_feature=[]
    for cmd_block in user_cmd_list:
        f1=len(set(cmd_block))
        fdist = FreqDist(cmd_block).keys()
        f2=fdist[0:10]
        f3=fdist[-10:]
        f2 = len(set(f2) & set(dist_max))
        f3=len(set(f3)&set(dist_min))
        x=[f1,f2,f3]
        user_cmd_feature.append(x)
    return user_cmd_feature

def get_user_cmd_feature_new(user_cmd_list,dist):
    user_cmd_feature=[]
    for cmd_list in user_cmd_list:
        x=[]
        for cmd in  cmd_list:
            v = [0] * len(dist)
            for i in range(0, len(dist)):
                if cmd == dist[i]:
                    v[i] = 1
            x.append(v)
        user_cmd_feature.append(x)
    return user_cmd_feature

def get_label(filename,index=0):
    x=[]
    with open(filename) as f:
        for line in f:
            line=line.strip('\n')
            x.append( int(line.split()[index]))
    return x


def do_knn(x_train,y_train,x_test,y_test):
    neigh = KNeighborsClassifier(n_neighbors=3)
    neigh.fit(x_train, y_train)
    y_predict=neigh.predict(x_test)
    score = np.mean(y_test == y_predict) * 100

    print  score


def do_rnn(x_train,x_test,y_train,y_test):
    global n_words
    # Data preprocessing
    # Sequence padding
    print "GET n_words embedding %d" % n_words


    #x_train = pad_sequences(x_train, maxlen=100, value=0.)
    #x_test = pad_sequences(x_test, maxlen=100, value=0.)
    # Converting labels to binary vectors
    y_train = to_categorical(y_train, nb_classes=2)
    y_test = to_categorical(y_test, nb_classes=2)

    # Network building
    net = tflearn.input_data(shape=[None, 100,n_words])
    net = tflearn.lstm(net, 10,  return_seq=True)
    net = tflearn.lstm(net, 10, )
    net = tflearn.fully_connected(net, 2, activation='softmax')
    net = tflearn.regression(net, optimizer='adam', learning_rate=0.1,name="output",
                             loss='categorical_crossentropy')

    # Training

    model = tflearn.DNN(net, tensorboard_verbose=3)
    model.fit(x_train, y_train, validation_set=(x_test, y_test), show_metric=True,
             batch_size=32,run_id="maidou")


if __name__ == '__main__':
    user_cmd_list,dist=load_user_cmd_new("../data/MasqueradeDat/User7")
    #print  "Dist:(%s)" % dist
    n_words=len(dist)
    user_cmd_feature=get_user_cmd_feature_new(user_cmd_list,dist)

    labels=get_label("../data/MasqueradeDat/label.txt",6)
    y=[0]*50+labels

    x_train=user_cmd_feature[0:N]
    y_train=y[0:N]

    x_test=user_cmd_feature[N:150]
    y_test=y[N:150]

    #print x_train

    do_rnn(x_train,x_test,y_train,y_test)





