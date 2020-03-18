import os, sys

def dfs (path, subject, replace):
	files = os.listdir (path)

	for file in files:
		filePath = path + "/" + file
		if (os.path.isdir (filePath)):
			dfs (filePath)
		else:
			fd = open (filePath, "r")
			data = fd.read ()
			fd.close ()

			if (subject in data):
				print ("Replacing in {}".format (filePath))
				data = data.replace (subject, replace)

				fd = open (filePath, "w")
				fd.write (data)
				fd.close ()

if (len (sys.argv) < 3):
	print ("Usage: {} subject replace".format (sys.argv[0]))
	exit (1)

dfs (os.getcwd ())