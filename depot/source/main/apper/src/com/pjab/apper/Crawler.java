package com.pjab.apper;

import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.util.List;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ConcurrentLinkedQueue;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.apache.http.HttpException;
import org.apache.http.HttpRequest;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.protocol.RequestDefaultHeaders;
import org.apache.http.impl.client.AbstractHttpClient;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.protocol.HTTP;
import org.apache.http.protocol.HttpContext;
import org.apache.http.protocol.RequestUserAgent;

public class Crawler implements Runnable{
	
	//public static final String PJAB_USER_AGENT = "iTunes/4.2 (Macintosh; U; PPC Mac OS X 10.2) ";
	//public static final String PJAB_USER_AGENT = "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.8) Gecko/20100723 Ubuntu/8.04 (hardy) Firefox/3.6.8) ";
	public static final String PJAB_USER_AGENT = "powerjab_user_agent";
	
	
	
	
	
	
	ConcurrentLinkedQueue <URLInfo> m_processQueue;
	ConcurrentHashMap<String, Boolean> m_seenURLs;
	private int m_crawlDepth;
	
	
	public Crawler(ConcurrentLinkedQueue <URLInfo> processQueue, ConcurrentHashMap<String, Boolean> seenURLs,  int depth) 
	{
		
		m_crawlDepth = depth;
		m_seenURLs = seenURLs;
		m_processQueue = processQueue;
				
	}
	
	


	public void run() {
		while(!m_processQueue.isEmpty())
		{
			URLInfo urlInfo;
			urlInfo = m_processQueue.remove();
			crawl(urlInfo);
		}
		
	
		
	}
	
	
	
	public String  crawl(URLInfo curURLInfo)
	{
		if(m_seenURLs.containsKey(curURLInfo.m_url))
			return "";
		
		int processQSize = m_processQueue.size();

		System.out.println("Processing url " + curURLInfo.toString() + "Remainiing urls:" + processQSize);
		m_seenURLs.put(curURLInfo.m_url, Boolean.TRUE);
		String response = getResponse(curURLInfo.m_url);
		LinkGetter linkGetter = new LinkGetter("PjabLinkGetter");
		List<String> links = linkGetter.getLinks(curURLInfo.m_url,response);
		for(int i=0; i<links.size(); i++)
		{
			
			String newURL = links.get(i);
			newURL = cleanURL(newURL);
			if(ignoreURL(newURL)) continue;
			if(m_seenURLs.containsKey(newURL)) continue;
			
			//System.out.println("Adding key " + newURL);
			int newDepth = curURLInfo.m_depth + 1;
			if(newDepth <= m_crawlDepth)
			{
				URLInfo newURLInfo = new URLInfo(newURL, "", newDepth);
				m_processQueue.add(newURLInfo);
			}
			
			

		}
		
		if(!response.contains("html"))
		{
			System.err.println("Received binary file. Skipping " + curURLInfo.m_url);
			return "";
		}
		
		Pattern appPattern = Pattern.compile("app\\/");
		Matcher m = appPattern.matcher(curURLInfo.m_url);
		if(m.find())
		{
			String filename = curURLInfo.m_url;
			filename = filename.replace('/', '_');
			int ind = filename.lastIndexOf("_app_");
			boolean toPrint= true;
			if(ind != -1)
				filename = filename.substring(ind+5);
			else
				toPrint = false;
			
			ind = filename.indexOf("?"); 
			if(ind != -1)
				filename = filename.substring(0,ind);
			
			if(filename.length() == 0)
				toPrint = false;
			
			if(toPrint)
			{
				Utils.printToFile("apps/" + filename, response);
				return filename;
			}
			
			
		}
		return "";
		
			
	}
	
	

	
	
	String cleanURL(String url)
	{
		int index = url.indexOf((int)'#');
		if (index == -1)
			return url;
		return url.substring(0,index);
	
	}
	
	boolean ignoreURL(String url)
	{
		boolean toIgnore = true;
		
		if(!url.startsWith("http://")) return toIgnore;
		if(url.startsWith("https")) return toIgnore;
		
		
		
		Pattern appPattern = Pattern.compile("app\\/");
		Pattern genrePattern = Pattern.compile("genre\\/");
		Matcher m = appPattern.matcher(url);
		if(m.find())
		{
			toIgnore = false;
			return toIgnore;
		}
		m = genrePattern.matcher(url);
		if(m.find())
		{
			toIgnore = false;
			return toIgnore;
		}
		
		
		return toIgnore;
	}
	
	
	String getResponse(String url)
	{
		DefaultHttpClient httpclient = new DefaultHttpClient();
	 	httpclient.removeRequestInterceptorByClass(RequestUserAgent.class);
	 	((AbstractHttpClient) httpclient).addRequestInterceptor(new RequestUserAgent() {

	 	    public void process(
	 	            HttpRequest request, HttpContext context)
	 	            throws HttpException, IOException {
	 	        request.setHeader(HTTP.USER_AGENT, PJAB_USER_AGENT);
	 	    }
	 	    
	 	});
	 	((AbstractHttpClient) httpclient).addRequestInterceptor(new RequestDefaultHeaders() {

	 	    public void process(
	 	            HttpRequest request, HttpContext context)
	 	            throws HttpException, IOException {
	 	        request.setHeader("H", "X-Apple-Store-Front: 143441-1");
	 	    }
	 	    
	 	});
	 	HttpGet httpget = new HttpGet (url);
	 	ResponseHandler<String> responseHandler = new BasicResponseHandler();
	    try {
			String responseBody = httpclient.execute(httpget, responseHandler);
			httpclient.getConnectionManager().shutdown();
			return responseBody;
		} catch (ClientProtocolException e) {
			System.err.println(e.toString());
			e.printStackTrace();
		} catch (IOException e) {

			System.err.println(e.toString());
			e.printStackTrace();
		}
		httpclient.getConnectionManager().shutdown();
		return "";
	}



	

}
