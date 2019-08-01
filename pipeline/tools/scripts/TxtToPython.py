# -*- coding: utf-8 -*-
from Domain import *
from Protein import *


class TxtToPython:
    """Classe convertissant un txt en liste d'objets protéines"""

    def __init__(self, file):
        """Constructeur de notre classe"""

        self.proteins = []
        self.set_list_of_proteins(file)

    def set_list_of_proteins(self, file):
        with open(file, "r") as f:
            for line in f:
                protein_as_a_list = line.split("\t")
                domains_list = []
                for i in range(2, len(protein_as_a_list), 4):
                    domains_list.append(Domain(protein_as_a_list[i], protein_as_a_list[i + 1], protein_as_a_list[i+2],
                                               protein_as_a_list[i+3]))
                self.proteins.append(Protein(protein_as_a_list[0], protein_as_a_list[1], domains_list))
