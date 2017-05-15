with open("normal-log.txt") as f:
	for line in f:
		line=line.strip("\n")
		print "/wp-login.php?action=%s" % line
