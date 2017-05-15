import tensorflow as tf
from tensorflow.contrib.learn.python import learn
from sklearn import metrics
from sklearn.model_selection import train_test_split
import numpy as np
from sklearn.naive_bayes import GaussianNB

#0,0.64,0.64,0,0.32,0,0,0,0,0,0,0.64,0,0,0,0.32,0,1.29,1.93,0,0.96,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
# 0,0,0,0,0.778,0,0,3.756,61,278,1
def load_SpamBase(filename):
    x=[]
    y=[]
    with open(filename) as f:
        for line in f:
            line=line.strip('\n')
            v=line.split(',')
            y.append(int(v[-1]))
            t=[]
            for i in range(57):
                t.append(float(v[i]))
            t=np.array(t)
            x.append(t)

    x=np.array(x)
    y=np.array(y)
    print x.shape
    print y.shape

    x_train, x_test, y_train, y_test=train_test_split( x,y, test_size=0.4, random_state=0)
    print x_train.shape
    print x_test.shape
    return x_train, x_test, y_train, y_test



def main(unused_argv):
    x_train, x_test, y_train, y_test=load_SpamBase("../data/spambase/spambase.data")


    feature_columns = tf.contrib.learn.infer_real_valued_columns_from_input(x_train)
    classifier = tf.contrib.learn.DNNClassifier(
        feature_columns=feature_columns, hidden_units=[30,10], n_classes=2)


    classifier.fit(x_train, y_train, steps=500,batch_size=10)
    y_predict=list(classifier.predict(x_test, as_iterable=True))
    #y_predict = classifier.predict(x_test)
    #print y_predict
    score = metrics.accuracy_score(y_test, y_predict)
    print('Accuracy: {0:f}'.format(score))

    gnb = GaussianNB()
    y_predict = gnb.fit(x_train, y_train).predict(x_test)
    score = metrics.accuracy_score(y_test, y_predict)
    print('Accuracy: {0:f}'.format(score))


if __name__ == '__main__':
  tf.app.run()