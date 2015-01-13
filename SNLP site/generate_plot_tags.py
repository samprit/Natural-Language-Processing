from __future__ import division
# from table import *
# from sets import Set 
import numpy as np
import pylab
import matplotlib.pyplot as plt
from matplotlib.backends.backend_pdf import PdfPages
import sys


def generate_plot_sentiment(filename) :
  pp = PdfPages(filename+'_temporal.pdf')
  fname = open("Network.txt")
  tags = open("output.txt")
  dict = {}
  d = {}
  d["POS"] = 0
  d["NEU"] = 1
  d["NEG"] = 2

  dc = {}
  mlist = [0]*50
  pos = [0]*50
  neu = [0]*50
  neg = [0]*50
  yr_out = int(filename[1:3])
  if(yr_out<=13) :
    yr_out+=2000
  else :
    yr_out+=1900

  max_val = 0
  for f,t in zip(fname.readlines(), tags.readlines()) : 
    s = f.split('\t')
    s[1] = s[1].strip('\n')
    if(not(s[1] == filename)) :
      continue
    yr_in = int(s[0][1:3])

    if(yr_in<=13) :
      yr_in+=2000
    else :
      yr_in+=1900
    if(yr_in-yr_out > max_val) : 
      max_val = yr_in-yr_out

    # print yr_in, yr_out
    t = t.split('\n')

    mlist[yr_in - yr_out]+=1
    if(t[0] == "POS"):
      pos[yr_in - yr_out]+= 1
    if(t[0] == "NEU"):
      neu[yr_in - yr_out]+= 1
    if(t[0] == "NEG"):
      neg[yr_in - yr_out]+= 1

  for i in range(0,50) :
    if(mlist[i]>0):
      pos[i] = (pos[i]/mlist[i] + 0.05)/1.15
      neu[i] = (neu[i]/mlist[i] + 0.05)/1.15
      neg[i] = (neg[i]/mlist[i]+ 0.05)/1.15

    else :
      pos[i] = .33
      neu[i] =  .33
      neg[i] = .33

  print (mlist[0:max_val])
  # print cnt
  # print aan
  # print md
  # print ne
  # print bg
  # print cm
  x = [i for i in range(0,50)]
  xinterp = [i for i in range(0,max_val)]

  posinterp = np.interp(xinterp, x, pos)
  neuinterp = np.interp(xinterp, x, neu)
  neginterp = np.interp(xinterp, x, neg)

  fig = pylab.figure()
  pylab.plot(xinterp, posinterp, 'b', label='POS')
  pylab.plot(xinterp, neuinterp,'g', label='NEU')
  pylab.plot(xinterp, neginterp, 'k', label='NEG')
  fig.suptitle('Temporal analysis of sentiments for '+filename, fontsize=14)
  pylab.xlabel('Years from publication', fontsize=14)
  pylab.ylabel('Fraction of tags', fontsize=14)
  pylab.legend(loc='best', prop={'size':10})
  pp.savefig(fig)
  pp.close()


def generate_plot_tags(filename) :
  pp = PdfPages(filename+'_tags.pdf')
  fname = open("Network.txt")
  tags = open("TagOutputs")
  dict = {}
  d = {}
  d["AAN"] = 0
  d["MD"] = 1
  d["NE"] = 2
  d["CM"] = 3
  d["BG"] = 4

  dc = {}
  mlist = [0]*50
  aan = [0]*50
  md = [0]*50
  ne = [0]*50
  bg = [0]*50
  cm = [0]*50

  yr_out = int(filename[1:3])
  if(yr_out<=13) :
    yr_out+=2000
  else :
    yr_out+=1900

  max_val = 0
  for f,t in zip(fname.readlines(), tags.readlines()) : 
    s = f.split('\t')
    s[1] = s[1].strip('\n')
    if(not(s[1] == filename)) :
      continue

    yr_in = int(s[0][1:3])

    if(yr_in<=13) :
      yr_in+=2000
    else :
      yr_in+=1900
    if(yr_in - yr_out > max_val) :
      max_val = yr_in - yr_out
    # print yr_in, yr_out
    t = t.split('\n')

    mlist[yr_in - yr_out]+=1
    if(t[0] == "AAN"):
      aan[yr_in - yr_out]+= 1
    if(t[0] == "MD"):
      md[yr_in - yr_out]+= 1
    if(t[0] == "CM"):
      cm[yr_in - yr_out]+= 1
    if(t[0] == "NE"):
      ne[yr_in - yr_out]+= 1
    if(t[0] == "BG"):
      bg[yr_in - yr_out]+= 1

  for i in range(0,50) :
    if(mlist[i]>0):
      aan[i] = (aan[i]/mlist[i] + 0.05)/1.25
      md[i] = (md[i]/mlist[i] + 0.05)/1.25
      cm[i] = (cm[i]/mlist[i]+ 0.05)/1.25
      ne[i] = (ne[i]/mlist[i]+ 0.05)/1.25
      bg[i] = (bg[i]/mlist[i]+ 0.05)/1.25

    else :
      aan[i] = .2
      md[i] =  .2
      cm[i] = .2
      ne[i] = .2
      bg[i] = .2

  print (mlist[0:max_val])
  x = [i for i in range(0,50)]
  xinterp = [i for i in range(0,max_val+1)]

  aaninterp = np.interp(xinterp, x, aan)
  neinterp = np.interp(xinterp, x, ne)
  bginterp = np.interp(xinterp, x, bg)
  cminterp = np.interp(xinterp, x, cm)
  mdinterp = np.interp(xinterp, x, md)

  fig = pylab.figure()
  pylab.plot(xinterp, aaninterp, 'b', label='AAN')
  pylab.plot(xinterp, mdinterp, 'g', label='MD')
  pylab.plot(xinterp, neinterp, 'k', label='NE')
  pylab.plot(xinterp, bginterp, 'y', label='BG')
  pylab.plot(xinterp, cminterp, 'r',label = 'CM')

  fig.suptitle('Temporal analysis of tags for '+filename, fontsize=14)
  pylab.xlabel('Years from publication', fontsize=14)
  pylab.ylabel('Fraction of tags', fontsize=14)
  pylab.legend(loc='best', prop={'size':10})
  pp.savefig(fig)
  pp.close()


def main() :
  fname = open("Query.txt")
  filename = fname.readline().strip('\n')
  filename = filename
  generate_plot_tags(filename)
  # generate_plot_sentiment(filename)

if __name__ == '__main__':
  main()