package com.pjab.apper;

import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ConcurrentLinkedQueue;

public class AppFetcher {
	
	
	public static void main(String [] args) throws Exception
	{
	

		ConcurrentLinkedQueue<URLInfo>processQueue = new ConcurrentLinkedQueue<URLInfo>();
		ConcurrentHashMap<String, Boolean> seenURLs = new ConcurrentHashMap<String, Boolean>();
		
		
		String seedURL = "http://itunes.apple.com/en/app/linkedin/id288429040?mt=8";
		URLInfo newURLInfo = new URLInfo(seedURL,"",0);
		
		Crawler crawler = new Crawler(processQueue,seenURLs,0);
		String fileName = crawler.crawl(newURLInfo);
		fileName = "apps/"+ fileName;
		System.out.println(fileName);
		

		AppParser parser = new AppParser(fileName);
		Map<String, String> fieldData = parser.parse();
		
		String output = parser.toString(fieldData);
		System.out.println(output);
		
		
		
	}

}
