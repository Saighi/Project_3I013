# -*- coding: utf-8 -*-

# TODO qqchose

class Protein:
    """Classe définissant un domain caractérisée par :
    - son début
    - sa fin
    - sa confiance
    - son nom"""

    def __init__(self, id, size, domains):
        """Constructeur de notre classe"""
        self.size = size
        self.id = id
        self.domains = domains

    def __repr__(self):
        return str(self.__dict__)