import sys
import getpass
from tools import *
from Clusterer import *
from TxtToPython import *

toolslist = {"dama": executeDama, "perl": executePerl}
username = getpass.getuser()
paths = setVariables(sys.argv[1])
sample = sys.argv[2]
final_out_put_name = sys.argv[3]
tool = sys.argv[4]
selectedtool = toolslist.get(tool, lambda: "Invalid tool")

# nameNewRepertory = str(sum([len(dir) for r, dir, files in os.walk(
#     "../../usersFiles")]))+'/'  # Only relativ path !!!
# os.system('mkdir -p '+paths['PATH_REPERTORY'] +
#           paths['PATH_USERSFILES']+nameNewRepertory+"temp")
# selectedtool(paths, sample, final_out_put_name, username, nameNewRepertory)

nameNewRepertory="0/"

Converted_data = TxtToPython(paths["PATH_REPERTORY"]+\
                 paths['PATH_USERSFILES'] + nameNewRepertory + final_out_put_name)

clustered_proteins = Clusterer(Converted_data.proteins)

print(clustered_proteins)

