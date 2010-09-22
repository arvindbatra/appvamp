
package com.pjab.apper;

import com.pjab.apper.*;
import java.sql.Connection;
import java.util.Properties;


import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.File;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import java.util.ArrayList;
import java.util.List;
import org.apache.http.NameValuePair;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.HttpException;
import org.apache.http.HttpRequest;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.HTTP;
import org.apache.http.params.HttpParams;
import org.apache.http.params.BasicHttpParams;

import org.apache.http.HttpRequest;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.protocol.RequestDefaultHeaders;
import org.apache.http.impl.client.AbstractHttpClient;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.protocol.HTTP;
import org.apache.http.protocol.HttpContext;
import org.apache.http.protocol.RequestUserAgent;
import java.net.URLEncoder;


import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

public class AppCategorizer
{


	public static final String KOSMIX_API_KEY = "0e0a57d55fb2b194cbb56b0b682614";
	public static final String PJAB_USER_AGENT = "pjab_user_agent";



	public JsonObject categorize(String text) 
	{
		try {
			DefaultHttpClient httpclient = new DefaultHttpClient();
			final String data  = URLEncoder.encode(text,"UTF-8");

/*			httpclient.removeRequestInterceptorByClass(RequestUserAgent.class);
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
					request.setHeader("key", "key="+KOSMIX_API_KEY+"&text="+data);
					//request.setHeader("text", data);
				}
				
			});
*/		
			
			String url  = "http://api.kosmix.com/annotate/v1?key=" + KOSMIX_API_KEY + "&text=" + data;
			System.out.println("Calling api for url " + url);
			//String url  = "http://api.kosmix.com/annotate/v1";
			//String url  = "http://www.google.com";
			//HttpPost httppost = new HttpPost (url);
		
			HttpPost httppost = new HttpPost (url);
		
			//TODO: i do not know how to make HttpPost work
/*			HttpParams params = new BasicHttpParams();
			params.setParameter("key", KOSMIX_API_KEY);
			params.setParameter("text", data);
			httppost.setParams(params);
			httppost.addHeader("key", KOSMIX_API_KEY);
			httppost.addHeader("text", data);

			List <NameValuePair> nvps = new ArrayList <NameValuePair>();
	        nvps.add(new BasicNameValuePair("key", KOSMIX_API_KEY));
	        nvps.add(new BasicNameValuePair("text", data));
	        httppost.setEntity(new UrlEncodedFormEntity(nvps, HTTP.UTF_8));

			System.out.println(httppost.getRequestLine().toString());
			System.out.println(httppost.getURI().toString());
			System.out.println((String)((httppost.getParams()).getParameter("key")));
*/
			ResponseHandler<String> responseHandler = new BasicResponseHandler();
			try {
				String responseBody = httpclient.execute(httppost, responseHandler);
				JsonParser jparser  = new JsonParser();
				JsonObject jobj = (jparser.parse(responseBody)).getAsJsonObject();
		
				System.out.println(jobj.toString());	
				httpclient.getConnectionManager().shutdown();
				return jobj;
			} catch (ClientProtocolException e) {
				System.err.println(e.toString());
				e.printStackTrace();
			} catch (IOException e) {

				System.err.println(e.toString());
				e.printStackTrace();
			}
			
			httpclient.getConnectionManager().shutdown();
		}catch(UnsupportedEncodingException e)
		{
			System.err.println(e.getMessage());
		}
		catch (Exception e)
		{
				System.err.println(e.toString());
				e.printStackTrace();

		}

		return null;
	}

	
	



	public static void main(String [] args) throws Exception 
	{
		Properties prop = Utils.loadProperties("default.properties");		
		Connection conn = DatabaseConfig.getInstance().getConnection();
		String appCatDir = prop.getProperty(ApperConstants.OUTPUT_CAT_DIR);

		List<AppInfo> apps = DatabaseUtils.getAllAppData(conn);
		AppCategorizer cat = new AppCategorizer();
		for(int i=0; i<apps.size(); i++)
		{
			
			AppInfo ai = apps.get(i);
			String text = ai.data.get("description");
			String name = ai.data.get("name");
			name = name.trim();
			String seller = ai.data.get("seller");
			String genre = ai.data.get("genre");
			String catData = name + " "  + seller + "  "  + genre + " " + text;
			if(catData.length() > 2000) //Get limit
				catData = catData.substring(0,1999);
			JsonObject obj= cat.categorize(text);
			name = name.replace('/', '_');
			name = name.replace(' ', '-');
			if(obj != null && obj.has("categories"))
			{
				obj.addProperty("name", name);
				//obj.addProperty("catData", catData);
				obj.addProperty("genre", genre);
				obj.addProperty("seller", seller);
				String appFileName =  appCatDir + "/" + name;
    			Utils.printToFile(appFileName, obj.toString());

				Thread.sleep(250);
			}
			else
				System.err.println("Cat failed for app name: " + name); 




		}



	}





}


