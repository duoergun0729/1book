import tensorflow as tf
from tensorflow.contrib.learn.python import learn
from sklearn import metrics
from sklearn.model_selection import train_test_split
import numpy as np
from sklearn.naive_bayes import GaussianNB
import os
from sklearn.feature_extraction.text import CountVectorizer
from tensorflow.contrib.layers.python.layers import encoders
from sklearn import svm


MAX_DOCUMENT_LENGTH = 50
EMBEDDING_SIZE = 50

n_words=0


def load_one_file(filename):
    x=""
    with open(filename) as f:
        for line in f:
            #line=line.strip('\n')
            x+=line
    return x

def load_files(rootdir,label):
    list = os.listdir(rootdir)
    x=[]
    y=[]
    for i in range(0, len(list)):
        path = os.path.join(rootdir, list[i])
        if os.path.isfile(path):
            print "Load file %s" % path
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


def rnn_model(features, target):
  """RNN model to predict from sequence of words to a class."""
  # Convert indexes of words into embeddings.
  # This creates embeddings matrix of [n_words, EMBEDDING_SIZE] and then
  # maps word indexes of the sequence into [batch_size, sequence_length,
  # EMBEDDING_SIZE].
  word_vectors = tf.contrib.layers.embed_sequence(
      features, vocab_size=n_words, embed_dim=EMBEDDING_SIZE, scope='words')

  # Split into list of embedding per word, while removing doc length dim.
  # word_list results to be a list of tensors [batch_size, EMBEDDING_SIZE].
  word_list = tf.unstack(word_vectors, axis=1)

  # Create a Gated Recurrent Unit cell with hidden size of EMBEDDING_SIZE.
  cell = tf.contrib.rnn.GRUCell(EMBEDDING_SIZE)


  # Create an unrolled Recurrent Neural Networks to length of
  # MAX_DOCUMENT_LENGTH and passes word_list as inputs for each unit.
  _, encoding = tf.contrib.rnn.static_rnn(cell, word_list, dtype=tf.float32)

  # Given encoding of RNN, take encoding of last step (e.g hidden size of the
  # neural network of last step) and pass it as features for logistic
  # regression over output classes.
  target = tf.one_hot(target, 15, 1, 0)
  logits = tf.contrib.layers.fully_connected(encoding, 15, activation_fn=None)
  loss = tf.contrib.losses.softmax_cross_entropy(logits, target)

  # Create a training op.
  train_op = tf.contrib.layers.optimize_loss(
      loss,
      tf.contrib.framework.get_global_step(),
      optimizer='Adam',
      learning_rate=0.01)

  return ({
      'class': tf.argmax(logits, 1),
      'prob': tf.nn.softmax(logits)
  }, loss, train_op)



def main(unused_argv):


    x,y=load_data()

    x_train, x_test, y_train, y_test = train_test_split(x, y, test_size=0.4, random_state=0)

    vp = learn.preprocessing.VocabularyProcessor(max_document_length=MAX_DOCUMENT_LENGTH, min_frequency=1)

    x_train = np.array(list(vp.fit_transform(x_train)))
    x_test = np.array(list(vp.transform(x_test)))
    n_words=len(vp.vocabulary_)
    print('Total words: %d' % n_words)

    gnb = GaussianNB()
    y_predict = gnb.fit(x_train, y_train).predict(x_test)
    score = metrics.accuracy_score(y_test, y_predict)
    print('NB Accuracy: {0:f}'.format(score))

    feature_columns = tf.contrib.learn.infer_real_valued_columns_from_input(x_train)
    classifier = tf.contrib.learn.DNNClassifier(
        feature_columns=feature_columns, hidden_units=[500,10], n_classes=2)

    classifier.fit(x_train, y_train, steps=5000, batch_size=10)
    y_predict=list(classifier.predict(x_test, as_iterable=True))
    score = metrics.accuracy_score(y_test, y_predict)
    print('DNN Accuracy: {0:f}'.format(score))

"""
    classifier = learn.Estimator(model_fn=rnn_model)
    classifier.fit(x_train, y_train, steps=200,batch_size=50)
    y_predict = [
        p['class'] for p in classifier.predict(
            x_test, as_iterable=True)
        ]
    score = metrics.accuracy_score(y_test, y_predict)
    print('RNN Accuracy: {0:f}'.format(score))

    clf = svm.SVC()
    clf.fit(x_train, y_train)
    y_predict=clf.predict(x_test)
    score = metrics.accuracy_score(y_test, y_predict)
    print('SVM Accuracy: {0:f}'.format(score))
"""


if __name__ == '__main__':
  tf.app.run()