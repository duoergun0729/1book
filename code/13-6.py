import networkx as nx
import matplotlib.pyplot as plt



def helloWord():
    G = nx.Graph()
    G.add_node("u1")
    G.add_node("u2")
    G.add_edge("u1", "1.1.1.1")
    G.add_edge("u2", "1.1.1.1")
    nx.draw(G,with_labels=True,node_size=600)
    plt.show()

def show1():
    with open("../data/KnowledgeGraph/sample1.txt") as f:
        G = nx.Graph()
        for line in f:
            line=line.strip('\n')
            uid,ip,tel,activesyncid=line.split(',')
            G.add_edge(uid, ip)
            G.add_edge(uid, tel)
            G.add_edge(uid, activesyncid)
        nx.draw(G, with_labels=True, node_size=600)
        plt.show()

def show2():
    with open("../data/KnowledgeGraph/sample2.txt") as f:
        G = nx.Graph()
        for line in f:
            line=line.strip('\n')
            uid,ip,login,ua=line.split(',')
            G.add_edge(uid, ip)
            G.add_edge(uid, login)
            G.add_edge(uid, ua)
        nx.draw(G, with_labels=True, node_size=600)
        plt.show()

def show3():
    G = nx.Graph()
    with open("../data/KnowledgeGraph/sample3.txt") as f:
        for line in f:
            line=line.strip('\n')
            hid,uid,app=line.split(',')
            G.add_edge(hid, uid)
            G.add_edge(hid, app)
    f.close()

    with open("../data/KnowledgeGraph/sample4.txt") as f:
        for line in f:
            line=line.strip('\n')
            hid,uid,action=line.split(',')
            G.add_edge(hid, uid)
            G.add_edge(hid, action)
    f.close()

    nx.draw(G, with_labels=True, node_size=600)
    plt.show()

def show4():
    G = nx.Graph()
    with open("../data/KnowledgeGraph/sample5.txt") as f:
        for line in f:
            line=line.strip('\n')
            mail,domain,ip=line.split(',')
            G.add_edge(mail, domain)
            G.add_edge(domain, ip)
    f.close()

    nx.draw(G, with_labels=True, node_size=600)
    plt.show()

def show5():
    G = nx.Graph()
    with open("../data/KnowledgeGraph/sample6.txt") as f:
        for line in f:
            line=line.strip('\n')
            file,domain=line.split(',')
            G.add_edge(file, domain)

    f.close()

    nx.draw(G, with_labels=True, node_size=600)
    plt.show()
if __name__ == '__main__':
    print "Knowledge Graph"
    #helloWord()
    show5()