# -*- coding:utf-8 -*-

import re
import matplotlib.pyplot as plt
import os
from sklearn.feature_extraction.text import CountVectorizer
from sklearn import cross_validation
import os
from sklearn.naive_bayes import GaussianNB


def load_kdd99(filename):
    x=[]
    with open(filename) as f:
        for line in f:
            line=line.strip('\n')
            line=line.split(',')
            x.append(line)
    return x

def get_apache2andNormal(x):
    v=[]
    w=[]
    y=[]
    for x1 in x:
        if ( x1[41] in ['apache2.','normal.'] ) and ( x1[2] == 'http' ):
            if x1[41] == 'apache2.':
                y.append(1)
            else:
                y.append(0)

            x1 = [x1[0]] + x1[4:8]+x1[22:30]+x1[31:40]
            #x1 = x1[4:8]
            v.append(x1)

    for x1 in v :
        v1=[]
        for x2 in x1:
            v1.append(float(x2))
        w.append(v1)
    return w,y

if __name__ == '__main__':
    v=load_kdd99("../data/kddcup99/corrected")
    x,y=get_apache2andNormal(v)
    clf = GaussianNB()
    print  cross_validation.cross_val_score(clf, x, y, n_jobs=-1, cv=10)




