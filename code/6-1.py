# -*- coding: utf-8 -*-
from sklearn.datasets import load_iris
from sklearn import tree
import pydotplus

#mac下执行下列命令
#brew install graphviz
#pip install - -upgrade graphviz

iris = load_iris()


clf = tree.DecisionTreeClassifier()
clf = clf.fit(iris.data, iris.target)


dot_data = tree.export_graphviz(clf, out_file=None)
graph = pydotplus.graph_from_dot_data(dot_data)
graph.write_pdf("../photo/6/iris.pdf")