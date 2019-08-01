# -*- coding: utf-8 -*-
class Domain:
    """Classe définissant un domain caractérisée par :
    - son début
    - sa fin
    - sa confiance
    - son nom"""

    def __init__(self, id, confiance, first, last):
        """Constructeur de notre classe"""
        self.first = first
        self.last = last
        self.id = id
        self.confiance = confiance

    def __repr__(self):
        return str(self.__dict__)