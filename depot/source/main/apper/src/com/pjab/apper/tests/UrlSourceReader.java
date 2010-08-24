package com.pjab.apper.tests;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ConcurrentLinkedQueue;

import com.pjab.apper.AppData;
import com.pjab.apper.AppParser;
import com.pjab.apper.Crawler;
import com.pjab.apper.DataMapping;
import com.pjab.apper.DatabaseConfig;
import com.pjab.apper.DatabaseUtils;
import com.pjab.apper.URLInfo;
import com.pjab.apper.Utils;

public class UrlSourceReader 
{
	List<String> urls;
	
	public UrlSourceReader() {
		// TODO Auto-generated constructor stub
		urls = new ArrayList<String>();
	}
	public void readURLs( String file ) throws IOException {
	    BufferedReader reader = new BufferedReader( new FileReader (file));
	    String line  = null;
	    while( ( line = reader.readLine() ) != null ) {
	        urls.add(line);
	     }
	    
	    
	}
	
	
	private void getApps() throws InterruptedException
	{
		ConcurrentLinkedQueue<URLInfo>processQueue = new ConcurrentLinkedQueue<URLInfo>();
		ConcurrentHashMap<String, Boolean> seenURLs = new ConcurrentHashMap<String, Boolean>();
		
		Crawler crawler = new Crawler(processQueue,seenURLs,0);
		
		for(int i=0; i<urls.size(); i++)
		{
			String seedURL = urls.get(i);
			URLInfo newURLInfo = new URLInfo(seedURL,"",0);
			crawler.crawl(newURLInfo);
			Thread.sleep(1000);
		}
		
	}
	
	
	private void parseFiles() throws Exception
	{
		File dir = new File("apps");
        File [] files = dir.listFiles();
		Map<String,DataMapping> mappings = DataMapping.readJson("data/dataMappings.json");
		DataMapping dm = mappings.get("itunes_web_html_mapping");
		Connection conn = DatabaseConfig.getInstance().getConnection();
		for( File f: files)
        {
			
        	String appFileName = "apps/" + f.getName();
        	AppParser parser = new AppParser(appFileName);
    		try {
    			AppData appData = parser.parseWithDataMappings(dm);
    			String appDataFile = "appData1/" + f.getName();
    			System.out.println(appDataFile);
    			Utils.printToFile(appDataFile, appData.toJSON());
    		
    			System.out.println("Writing to database");
    			DatabaseUtils.insertAppInfo(conn, appData);
    			
    		}catch (Exception e)
    		{
    			System.out.println("Exception thrown for file" + e.getMessage());
    			e.printStackTrace();
    			//break;
    		}
    		
    	}
		
		DatabaseConfig.getInstance().terminateConnection();
        	
	}
	

	
	public static void main(String[] args) throws Exception
	{
		String filename = "data/top50appslist.txt";
		
		UrlSourceReader reader = new UrlSourceReader();
		
		reader.readURLs(filename);
		//reader.getApps();
		reader.parseFiles();
	}

}
