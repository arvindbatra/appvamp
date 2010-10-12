package com.appvamp.applet;
import java.io.*;
import java.util.*;
import com.appvamp.applet.nanoxml.*;

public class PlistParser
{

	public Map<String,Object> parseXML(XMLElement elem)
	{
		HashMap<String, Object> cache = new HashMap<String, Object> ();
		try {
		Stack<String> keyPath=new Stack<String>();
		readNode(elem, keyPath, cache);
		}catch (Exception e)
		{

			System.out.println("Failed to parse " + elem.toString() + " " + e.getMessage());

		}

		return cache;
	}

	private static void readNode(XMLElement node, Stack<String> keyPath, HashMap<String, Object> cache) throws IOException {
	 String name = node.getName();
	 if (name.equals("plist")) {
		readPList(node,keyPath,cache);
	 } else if (name.equals("dict")) {
		readDict(node,keyPath,cache);
	 } else if (name.equals("array")) {
		readArray(node,keyPath,cache);
	 } else {
		readValue(node,keyPath,cache);
	 }
	}


private static void readPList(XMLElement plist, Stack<String> keyPath, HashMap<String, Object> cache)  throws IOException{
	 ArrayList<XMLElement>children=plist.getChildren();
	 for (int i=0,n=children.size();i<n;i++) {
		readNode(children.get(i),keyPath,cache);
	 }
	}
	private static void readDict(XMLElement dict, Stack<String> keyPath, HashMap<String, Object> cache) throws IOException {
	 ArrayList<XMLElement>children=dict.getChildren();
	 for (int i=0,n=children.size();i<n;i+=2) {
		XMLElement keyElem = children.get(i);
		if (!keyElem.getName().equals("key")) {
		  throw new IOException("missing dictionary key at"+keyPath);
		}
		keyPath.push(keyElem.getContent());
		readNode(children.get(i+1),keyPath,cache);
		keyPath.pop();
	 }
	}
	private static void readArray(XMLElement array, Stack<String> keyPath, HashMap<String, Object> cache)  throws IOException{
	 ArrayList<XMLElement>children=array.getChildren();
	 for (int i=0,n=children.size();i<n;i++) {
		keyPath.push(Integer.toString(i));
		readNode(children.get(i),keyPath,cache);
		keyPath.pop();
	 }
	}
	private static void readValue(XMLElement value, Stack<String> keyPath, HashMap<String, Object> cache)  throws IOException{
	 StringBuffer key=new StringBuffer();
	 for (Iterator<String> i=keyPath.iterator();i.hasNext();) {
		key.append(i.next());
		if (i.hasNext()) {
		  key.append('_');
		}
	 }
	 cache.put(key.toString(), value.getContent());
	}
}
