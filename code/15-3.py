import tensorflow as tf
import pickle
import gzip
from tensorflow.contrib.learn.python import learn
from sklearn import metrics

def load_data():
    with gzip.open('../data/MNIST/mnist.pkl.gz') as fp:
        training_data, valid_data, test_data = pickle.load(fp)
    return training_data, valid_data, test_data


def main(unused_argv):
    training_data, valid_data, test_dat=load_data()

    x_training_data,y_training_data=training_data
    x1,y1=test_dat

    feature_columns = learn.infer_real_valued_columns_from_input(x_training_data)

    feature_columns = tf.contrib.learn.infer_real_valued_columns_from_input(
        x_training_data)
    classifier = tf.contrib.learn.DNNClassifier(
        feature_columns=feature_columns, hidden_units=[100, 50, 10], n_classes=10)


    classifier.fit(x_training_data, y_training_data, steps=200)
    predictions = list(classifier.predict(x1, as_iterable=True))
    score = metrics.accuracy_score(y1, predictions)
    print('Accuracy: {0:f}'.format(score))


if __name__ == '__main__':
  tf.app.run()