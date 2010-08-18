package com.pjab.apper;


import com.pjab.apper.URLInfo;


import java.util.ArrayList;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.ConcurrentLinkedQueue;

public class CrawlAppStrore {
	
	public static final String SEED_URL = "http://itunes.apple.com/us/genre/mobile-software-applications/id7003";
	public static final int MAX_CRAWL_DEPTH = 5;
	
	
	 public static void main(String[] args) throws Exception {

		 	System.out.println("Hello world!");
		 	
		 	
		 	int depth = MAX_CRAWL_DEPTH;
		 	String seedURL = SEED_URL;
			System.out.println("Calling crawl with url " + seedURL + " and maxDepth:" + depth);

			ConcurrentLinkedQueue<URLInfo>processQueue = new ConcurrentLinkedQueue<URLInfo>();
			ConcurrentHashMap<String, Boolean> seenURLs = new ConcurrentHashMap<String, Boolean>();
			
			URLInfo newURLInfo = new URLInfo(seedURL,"",0);
			processQueue.add(newURLInfo);
			
			
			int numThreads = 5;
			
			Thread thread0 = new Thread(new Crawler(processQueue,seenURLs,depth), "thread0");
			ArrayList<Thread> crawlThreads = new ArrayList<Thread>();
			crawlThreads.add(thread0);
			
			thread0.start();
			Thread.currentThread().sleep(2000);
			
			for(int i=1; i<numThreads; i++)
			{
				Thread thread = new Thread(new Crawler(processQueue,seenURLs, depth), "thread"+i);
				thread.start();
				crawlThreads.add(thread);
				
			}
			
			for(int i=0; i<crawlThreads.size(); i++)
				crawlThreads.get(i).join();
				
				
			
			
			
		 	
		 	
		 	
		 	
		 	
		 	
		 	
		 	/*DefaultHttpClient httpclient = new DefaultHttpClient();
		 	httpclient.removeRequestInterceptorByClass(RequestUserAgent.class);
		 	((AbstractHttpClient) httpclient).addRequestInterceptor(new RequestUserAgent() {

		 	    public void process(
		 	            HttpRequest request, HttpContext context)
		 	            throws HttpException, IOException {
		 	        request.setHeader(HTTP.USER_AGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.8) Gecko/20100723 Ubuntu/8.04 (hardy) Firefox/3.6.8)");
		 	    }
		 	    
		 	});

		 	((AbstractHttpClient) httpclient).addRequestInterceptor(new RequestDefaultHeaders() {

		 	    public void process(
		 	            HttpRequest request, HttpContext context)
		 	            throws HttpException, IOException {
		 	        request.setHeader("H", "X-Apple-Store-Front: 143441-1");
		 	    }
		 	    
		 	});
		 	

		 	
		 	String url = "http://itunes.apple.com/us/genre/mobile-software-applications/id7004?letter=A";
		    //String url = "http://itunes.apple.com/us/genre/mobile-software-applications/id6018?mt=8&letter=F&page=2#page";
	        HttpGet httpget = new HttpGet(url); 
	        System.out.println("executing request " + httpget.getURI());
	        // Create a response handler
	        ResponseHandler<String> responseHandler = new BasicResponseHandler();
	        String responseBody = httpclient.execute(httpget, responseHandler);
	        //System.out.println(responseBody);
	        
	        
	        System.out.println("----------------------------------------");
	        
	        FileWriter fstream = new FileWriter("out.txt");
	        BufferedWriter out = new BufferedWriter(fstream);
	        out.write(responseBody);
	        
	        
	        
	        LinkGetter linkGetter = new LinkGetter("PjabLinkGetter");
	        List<String> links = linkGetter.getLinks(url,responseBody);
	        
	        for(int i=0; i<links.size(); i++)
	        {
	        	System.out.println(links.get(i));
	        	out.write(links.get(i) + "\n");
	        }
	        
	        Crawler crawler = new Crawler(url,-1);
	        System.out.println("Before:" + url);
	        url = crawler.cleanURL(url);
	        System.out.println("After" + url);
	        
	        out.close();
	        

	        // When HttpClient instance is no longer needed, 
	        // shut down the connection manager to ensure
	        // immediate deallocation of all system resources
	        httpclient.getConnectionManager().shutdown();        
	  		 */
		 
		 
	 }


}



