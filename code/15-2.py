import tensorflow as tf
import pickle
import gzip



def get_one_hot(x,size=10):
    v=[]
    for x1 in x:
        x2=[0]*size
        x2[(x1-1)]=1
        v.append(x2)
    return v


def load_data():
    with gzip.open('../data/MNIST/mnist.pkl.gz') as fp:
        training_data, valid_data, test_data = pickle.load(fp)
    return training_data, valid_data, test_data

training_data, valid_data, test_dat=load_data()

x_training_data,y_training_data=training_data
x1,y1=test_dat

y_training_data=get_one_hot(y_training_data)
y1=get_one_hot(y1)


batch_size=100

in_units=784
h1_units=300
W1=tf.Variable(tf.truncated_normal([in_units,h1_units],stddev=0.1))
b1=tf.Variable(tf.zeros([h1_units]))
W2=tf.Variable(tf.zeros([h1_units,10]))
b2=tf.Variable(tf.zeros([10]))

x = tf.placeholder(tf.float32, [None, in_units])
keep_prob=tf.placeholder(tf.float32)

hidden1=tf.nn.relu(tf.matmul(x,W1)+b1)
hidden1_drop=tf.nn.dropout(hidden1,keep_prob)


y = tf.nn.softmax(tf.matmul(hidden1_drop,W2) + b2)
y_ = tf.placeholder(tf.float32, [None,10])

cross_entropy = tf.reduce_mean(-tf.reduce_sum(y_*tf.log(y),reduction_indices=[1]))
train_step = tf.train.AdagradOptimizer(0.3).minimize(cross_entropy)

init = tf.initialize_all_variables()
sess = tf.Session()
sess.run(init)



for i in range(int(len(x_training_data)/batch_size)):
    batch_xs=x_training_data[(i*batch_size):((i+1)*batch_size)]
    batch_ys=y_training_data[(i*batch_size):((i+1)*batch_size)]

    sess.run(train_step, feed_dict={x: batch_xs, y_: batch_ys,keep_prob:0.75})


correct_prediction = tf.equal(tf.argmax(y, 1), tf.argmax(y_, 1))
accuracy = tf.reduce_mean(tf.cast(correct_prediction, tf.float32))

print(sess.run(accuracy, feed_dict={x: x1, y_: y1,keep_prob:1.0}))