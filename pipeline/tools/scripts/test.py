import os
cpt = sum([len(dir) for r, dir, files in os.walk("../../usersFiles")])
print(cpt)