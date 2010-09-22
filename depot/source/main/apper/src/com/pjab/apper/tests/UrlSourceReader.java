package com.pjab.apper.tests;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.Properties;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ConcurrentLinkedQueue;

import com.pjab.apper.AppData;
import com.pjab.apper.AppParser;
import com.pjab.apper.Crawler;
import com.pjab.apper.ApperConstants;
import com.pjab.apper.DataMapping;
import com.pjab.apper.DatabaseConfig;
import com.pjab.apper.DatabaseUtils;
import com.pjab.apper.URLInfo;
import com.pjab.apper.Utils;

public class UrlSourceReader 
{
	List<String> urls;
	private Properties props;
	
	public UrlSourceReader( Properties properties) {
		// TODO Auto-generated constructor stub
		urls = new ArrayList<String>();
		props = properties;
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

		String outputAppDir = props.getProperty(ApperConstants.OUTPUT_APP_DIR);
		
		Crawler crawler = new Crawler(processQueue,seenURLs,0, outputAppDir);
		
		for(int i=0; i<urls.size(); i++)
		{
			String seedURL = urls.get(i);
			URLInfo newURLInfo = new URLInfo(seedURL,"",0);
			crawler.crawl(newURLInfo);
			Thread.sleep(2000);
		}
		
	}
	
	
	private void parseFiles() throws Exception
	{
		String appOutDir = props.getProperty(ApperConstants.OUTPUT_APP_DIR);
		String appDataDir = props.getProperty(ApperConstants.OUTPUT_DATA_DIR);
	  	File appDir = new File(appOutDir);
	  	File dataDir = new File(appDataDir);
		if(!dataDir.exists())
		  	dataDir.mkdirs();
		if(!appDir.exists())
		  	appDir.mkdirs();

        File [] files = appDir.listFiles();
		Map<String,DataMapping> mappings = DataMapping.readJson("data/dataMappings.json");
		DataMapping dm = mappings.get("itunes_web_html_mapping");
		Connection conn = DatabaseConfig.getInstance().getConnection();
		for( File f: files)
        {
			
        	String appFileName =  appOutDir + "/" + f.getName();
        	AppParser parser = new AppParser(appFileName);
    		try {
    			AppData appData = parser.parseWithDataMappings(dm);
    			String appDataFile = appDataDir + "/" + f.getName();
    			System.out.println(appDataFile);
    			Utils.printToFile(appDataFile, appData.toJSON());
    		
    			System.out.println("Writing to database");
    			DatabaseUtils.insertAppInfoIfNotExists(conn, appData);
    			
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
		//String filename = "data/top50appslist.txt";
		String filename = "data/popular.txt";
		Properties prop = Utils.loadProperties("default.properties");		

		UrlSourceReader reader = new UrlSourceReader(prop);

		
		//reader.readURLs(filename);
		//reader.getApps();
		reader.parseFiles();
	}

}
