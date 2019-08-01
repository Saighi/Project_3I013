# -*- coding: utf-8 -*-
import os


def setVariables(path_settings):
    with open(path_settings, "r") as f:
        settings = f
        paths = {}
        for line in settings:
            nom, valeur = line.split(":")
            paths[nom] = valeur[:len(valeur) - 1]
    return paths


def fromDictToFinalFile(proteinesDic, path_cible):
    with open(path_cible, "w") as f:
        fichierfinal = f

        for prot in proteinesDic:
            ligne = prot + "    " + proteinesDic[prot]["taille"]

            for domain in proteinesDic[prot]["domains"]:
                ligne += "    " + domain[0] + "    " + domain[1] + \
                         "    " + domain[2] + "    " + domain[3]
            fichierfinal.write(ligne + "\n")


def makeDama(paths, sample, nameNewRepertory):
    os.system(paths['PATH_REPERTORY'] + paths['PATH_HMMER'] + 'hmmscan --domtblout ' + paths['PATH_REPERTORY'] + paths[
        'PATH_USERSFILES'] + nameNewRepertory + 'temp/outputDAMAPerl ' + paths['PATH_REPERTORY'] + paths[
                  'PATH_DATABASE'] + 'Pfam-A.hmm ' + paths['PATH_REPERTORY'] + paths['PATH_SAMPLES'] + sample
              )
    os.system('perl ' + paths['PATH_REPERTORY'] + paths['PATH_DAMA'] + 'scripts/convertHmmscanOutput.pl -hmmscanFile ' +
              paths['PATH_REPERTORY'] + paths[
                  'PATH_USERSFILES'] + nameNewRepertory + 'temp/outputDAMAPerl  -outputFile ' + paths[
                  'PATH_REPERTORY'] + paths['PATH_USERSFILES'] + nameNewRepertory + 'temp/outputInterDAMA'
              )
    os.system(
        paths['PATH_REPERTORY'] + paths['PATH_DAMA'] + 'Release/src/DAMA -domainsHitFile ' + paths['PATH_REPERTORY'] +
        paths['PATH_USERSFILES'] + nameNewRepertory + 'temp/outputInterDAMA -knownArchFile ' + paths['PATH_REPERTORY'] +
        paths['PATH_DAMA'] + 'database/pfam32/pfam.knownArch.txt -outputFile ' + paths['PATH_REPERTORY'] + paths[
            'PATH_USERSFILES'] + nameNewRepertory + 'temp/outputfinal.dama -domainsInfoFile ' + paths[
            'PATH_REPERTORY'] + paths['PATH_DAMA'] + 'database/pfam32/pfam.domains'
        )


def clean(paths, nameNewRepertory):
    os.system('rm -rf ' + paths['PATH_REPERTORY'] + paths['PATH_USERSFILES'] + nameNewRepertory + 'temp')


def getFirst(elem):
    return int(elem[2])

def executeDama(paths, sample, nameOutput, username, nameNewRepertory):
    makeDama(paths, sample, nameNewRepertory)

    actual_path = '/home/' + username + '/Desktop/pipeline' + \
                  paths['PATH_USERSFILES'] + nameNewRepertory
    actual_path_temp = actual_path + 'temp/'
    proteines_dic = {}

    with open(actual_path_temp + 'outputDAMAPerl', "r") as f1:
        sequences = f1
        with open(actual_path_temp + 'outputfinal.dama', "r") as f2:
            domains = f2

            for ligne in sequences:
                if ligne[0] != "#":
                    tabligne = ligne.split()
                    if len(tabligne) > 6:
                        proteines_dic[tabligne[3]] = {"taille": tabligne[5]}

            for prot in proteines_dic:
                proteines_dic[prot]["domains"] = []

            for ligne in domains:
                tabligne = ligne.split()
                proteines_dic[tabligne[3]]["domains"].append(
                    (tabligne[4], tabligne[0], tabligne[1], tabligne[2]))

            for prot in proteines_dic:
                proteines_dic[prot]["domains"].sort(key=getFirst)

    fromDictToFinalFile(proteines_dic, actual_path + nameOutput)
    clean(paths, nameNewRepertory)


def makePerl(paths, sample, nameNewRepertory):
    os.system(paths['PATH_REPERTORY'] + paths['PATH_HMMER'] + 'hmmscan --domtblout ' + paths['PATH_REPERTORY'] + paths[
        'PATH_USERSFILES'] + nameNewRepertory + 'temp/outputDAMAPerl ' + paths['PATH_REPERTORY'] + paths[
                  'PATH_DATABASE'] + 'Pfam-A.hmm ' + paths['PATH_REPERTORY'] + paths['PATH_SAMPLES'] + sample
              )
    os.system('perl ' + paths['PATH_REPERTORY'] + paths['PATH_TOOLS'] + 'convertHmmscanOutput.pl -hmmscanFile ' + paths[
        'PATH_REPERTORY'] + paths['PATH_USERSFILES'] + nameNewRepertory + 'temp/outputDAMAPerl  -outputFile ' + paths[
                  'PATH_REPERTORY'] + paths['PATH_USERSFILES'] + nameNewRepertory + 'temp/outputInterPerl'
              )
    os.system('perl ' + paths['PATH_REPERTORY'] + paths['PATH_PERLLIB'] + '/HMMs/HMMER/hmmer.pl -k 6 -arg1 ' + paths[
        'PATH_REPERTORY'] + paths['PATH_USERSFILES'] + nameNewRepertory + 'temp/outputInterPerl -arg2 ' + paths[
                  'PATH_REPERTORY'] + paths['PATH_USERSFILES'] + nameNewRepertory + 'temp/outputfinalPerl'
              )


def executePerl(paths, sample, nameOutput, username, nameNewRepertory):
    makePerl(paths, sample, nameNewRepertory)

    actual_path = '/home/' + username + '/Desktop/pipeline' + \
                  paths['PATH_USERSFILES'] + nameNewRepertory
    actual_path_temp = actual_path + 'temp/'
    proteines_dic = {}

    with open(actual_path_temp + 'outputDAMAPerl', "r") as f1:
        sequences = f1
        with open(actual_path_temp + 'outputfinalPerl', "r") as f2:
            domains = f2

            for ligne in sequences:
                if ligne[0] != "#":
                    tabligne = ligne.split()
                    if len(tabligne) > 6:
                        proteines_dic[tabligne[3]] = {"taille": tabligne[5]}

            for prot in proteines_dic:
                proteines_dic[prot]["domains"] = []

            for ligne in domains:
                tabligne = ligne.split()
                proteines_dic[tabligne[3]]["domains"].append(
                    (tabligne[4], tabligne[0], tabligne[1], tabligne[2]))

            for prot in proteines_dic:
                proteines_dic[prot]["domains"].sort(key=getFirst)

    fromDictToFinalFile(proteines_dic, actual_path + nameOutput)
    clean(paths, nameNewRepertory)

  