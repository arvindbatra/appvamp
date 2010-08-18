package com.pjab.apper;

public class URLInfo {
	
	public URLInfo (String url, String title, int depth)
	{
		m_url = url;
		m_title = title;
		m_depth = depth;
	
	}
	
	public String toString()
	{
		return "url:" + m_url + "  depth:" + m_depth;   
	}
	
	public String m_url;
	public String m_title;
	public int m_depth;
	

}
