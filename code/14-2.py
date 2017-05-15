# -*- coding:utf-8 -*-

import re
import matplotlib.pyplot as plt
import os
from sklearn.feature_extraction.text import CountVectorizer
from sklearn import cross_validation
import os

import numpy as np
from sklearn.neural_network import MLPClassifier


def load_one_flle(filename):
    x=[]
    with open(filename) as f:
        line=f.readline()
        line=line.strip('\n')
    return line

def load_adfa_training_files(rootdir):
    x=[]
    y=[]
    list = os.listdir(rootdir)
    for i in range(0, len(list)):
        path = os.path.join(rootdir, list[i])
        if os.path.isfile(path):
            x.append(load_one_flle(path))
            print "Load file(%s)" % path
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

def load_adfa_java_files(rootdir):
    x=[]
    y=[]
    allfile=dirlist(rootdir,[])
    for file in allfile:
        if re.match(r"../data/ADFA-LD/Attack_Data_Master/Java_Meterpreter_\d+/UAD-Java-Meterpreter*",file):
            print "Load file(%s)" % file
            x.append(load_one_flle(file))
            y.append(1)
    return x,y



if __name__ == '__main__':

    x1,y1=load_adfa_training_files("../data/ADFA-LD/Training_Data_Master/")
    x2,y2=load_adfa_java_files("../data/ADFA-LD/Attack_Data_Master/")

    x=x1+x2
    y=y1+y2
    #print x
    vectorizer = CountVectorizer(min_df=1)
    x=vectorizer.fit_transform(x)
    x=x.toarray()

    mlp = MLPClassifier(hidden_layer_sizes=(150,50), max_iter=10, alpha=1e-4,
                        solver='sgd', verbose=10, tol=1e-4, random_state=1,
                        learning_rate_init=.1)

    score=cross_validation.cross_val_score(mlp, x, y, n_jobs=-1, cv=10)
    print  np.mean(score)







