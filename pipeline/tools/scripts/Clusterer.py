# -*- coding: utf-8 -*-
from matplotlib import pyplot as plt
from nltk import edit_distance
from scipy.cluster.hierarchy import dendrogram, fcluster, linkage
from scipy.spatial.distance import squareform

from TxtToPython import *


class Clusterer:


    """Classe convertissant un txt en liste de d'objets protéines"""

    def __init__(self,proteins):
        
        self.clusters= self.clustering(proteins)

    def clustering(self,proteins):

        distance_matrix = []

        for prot in proteins:
            distance_matrix.append([])
            for prot2 in proteins:
                distance_matrix[-1].append(edit_distance([domain.id for domain in prot.domains], [domain.id for domain in prot2.domains],transpositions=True))

        abstract_Dendrogramme = linkage(squareform(distance_matrix), 'ward')
        coresponding_clusters = fcluster(abstract_Dendrogramme,t=15,criterion='distance').tolist()

        clusters={}
        for i in range(len(proteins)):

            if coresponding_clusters[i] in clusters:
                clusters[coresponding_clusters[i]].append(proteins[i])
            else:
                clusters[coresponding_clusters[i]]=[proteins[i]]

        """ fig = plt.figure(figsize=(25, 10))
        dn = dendrogram(abstract_Dendrogramme)
        plt.show() """

        return clusters


    def __repr__(self):
        self_repr=""
        for cluster in self.clusters:
            for prot in self.clusters[cluster]:
                domains_info=""
                for domain in prot.domains:
                    domains_info+="\t"+domain.id+"\t"+domain.confiance+"\t"+domain.first+"\t"+domain.last
                self_repr+=prot.id+"\t"+prot.size+domains_info
            self_repr+="#\n"

        return self_repr
