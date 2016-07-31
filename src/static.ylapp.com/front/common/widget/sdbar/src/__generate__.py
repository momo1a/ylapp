#-*- encoding:utf-8 -*-
#Author: 陆楚良
#   Q Q: 874449204

#说明：
#   __generate__.py     为打包脚本文件
#   __main__.js         为源码主文件
#   其它文件是自定义的，为拆开的源码文件
#
#   在源码文件中，使用   // <include: filename.js>   的注释方法进行包含另一文件
#   只有__main__.js支持在第一行代码中写入注释   // <out: output.js>  声明打包后的文件存放路径

import os,time
date = time.strftime("%Y-%m-%d %H:%M:%S")
class Generate:
    def __init__(self, filename):
        filename = os.path.abspath(filename)
        dirname  = os.path.dirname(filename)+os.path.sep
        print "  input: "+filename
        generate = open(filename)
        outfile  = generate.readline().strip()
        lnum = 0
        if outfile[0:8]=="// <out:" and outfile[-1:]==">":
            outfile  = os.path.abspath(dirname + outfile[8:].strip()[:-1].strip())
            print " output: "  + outfile
            self.out = open(outfile, "w")
            while True:
                line = generate.readline()
                if line:
                    lnum += 1
                    l = line.strip()
                    if l[0:12]=="// <include:" and l[-1:]==">":
                        indent   = line[0:line.find(l)]
                        filename = dirname+l[12:].strip()[:-1].strip()
                        outstr,outnum = self.__include(filename, indent, lnum)
                        self.out.write(line.replace(l, l+" line: "+str(lnum)+"-"+str(outnum)))
                        lnum = outnum
                        self.out.write(outstr)
                    elif l[0]=="*":
                        self.out.write(line.replace("{date}", date))
                    else:
                        self.out.write(line)
                else:
                    break
            self.out.close();
            print "Success: " + outfile
        else:
            print "  Error: Output file does not exist."
        generate.close()
    def __include(self, filename, indent, lnum):
        filename = os.path.abspath(filename)
        dirname  = os.path.dirname(filename)+os.path.sep
        outstr = "";
        print "include: " + filename
        f = open(filename)
        while True:
            line = f.readline()
            if line:
                lnum += 1
                l = line.strip()
                if l[0:12]=="// <include:" and l[-1:]==">":
                    indent   = indent+line[0:line.find(l)]
                    filename = dirname+l[12:].strip()[:-1].strip()
                    outstr2,outnum2 = self.__include(filename, indent, lnum)
                    outstr += indent+line.replace(l, l+" line: "+str(lnum)+"-"+str(outnum2))+outstr2
                    lnum = outnum2
                else:
                    outstr += indent+line
            else:
                break
        f.close()
        return outstr,lnum


Generate(os.path.dirname(__file__)+"/__main__.js")
