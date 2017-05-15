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


def load_kdd99(filename):
    x=[]
    with open(filename) as f:
        for line in f:
            line=line.strip('\n')
            line=line.split(',')
            x.append(line)
    return x

def get_guess_passwdandNormal(x):
    v=[]
    w=[]
    y=[]
    for x1 in x:
        if ( x1[41] in ['guess_passwd.','normal.'] ) and ( x1[2] == 'pop_3' ):
            if x1[41] == 'guess_passwd.':
                y.append(1)
            else:
                y.append(0)

            x1 = [x1[0]] + x1[4:8]+x1[22:30]
            v.append(x1)

    for x1 in v :
        v1=[]
        for x2 in x1:
            v1.append(float(x2))
        w.append(v1)
    return w,y

if __name__ == '__main__':
    v=load_kdd99("../data/kddcup99/corrected")
    x,y=get_guess_passwdandNormal(v)
    clf = tree.DecisionTreeClassifier()
    print  cross_validation.cross_val_score(clf, x, y, n_jobs=-1, cv=10)

    clf = clf.fit(x, y)
    dot_data = tree.export_graphviz(clf, out_file=None)
    graph = pydotplus.graph_from_dot_data(dot_data)
    graph.write_pdf("../photo/6/iris-dt.pdf")




